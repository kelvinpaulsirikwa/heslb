
<aside class="government-sidebar">
    <!-- Search Portal -->
     
    <div class="sidebar-widget search-widget">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-search"></i>
                TAFUTA HABARI
            </h3>
        </div>
        <div class="widget-content">
            <form action="{{ route('newscenter.searching') }}" method="GET" class="search-form-modern">
                <div class="search-container">
                    <input type="text" name="search" class="search-input-modern" 
                           placeholder="Ingiza neno ..." 
                           value="{{ request('search') }}"
                           autocomplete="off">
                    <button type="submit" class="search-btn">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Latest Updates -->
    <div class="sidebar-widget">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-clock"></i>
                HABARI ZA HIVI KARIBUNI
            </h3>
        </div>
        <div class="widget-content">
            <div class="latest-news-list">
                @php
                // Check if we're on a category page
                $currentCategory = request()->route('category') ?? null;
                $allLatest = collect();
                
                if ($currentCategory) {
                    // If on a specific category page, show recent items from that category
                    if ($currentCategory === 'general news') {
                        $latestNews = \App\Models\News::where('category', 'general news')
                            ->where('date_expire', '>=', now())
                            ->latest('created_at')
                            ->take(5)
                            ->get();
                        
                        foreach($latestNews as $news) {
                            $allLatest->push([
                                'id' => $news->id,
                                'title' => $news->title,
                                'category' => $news->category,
                                'image' => $news->front_image,
                                'created_at' => $news->created_at,
                                'type' => 'news',
                                'route' => 'newscenter.singlenews'
                            ]);
                        }
                    } elseif ($currentCategory === 'successful stories') {
                        // Get admin posted success stories
                        $adminStories = \App\Models\News::where('category', 'successful stories')
                            ->where('date_expire', '>=', now())
                            ->latest('created_at')
                            ->take(3)
                            ->get();
                        
                        foreach($adminStories as $story) {
                            $allLatest->push([
                                'id' => $story->id,
                                'title' => $story->title,
                                'category' => $story->category,
                                'image' => $story->front_image,
                                'created_at' => $story->created_at,
                                'type' => 'news',
                                'route' => 'newscenter.singlenews'
                            ]);
                        }
                        
                        // Get user submitted success stories
                        $userStories = \App\Models\Contact::where('contact_type', 'success_stories')
                            ->where('status', 'seen')
                            ->where('published', true)
                            ->latest('created_at')
                            ->take(2)
                            ->get();
                        
                        foreach($userStories as $story) {
                            $allLatest->push([
                                'id' => $story->id,
                                'title' => Str::limit($story->message, 60),
                                'category' => 'successful stories',
                                'image' => $story->image,
                                'created_at' => $story->created_at,
                                'type' => 'story',
                                'route' => 'story.showspecific'
                            ]);
                        }
                    }
                } else {
                    // If on main news page, show mixed recent content
                    $latestNews = \App\Models\News::where('category', 'general news')
                        ->where('date_expire', '>=', now())
                        ->latest('created_at')
                        ->take(3)
                        ->get();
                    
                    // Get latest success stories from Contact model
                    $latestStories = \App\Models\Contact::where('contact_type', 'success_stories')
                        ->where('status', 'seen')
                        ->where('published', true)
                        ->latest('created_at')
                        ->take(2)
                        ->get();
                    
                    // Add news items
                    foreach($latestNews as $news) {
                        $allLatest->push([
                            'id' => $news->id,
                            'title' => $news->title,
                            'category' => $news->category,
                            'image' => $news->front_image,
                            'created_at' => $news->created_at,
                            'type' => 'news',
                            'route' => 'newscenter.singlenews'
                        ]);
                    }
                    
                    // Add success stories
                    foreach($latestStories as $story) {
                        $allLatest->push([
                            'id' => $story->id,
                            'title' => Str::limit($story->message, 60),
                            'category' => 'successful stories',
                            'image' => $story->image,
                            'created_at' => $story->created_at,
                            'type' => 'story',
                            'route' => 'story.showspecific'
                        ]);
                    }
                }
                
                // Sort by created_at and take 5 most recent
                $allLatest = $allLatest->sortByDesc('created_at')->take(5);
                @endphp
                @foreach($allLatest as $latest)
                <div class="latest-news-item">
                    <div class="latest-image">
                        <img src="{{ ($latest['image'] && file_exists(public_path('images/storage/' . $latest['image']))) ? asset('images/storage/' . $latest['image']) : asset('images/static_files/noimagenews.png') }}" 
                             alt="{{ $latest['title'] }}">
                    </div>
                    <div class="latest-content">
                        <div class="latest-category">{{ strtoupper($latest['category']) }}</div>
                        <h5>
                            <a href="{{ route($latest['route'], $latest['id']) }}">
                                {{ Str::limit($latest['title'], 55) }}
                            </a>
                        </h5>
                        <div class="latest-date">
                            <i class="fas fa-calendar"></i>
                            {{ \Carbon\Carbon::parse($latest['created_at'])->format('j M Y') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Categories Directory -->
    <div class="sidebar-widget">
        <div class="widget-header">
            <h3 class="widget-title">
                <i class="fas fa-folder-open"></i>
                MAKUNDI YA HABARI
            </h3>
        </div>
        <div class="widget-content">
            <div class="categories-list">
                @php
                $allCategories = [
                    [
                        'slug' => 'general news',
                        'icon' => 'fa-globe-africa',
                        'title' => 'GENERAL NEWS',
                        'color' => '#0a5d7a'
                    ],
                    [
                        'slug' => 'successful stories',
                        'icon' => 'fa-trophy',
                        'title' => 'SUCCESSFUL STORIES',
                        'color' => '#084a5f'
                    ]
                ];
                
                // Get counts for each category
                $newsCounts = \App\Models\News::select('category', \DB::raw('count(*) as total'))
                    ->whereIn('category', ['general news', 'successful stories'])
                    ->groupBy('category')
                    ->pluck('total', 'category')
                    ->toArray();
                
                // Get success stories count (user submitted)
                $successStoriesCount = \App\Models\Contact::where('contact_type', 'success_stories')
                    ->where('status', 'seen')
                    ->where('published', true)
                    ->count();
                
                // Combine counts - add user stories to admin stories count
                $adminSuccessStoriesCount = $newsCounts['successful stories'] ?? 0;
                $totalSuccessStoriesCount = $adminSuccessStoriesCount + $successStoriesCount;
                $categoryCounts = array_merge($newsCounts, ['successful stories' => $totalSuccessStoriesCount]);
                @endphp
                @foreach($allCategories as $category)
                <a href="{{ route('newscenter.category', $category['slug']) }}" class="category-link">
                    <div class="category-link-content">
                    <span class="category-icon-small" style="color: '{{ $category['color'] }}'">
                    <i class="fas {{ $category['icon'] }}"></i>
                        </span>
                        <span class="category-name">{{ $category['title'] }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>







</aside>
