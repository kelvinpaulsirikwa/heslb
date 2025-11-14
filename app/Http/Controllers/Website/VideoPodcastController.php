<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Videopodcast;
use Illuminate\Http\Request;

class VideoPodcastController extends Controller
{
    //
     public function fetchvideolinks()
    {
        // Fetch videos from database, latest first
        $videos = Videopodcast::latest()->get();

        // Pass the videos to the view
        return view('aboutus.partials.videopodcast', compact('videos'));
    }
}
