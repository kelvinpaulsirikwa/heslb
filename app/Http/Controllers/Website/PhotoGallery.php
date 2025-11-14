<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Taasisevent;
use App\Models\TaasiseventImage;

class PhotoGallery extends Controller
{
    /**
     * Show all events that have images (like "folders").
     */
    public function allFileManage()
    {
        // Get all events that have at least one image
        $events = Taasisevent::withCount('images')
            ->has('images')
            ->latest()
            ->get();

        return view('aboutus.partials.photogallery', compact('events'));
    }

    /**
     * Show images inside a specific event (like opening a folder).
     */
    public function viewFolder($eventId)
    {
        $event = Taasisevent::with(['images'])
            ->findOrFail($eventId);

        return view('aboutus.partials.folderimagecontent', compact('event'));
    }
}
