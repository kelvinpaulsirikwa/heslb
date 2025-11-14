<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Videopodcast;
use Illuminate\Support\Facades\Auth;

class VideoPodcastsController extends Controller
{
    /**
     * Display a listing of the videopodcasts.
     */
    public function index()
    {
        $videos = Videopodcast::with('user')->latest()->get();
        return view('adminpages.videopodcast.index', compact('videos'));
    }

    /**
     * Show the form for creating a new videopodcast.
     */
    public function create()
    {
        return view('adminpages.videopodcast.create');
    }

    /**
     * Store a newly created videopodcast in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'video_podcasts');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        Videopodcast::create([
            'name'       => $validatedData['name'],
            'link'       => $validatedData['link'],
            'description' => $validatedData['description'] ?? null,
            'posted_by'  => Auth::id(), // Get from logged in user
            'date_posted' => now(),
        ]);

        return redirect()->route('videopodcasts.index')
                         ->with('success', 'Video podcast created successfully.');
    }

    /**
     * Show the form for editing the specified videopodcast.
     */
    public function edit($id)
    {
        $video = Videopodcast::findOrFail($id);
        return view('adminpages.videopodcast.edit', compact('video'));
    }

    /**
     * Update the specified videopodcast in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'video_podcasts');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $video = Videopodcast::findOrFail($id);
        $video->update([
            'name' => $validatedData['name'],
            'link' => $validatedData['link'],
            'description' => $validatedData['description'] ?? null,
        ]);

        return redirect()->route('videopodcasts.index')
                         ->with('success', 'Video podcast updated successfully.');
    }

    /**
     * Remove the specified videopodcast from storage.
     */
    public function destroy($id)
    {
        $video = Videopodcast::findOrFail($id);
        $video->delete();

        return redirect()->route('videopodcasts.index')
                         ->with('success', 'Video podcast deleted successfully.');
    }
}
