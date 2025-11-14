<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Userstable;
use Illuminate\Support\Facades\Auth;

class NewsPagePublishController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('adminpages.newsandevent.allnews', compact('news'));
    }

    /**
     * Show the form for creating new news.
     */
    public function create()
    {
        $categories = ['general news', 'successful stories'];
        return view('adminpages.newsandevent.create', compact('categories'));
    }

    /**
     * Store a newly created news post in storage.
     */
 public function store(Request $request)
{
    try {
        $validatedData = \App\Services\AdminValidationService::validate($request, 'news_publish');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }

    $frontImagePath = null;
    if ($request->hasFile('front_image')) {
        $frontImagePath = $request->file('front_image')->store('news_images', 'public');
    }

    News::create([
        'title'       => $validatedData['title'],
        'content'     => $validatedData['content'],
        'category'    => $validatedData['category'],
        'date_expire' => $validatedData['date_expire'],
        'posted_by'   => Auth::id(),
        'front_image' => $frontImagePath
    ]);

    return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
}

    /**
     * Show the form for editing news.
     */
    public function edit($id)
    {
        $news = News::findOrFail($id);
        $categories = ['general news', 'successful stories'];
        return view('adminpages.newsandevent.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified news.
     */
    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'news_publish');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        if ($request->hasFile('front_image')) {
            $validatedData['front_image'] = $request->file('front_image')->store('news_images', 'public');
        }


        $news->update($validatedData);

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified news.
     */

    public function show($id)
{
    $news = News::findOrFail($id);
    return view('adminpages.newsandevent.show', compact('news'));
}

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}
