<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userstable;
use App\Models\Videopodcast;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;

class VideoPodcastsController extends Controller
{
    /**
     * Display a listing of the videopodcasts.
     */
    public function index(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_video_podcasts')) {
            abort(403, 'You do not have permission to manage video podcasts.');
        }
        
        $query = Videopodcast::with('user');
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('posted_by', $request->user_id);
        }
        
        $videos = $query->latest()->get();
        return view('adminpages.videopodcast.index', compact('videos'));
    }

    /**
     * Show the form for creating a new videopodcast.
     */
    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_video_podcasts')) {
            abort(403, 'You do not have permission to manage video podcasts.');
        }
        
        return view('adminpages.videopodcast.create');
    }

    /**
     * Store a newly created videopodcast in storage.
     */
    public function store(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_video_podcasts')) {
            abort(403, 'You do not have permission to manage video podcasts.');
        }
        
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'video_podcasts');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $video = Videopodcast::create([
            'name'       => $validatedData['name'],
            'link'       => $validatedData['link'],
            'description' => $validatedData['description'] ?? null,
            'posted_by'  => Auth::id(), // Get from logged in user
            'date_posted' => now(),
        ]);

        // Audit log
        AuditLogService::log(
            'create',
            'VideoPodcast',
            $video->id,
            null,
            ['name' => $video->name]
        );

        return redirect()->route('videopodcasts.index')
                         ->with('success', 'Video podcast created successfully.');
    }

    /**
     * Show the form for editing the specified videopodcast.
     */
    public function edit($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_video_podcasts')) {
            abort(403, 'You do not have permission to manage video podcasts.');
        }
        
        $video = Videopodcast::findOrFail($id);
        return view('adminpages.videopodcast.edit', compact('video'));
    }

    /**
     * Update the specified videopodcast in storage.
     */
    public function update(Request $request, $id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_video_podcasts')) {
            abort(403, 'You do not have permission to manage video podcasts.');
        }
        
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'video_podcasts');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $video = Videopodcast::findOrFail($id);
        
        // Store old values for audit log
        $oldValues = [
            'name' => $video->name,
            'link' => $video->link
        ];
        
        $video->update([
            'name' => $validatedData['name'],
            'link' => $validatedData['link'],
            'description' => $validatedData['description'] ?? null,
        ]);

        // Audit log
        AuditLogService::log(
            'update',
            'VideoPodcast',
            $video->id,
            $oldValues,
            ['name' => $video->name, 'link' => $video->link]
        );

        return redirect()->route('videopodcasts.index')
                         ->with('success', 'Video podcast updated successfully.');
    }

    /**
     * Remove the specified videopodcast from storage.
     */
    public function destroy($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_video_podcasts')) {
            abort(403, 'You do not have permission to manage video podcasts.');
        }
        
        $video = Videopodcast::findOrFail($id);
        
        // Audit log before deletion
        AuditLogService::log(
            'delete',
            'VideoPodcast',
            $video->id,
            ['name' => $video->name],
            null
        );
        
        $video->delete();

        return redirect()->route('videopodcasts.index')
                         ->with('success', 'Video podcast deleted successfully.');
    }
}
