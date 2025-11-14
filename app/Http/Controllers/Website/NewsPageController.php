<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Contact;
use Illuminate\Support\Str;

class NewsPageController extends Controller
{
    /**
     * Show all news with Habari Kuu separated.
     */
 public function showAllNews()
{
    // Get the most recent news for top display
    $latestNews = News::where(function($query) {
            $query->where('category', 'general news');
        })
        ->where('date_expire', '>=', now())
        ->latest('created_at')
        ->first();

    // Get all other general news (excluding the latest one)
    $regularNews = News::where(function($query) {
            $query->where('category', 'general news');
        })
        ->where('date_expire', '>=', now())
        ->where('id', '!=', $latestNews ? $latestNews->id : 0)
        ->get();

    // Get published success stories
    $successStories = Contact::where('contact_type', 'success_stories')
        ->where('status', 'seen')
        ->where('published', true)
        ->get();

    // Combine regular news and success stories
    $allResults = collect();
    
    // Add regular news items
    foreach($regularNews as $news) {
        $allResults->push([
            'id' => $news->id,
            'title' => $news->title,
            'content' => $news->content,
            'category' => $news->category,
            'image' => $news->front_image,
            'created_at' => $news->created_at,
            'type' => 'news',
            'route' => 'newscenter.singlenews'
        ]);
    }
    
    // Add success stories
    foreach($successStories as $story) {
        $allResults->push([
            'id' => $story->id,
            'title' => Str::limit($story->message, 100),
            'content' => $story->message,
            'category' => 'successful stories',
            'image' => $story->image,
            'created_at' => $story->created_at,
            'type' => 'story',
            'route' => 'story.showspecific'
        ]);
    }
    
    // Sort by created_at and paginate
    $newsArticles = $allResults->sortByDesc('created_at')->values();
    
    // Manual pagination for the collection
    $perPage = 10;
    $currentPage = request()->get('page', 1);
    $offset = ($currentPage - 1) * $perPage;
    $items = $newsArticles->slice($offset, $perPage)->values();
    
    // Create paginator manually
    $newsArticles = new \Illuminate\Pagination\LengthAwarePaginator(
        $items,
        $newsArticles->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'pageName' => 'page']
    );

    // Example event days (can be fetched dynamically later)
    $eventDays = ['5', '12', '18', '25'];
    $today = now()->format('j');

    return view('newspage.allnewspage', compact('latestNews', 'newsArticles'));
}


    /**
     * Show a specific news article by ID.
     */
    public function showSpecificNews($id)
    {
        $news = News::where('id', $id)
            ->where('date_expire', '>=', now())
            ->firstOrFail();
        return view('newspage.newsdetails', compact('news'));
    }

    /**
     * Fetch news by category.
     */
    
    public function newsByCategory($category)
    {
        // Handle both "success stories" and "successful stories" for backward compatibility
        if ($category === 'successful stories' || $category === 'success stories') {
            // For successful stories, fetch from both News model (admin posted) and Contact model (user submitted)
            // Check for both possible category values in the database
            $adminStories = News::where(function($query) {
                    $query->where('category', 'successful stories')
                          ->orWhere('category', 'success stories');
                })
                ->where('date_expire', '>=', now())
                ->orderBy('created_at', 'desc')
                ->get();
            
            $userStories = Contact::where('contact_type', 'success_stories')
                ->where('status', 'seen')
                ->where('published', true)
                ->orderBy('created_at', 'desc')
                ->get();
            
            // Combine and format both types of stories
            $allStories = collect();
            
            // Add admin posted stories
            foreach($adminStories as $story) {
                $allStories->push([
                    'id' => $story->id,
                    'title' => $story->title,
                    'content' => $story->content,
                    'category' => $story->category,
                    'image' => $story->front_image,
                    'created_at' => $story->created_at,
                    'type' => 'admin_news',
                    'route' => 'newscenter.singlenews'
                ]);
            }
            
            // Add user submitted stories
            foreach($userStories as $story) {
                $allStories->push([
                    'id' => $story->id,
                    'title' => Str::limit($story->message, 100),
                    'content' => $story->message,
                    'category' => 'successful stories',
                    'image' => $story->image,
                    'created_at' => $story->created_at,
                    'type' => 'user_story',
                    'route' => 'story.showspecific'
                ]);
            }
            
            // Sort by created_at and paginate
            $sortedStories = $allStories->sortByDesc('created_at')->values();
            
            // Manual pagination for the collection
            $perPage = 10;
            $currentPage = request()->get('page', 1);
            $offset = ($currentPage - 1) * $perPage;
            $items = $sortedStories->slice($offset, $perPage)->values();
            
            // Create paginator manually
            $newsArticles = new \Illuminate\Pagination\LengthAwarePaginator(
                $items,
                $sortedStories->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'pageName' => 'page']
            );
            
            
            return view('newspage.successstories', compact('newsArticles', 'category'));
        } else {
            // Handle general news category
            $newsArticles = News::where('category', $category)
                ->where('date_expire', '>=', now())
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('newspage.newscategory', compact('newsArticles', 'category'));
        }
    }
//searching 
 public function search(Request $request)
{
    $query = $request->input('search'); // Match the form input name

    // Search in News model
    $newsResults = News::where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('content', 'LIKE', "%{$query}%");
        })
        ->where('date_expire', '>=', now())
        ->get();

    // Search in Success Stories (Contact model) - only published ones
    $storyResults = Contact::where('contact_type', 'success_stories')
        ->where('status', 'seen')
        ->where('published', true)
        ->where(function($q) use ($query) {
            $q->where('message', 'LIKE', "%{$query}%")
              ->orWhere('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%");
        })
        ->get();

    // Combine results and format them consistently
    $allResults = collect();
    
    // Add news results
    foreach($newsResults as $news) {
        $allResults->push([
            'id' => $news->id,
            'title' => $news->title,
            'content' => $news->content,
            'category' => $news->category,
            'image' => $news->front_image,
            'created_at' => $news->created_at,
            'type' => 'news',
            'route' => 'newscenter.singlenews'
        ]);
    }
    
    // Add story results
    foreach($storyResults as $story) {
        $allResults->push([
            'id' => $story->id,
            'title' => Str::limit($story->message, 100),
            'content' => $story->message,
            'category' => 'successful stories',
            'image' => $story->image,
            'created_at' => $story->created_at,
            'type' => 'story',
            'route' => 'story.showspecific'
        ]);
    }
    
    // Sort by created_at and paginate
    $newsArticles = $allResults->sortByDesc('created_at')->values();
    
    // Manual pagination for the collection
    $perPage = 9;
    $currentPage = $request->get('page', 1);
    $offset = ($currentPage - 1) * $perPage;
    $items = $newsArticles->slice($offset, $perPage)->values();
    
    // Create paginator manually
    $newsArticles = new \Illuminate\Pagination\LengthAwarePaginator(
        $items,
        $newsArticles->count(),
        $perPage,
        $currentPage,
        ['path' => $request->url(), 'pageName' => 'page']
    );

    return view('newspage.partial.searchresult', compact('newsArticles', 'query'));
}


}
