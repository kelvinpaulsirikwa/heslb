<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Taasisevent;
use App\Models\TaasiseventImage;
use App\Models\Userstable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;

class PhotoGalleryController extends Controller
{
   
    public function index(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $query = Taasisevent::with(['user', 'images']);
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('posted_by', $request->user_id);
        }
        
        $events = $query->latest()->get();
        return view('adminpages.taasisevents.index', compact('events'));
    }

    /**
     * Show form for creating a new event
     */
    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        return view('adminpages.taasisevents.create');
    }

    /**
     * Store a new event
     */
    public function store(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'photo_gallery');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $event = Taasisevent::create([
            'posted_by'    => auth()->id(),
            'name_of_event'=> $validatedData['name_of_event'],
            'description'    => $validatedData['description'],
        ]);

        // Audit log
        AuditLogService::log(
            'create',
            'Event',
            $event->id,
            null,
            ['name_of_event' => $event->name_of_event]
        );

        return redirect()->route('admin.taasisevents.index')
                         ->with('success', 'Event created successfully.');
    }

    /**
     * Show event details
     */
    public function show($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $event = Taasisevent::with('images', 'user')->findOrFail($id);
        
        // Audit log for viewing
        AuditLogService::log(
            'view',
            'Event',
            $event->id,
            null,
            ['name_of_event' => $event->name_of_event]
        );
        
        return view('adminpages.taasisevents.show', compact('event'));
    }

    /**
     * Show form to edit event
     */
  public function edit($id)
{
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_events')) {
        abort(403, 'You do not have permission to manage events.');
    }
    
    // Fetch the event
    $event = Taasisevent::findOrFail($id);

    // Fetch all images related to this event
    $images = TaasiseventImage::where('taasisevent_id', $id)
                ->orderBy('created_at', 'desc') // optional: newest first
                ->get();

    return view('adminpages.taasisevents.edit', compact('event', 'images'));
}


    /**
     * Update event
     */
    public function update(Request $request, $id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $event = Taasisevent::findOrFail($id);

        // Store old values for audit log
        $oldValues = [
            'name_of_event' => $event->name_of_event,
            'description' => $event->description
        ];

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'photo_gallery_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $event->update([
            'name_of_event'=> $validatedData['name_of_event'],
            'description'    => $validatedData['description'],
        ]);

        // Audit log
        AuditLogService::log(
            'update',
            'Event',
            $event->id,
            $oldValues,
            ['name_of_event' => $event->name_of_event, 'description' => $event->description]
        );

        return redirect()->route('admin.taasisevents.index')
                         ->with('success', 'Event updated successfully.');
    }

    /**
     * Delete event if it has no images
     */
    public function destroy($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $event = Taasisevent::with('images')->findOrFail($id);

        if ($event->images->count() > 0) {
            return back()->with('error', 'Cannot delete event because it has images.');
        }

        // Audit log before deletion
        AuditLogService::log(
            'delete',
            'Event',
            $event->id,
            ['name_of_event' => $event->name_of_event],
            null
        );

        $event->delete();
        return redirect()->route('admin.taasisevents.index')
                         ->with('success', 'Event deleted successfully.');
    }



    //handleling image 
    
    public function addImageForm($eventId)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $event = Taasisevent::findOrFail($eventId);
        return view('adminpages.taasisevents.add_image', compact('event'));
    }

    /**
     * Store uploaded images for an event
     */
    public function storeImage(Request $request, $eventId)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $event = Taasisevent::findOrFail($eventId);

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'photo_gallery_images');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        foreach ($request->file('images') as $index => $image) {
            // Store image using Laravel storage
            $path = $image->store('taasisevents', 'public');

            $eventImage = TaasiseventImage::create([
                'taasisevent_id' => $event->id,
                'posted_by'      => auth()->id(),
                'image_link'     => $path,
                'description'    => $request->descriptions[$index] ?? null,
            ]);

            // Audit log for each image
            AuditLogService::log(
                'create',
                'EventImage',
                $eventImage->id,
                null,
                ['event_id' => $event->id, 'event_name' => $event->name_of_event]
            );
        }

        return redirect()->route('admin.taasisevents.show', $event->id)
                         ->with('success', 'Images uploaded successfully.');
    }

    /**
     * Show form to edit image description
     */
    public function editImage($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $image = TaasiseventImage::findOrFail($id);
        return view('adminpages.taasisevents.edit_image', compact('image'));
    }

    /**
     * Update image description
     */
    public function updateImage(Request $request, $id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $image = TaasiseventImage::findOrFail($id);

        // Store old values for audit log
        $oldValues = [
            'description' => $image->description
        ];

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'photo_gallery_image_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $image->update([
            'description' => $validatedData['description'],
        ]);

        // Audit log
        AuditLogService::log(
            'update',
            'EventImage',
            $image->id,
            $oldValues,
            ['description' => $image->description, 'event_id' => $image->taasisevent_id]
        );

        return redirect()->route('admin.taasisevents.show', $image->taasisevent_id)
                         ->with('success', 'Image updated successfully.');
    }

    /**
     * Delete an image
     */
    public function destroyImage($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_events')) {
            abort(403, 'You do not have permission to manage events.');
        }
        
        $image = TaasiseventImage::findOrFail($id);
        $eventId = $image->taasisevent_id;

        // Audit log before deletion
        AuditLogService::log(
            'delete',
            'EventImage',
            $image->id,
            ['event_id' => $eventId],
            null
        );

        // Delete from storage
        if (Storage::disk('public')->exists($image->image_link)) {
            Storage::disk('public')->delete($image->image_link);
        }

        $image->delete();

        return redirect()->route('admin.taasisevents.show', $eventId)
                         ->with('success', 'Image deleted successfully.');
    }
}

