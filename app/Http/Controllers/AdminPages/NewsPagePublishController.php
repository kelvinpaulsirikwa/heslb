<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Userstable;
use Illuminate\Support\Facades\Auth;
use App\Services\AuditLogService;

class NewsPagePublishController extends Controller
{
    /**
     * Display a listing of the news.
     */
    public function index(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_news')) {
            abort(403, 'You do not have permission to manage news.');
        }
        
        $query = News::query();
        
        // Filter by user if provided
        if ($request->filled('user_id')) {
            $query->where('posted_by', $request->user_id);
        }
        
        $news = $query->latest()->paginate(10)->appends($request->all());
        return view('adminpages.newsandevent.allnews', compact('news'));
    }

    /**
     * Show the form for creating new news.
     */
    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_news')) {
            abort(403, 'You do not have permission to manage news.');
        }
        
        $categories = ['general news', 'successful stories'];
        return view('adminpages.newsandevent.create', compact('categories'));
    }

    /**
     * Store a newly created news post in storage.
     */
 public function store(Request $request)
{
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_news')) {
        abort(403, 'You do not have permission to manage news.');
    }
    
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

    $news = News::create([
        'title'       => $validatedData['title'],
        'content'     => $validatedData['content'],
        'category'    => $validatedData['category'],
        'date_expire' => $validatedData['date_expire'],
        'posted_by'   => Auth::id(),
        'front_image' => $frontImagePath
    ]);

    // Audit log
    AuditLogService::log(
        'create',
        'News',
        $news->id,
        null,
        ['title' => $news->title, 'category' => $news->category]
    );

    return redirect()->route('admin.news.index')->with('success', 'News created successfully.');
}

    /**
     * Show the form for editing news.
     */
    public function edit($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_news')) {
            abort(403, 'You do not have permission to manage news.');
        }
        
        $news = News::findOrFail($id);
        $categories = ['general news', 'successful stories'];
        return view('adminpages.newsandevent.edit', compact('news', 'categories'));
    }

    /**
     * Update the specified news.
     */
    public function update(Request $request, $id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_news')) {
            abort(403, 'You do not have permission to manage news.');
        }
        
        $news = News::findOrFail($id);

        // Store old values for audit log
        $oldValues = [
            'title' => $news->title,
            'category' => $news->category,
            'date_expire' => $news->date_expire
        ];

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

        // Audit log
        AuditLogService::log(
            'update',
            'News',
            $news->id,
            $oldValues,
            ['title' => $news->title, 'category' => $news->category, 'date_expire' => $news->date_expire]
        );

        return redirect()->route('admin.news.index')->with('success', 'News updated successfully.');
    }

    /**
     * Remove the specified news.
     */

    public function show($id)
{
    // Server-side permission check
    /** @var Userstable $user */
    $user = Auth::user();
    if (!$user || !$user->hasPermission('manage_news')) {
        abort(403, 'You do not have permission to manage news.');
    }
    
    $news = News::findOrFail($id);
    
    // Audit log for viewing
    AuditLogService::log(
        'view',
        'News',
        $news->id,
        null,
        ['title' => $news->title]
    );
    
    return view('adminpages.newsandevent.show', compact('news'));
}

    public function destroy($id)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('manage_news')) {
            abort(403, 'You do not have permission to manage news.');
        }
        
        $news = News::findOrFail($id);
        
        // Audit log before deletion
        AuditLogService::log(
            'delete',
            'News',
            $news->id,
            ['title' => $news->title, 'category' => $news->category],
            null
        );
        
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}
