<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Scholarship;

class ScholarshipController extends Controller
{
    public function index()
    {
        $scholarships = Scholarship::where('is_active', true)
            ->orderByDesc('published_at')
            ->orderBy('title')
            ->get();

        if ($scholarships->isEmpty()) {
            return view('scholarships.partial.no-scholarships');
        }

        $current = $scholarships->first();
        return view('scholarships.index', compact('scholarships', 'current'));
    }

    public function show($slug)
    {
        $scholarships = Scholarship::where('is_active', true)
            ->orderByDesc('published_at')
            ->orderBy('title')
            ->get();

        $current = Scholarship::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('scholarships.index', compact('scholarships', 'current'));
    }
}


