<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use App\Models\Taasisevent;
use App\Models\TaasiseventImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class PhotoGalleryController extends Controller
{
   
    public function index()
    {
        $events = Taasisevent::with('user', 'images')->latest()->get();
        return view('adminpages.taasisevents.index', compact('events'));
    }

    /**
     * Show form for creating a new event
     */
    public function create()
    {
        return view('adminpages.taasisevents.create');
    }

    /**
     * Store a new event
     */
    public function store(Request $request)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'photo_gallery');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        Taasisevent::create([
            'posted_by'    => auth()->id(),
            'name_of_event'=> $validatedData['name_of_event'],
            'description'    => $validatedData['description'],
        ]);

        return redirect()->route('admin.taasisevents.index')
                         ->with('success', 'Event created successfully.');
    }

    /**
     * Show event details
     */
    public function show($id)
    {
        $event = Taasisevent::with('images', 'user')->findOrFail($id);
        return view('adminpages.taasisevents.show', compact('event'));
    }

    /**
     * Show form to edit event
     */
  public function edit($id)
{
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
        $event = Taasisevent::findOrFail($id);

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

        return redirect()->route('admin.taasisevents.index')
                         ->with('success', 'Event updated successfully.');
    }

    /**
     * Delete event if it has no images
     */
    public function destroy($id)
    {
        $event = Taasisevent::with('images')->findOrFail($id);

        if ($event->images->count() > 0) {
            return back()->with('error', 'Cannot delete event because it has images.');
        }

        $event->delete();
        return redirect()->route('admin.taasisevents.index')
                         ->with('success', 'Event deleted successfully.');
    }



    //handleling image 
    
    public function addImageForm($eventId)
    {
        $event = Taasisevent::findOrFail($eventId);
        return view('adminpages.taasisevents.add_image', compact('event'));
    }

    /**
     * Store uploaded images for an event
     */
    public function storeImage(Request $request, $eventId)
    {
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

            TaasiseventImage::create([
                'taasisevent_id' => $event->id,
                'posted_by'      => auth()->id(),
                'image_link'     => $path,
                'description'    => $request->descriptions[$index] ?? null,
            ]);
        }

        return redirect()->route('admin.taasisevents.show', $event->id)
                         ->with('success', 'Images uploaded successfully.');
    }

    /**
     * Show form to edit image description
     */
    public function editImage($id)
    {
        $image = TaasiseventImage::findOrFail($id);
        return view('adminpages.taasisevents.edit_image', compact('image'));
    }

    /**
     * Update image description
     */
    public function updateImage(Request $request, $id)
    {
        $image = TaasiseventImage::findOrFail($id);

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

        return redirect()->route('admin.taasisevents.show', $image->taasisevent_id)
                         ->with('success', 'Image updated successfully.');
    }

    /**
     * Delete an image
     */
    public function destroyImage($id)
    {
        $image = TaasiseventImage::findOrFail($id);
        $eventId = $image->taasisevent_id;

        // Delete from storage
        if (Storage::disk('public')->exists($image->image_link)) {
            Storage::disk('public')->delete($image->image_link);
        }

        $image->delete();

        return redirect()->route('admin.taasisevents.show', $eventId)
                         ->with('success', 'Image deleted successfully.');
    }
}

