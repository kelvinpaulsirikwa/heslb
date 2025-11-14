@extends('layouts.app')

@section('content')

<style>
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #ffffff;
    }

    .page-container {
        width: 100%;
        margin: 0;
        padding: 2rem 1rem;
        background-color: #f9fafb;
    }

    .main-grid {
        display: grid;
        grid-template-columns: 1fr 320px;
        gap: 3rem;
        align-items: start;
        max-width: 1200px;
        margin: 0 auto;
    }

    .article-container {
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }

    .article-header {
        display: grid;
        grid-template-columns: 120px 1fr;
        gap: 2rem;
        padding: 2rem;
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
        align-items: center;
    }

    .date-badge {
        text-align: center;
        background: #f8fafc;
        border-radius: 8px;
        padding: 1.5rem 1rem;
        border: 1px solid #e5e7eb;
    }

    .date-badge i {
        font-size: 1.5rem;
        margin-bottom: 0.8rem;
        display: block;
        color: #6b7280;
    }

    .date-badge div {
        font-size: 1.8rem;
        font-weight: 600;
        line-height: 1;
        margin-bottom: 0.3rem;
        color: #374151;
    }

    .date-badge small {
        font-size: 0.85rem;
        color: #6b7280;
        font-weight: 500;
    }

    .header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1.3;
        margin: 0 0 1.5rem 0;
        color: #1f2937;
    }

    .metadata-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 2rem;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .metadata-grid div {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .metadata-grid i {
        color: #9ca3af;
    }

    .featured-image-container {
        position: relative;
        background: #f3f4f6;
        width: 100%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .featured-image-container img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
    }

    .no-image-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: #f3f4f6;
        color: #9ca3af;
    }

    .no-image-placeholder img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: contain;
    }

    .no-image-placeholder i {
        margin-bottom: 1rem;
    }

    .no-image-placeholder p {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 500;
    }

    .article-content {
        padding: 1.5rem;
        line-height: 1.6;
        color: #374151;
    }

    .news-article-content-body {
        font-size: 1.05rem;
        max-width: none;
        line-height: 1.4 !important;
    }

    .news-article-content-body h1, .news-article-content-body h2, .news-article-content-body h3 {
        color: #1f2937;
        margin-top: 1.2rem;
        margin-bottom: 0.8rem;
    }

    .news-article-content-body p {
        margin: 0 !important;
        padding: 0 !important;
        margin-bottom: 1em !important;
    }

    .news-article-content-body p:empty {
        display: none !important;
    }

    .news-article-content-body p:first-child {
        margin-top: 0 !important;
    }

    .news-article-content-body p:last-child {
        margin-bottom: 0 !important;
    }
    
    .news-article-content-body br {
        display: none !important;
    }
    
    .news-article-content-body * {
        margin-block-start: 0 !important;
        margin-block-end: 0 !important;
    }

    .article-footer {
        padding: 1.5rem 2.5rem;
        background: #f9fafb;
        border-top: 1px solid #e5e7eb;
        text-align: center;
        color: #6b7280;
        font-size: 0.9rem;
    }

    .sidebar {
        background: #ffffff;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
        padding: 0;
        height: fit-content;
    }

    @media (max-width: 900px) {
        .main-grid {
            grid-template-columns: 1fr;
        }
        
        .article-header {
            display: flex;
            flex-direction: row;
            gap: 1rem;
            align-items: flex-start;
            padding: 1.2rem;
        }
        
        .date-badge {
            flex-shrink: 0;
            padding: 0.6rem;
            min-width: 70px;
        }
        
        .date-badge i {
            font-size: 1rem;
            margin-bottom: 0.3rem;
        }
        
        .date-badge div {
            font-size: 1.1rem;
        }
        
        .date-badge small {
            font-size: 0.7rem;
        }
        
        .header-content {
            flex: 1;
            min-width: 0;
        }
        
        .header-content h1 {
            font-size: 1.3rem;
            margin-bottom: 0.4rem;
            line-height: 1.2;
        }
        
        .metadata-grid {
            margin-top: 0.3rem;
            font-size: 0.8rem;
        }
        
        .featured-image-container {
            width: 100%;
            height: auto;
        }
        
        .featured-image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
    }

    @media (max-width: 640px) {
        .page-container {
            padding: 0.8rem 0.4rem;
        }
        
        .article-header {
            padding: 1rem;
            gap: 0.8rem;
        }
        
        .date-badge {
            padding: 0.5rem;
            min-width: 65px;
        }
        
        .date-badge i {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }
        
        .date-badge div {
            font-size: 1rem;
        }
        
        .date-badge small {
            font-size: 0.65rem;
        }
        
        .header-content h1 {
            font-size: 1.2rem;
            margin-bottom: 0.3rem;
        }
        
        .metadata-grid {
            font-size: 0.75rem;
            margin-top: 0.2rem;
        }
        
        .article-content {
            padding: 1rem;
        }
        
        .news-article-content-body {
            font-size: 0.9rem;
        }
        
        .article-footer {
            padding: 0.8rem;
            font-size: 0.8rem;
        }
        
        .featured-image-container {
            width: 100%;
            height: auto;
        }
        
        .featured-image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
    }

    @media (max-width: 480px) {
        .page-container {
            padding: 0.5rem 0.2rem;
        }
        
        .article-header {
            padding: 0.8rem;
            gap: 0.6rem;
        }
        
        .date-badge {
            padding: 0.4rem;
            min-width: 60px;
        }
        
        .date-badge i {
            font-size: 0.8rem;
            margin-bottom: 0.1rem;
        }
        
        .date-badge div {
            font-size: 0.9rem;
        }
        
        .date-badge small {
            font-size: 0.6rem;
        }
        
        .header-content h1 {
            font-size: 1.1rem;
            margin-bottom: 0.2rem;
            line-height: 1.1;
        }
        
        .metadata-grid {
            font-size: 0.7rem;
            margin-top: 0.1rem;
        }
        
        .article-content {
            padding: 0.8rem;
        }
        
        .news-article-content-body {
            font-size: 0.85rem;
        }
        
        .article-footer {
            padding: 0.6rem;
            font-size: 0.75rem;
        }
        
        .featured-image-container {
            width: 100%;
            height: auto;
        }
        
        .featured-image-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
    }
</style>

<div class="page-container">
    <div class="main-grid">
        <!-- Primary Article Section -->
        <article class="article-container">
            
            <!-- Article Header Section -->
            <header class="article-header">
                <!-- Publication Date Badge -->
                <div class="date-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <div>
                        {{ $news->created_at->format('j') }}
                    </div>
                    <small>
                        {{ $news->created_at->format('M Y') }}
                    </small>
                </div>

                <!-- Article Title and Metadata -->
                <div class="header-content">
                    <h1>
                        {{ $news->title }}
                    </h1>

                    <div class="metadata-grid">
                        <div>
                            <i class="fas fa-folder-open"></i>
                            <span>{{ ucfirst($news->category) }}</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Featured Image Section -->
            <div class="featured-image-container">
                @if($news->front_image && file_exists(public_path('images/storage/' . $news->front_image)))
                    <img src="{{ asset('images/storage/' . $news->front_image) }}" 
                         alt="{{ $news->title }}" 
                         class="featured-image">
                @else
                    <div class="no-image-placeholder">
                    <img src="{{ asset('images/static_files/noimagenews.png') }}" 
                         alt="{{ $news->title }}" 
                         class="featured-image">
                    </div>
                @endif
            </div>

            <!-- Article Content Body -->
            <div class="article-content">
                <div class="news-article-content-body">
                    @php
                        // Remove inline styles and extra spacing from the content
                        $cleanContent = preg_replace('/style="[^"]*"/', '', $news->content);
                        $cleanContent = preg_replace('/class="[^"]*"/', '', $cleanContent);
                        $cleanContent = preg_replace('/<p>\s*<\/p>/', '', $cleanContent);
                        $cleanContent = preg_replace('/<p>\s*&nbsp;\s*<\/p>/', '', $cleanContent);
                        $cleanContent = preg_replace('/(<br\s*\/?>\s*)+/', '', $cleanContent);
                        $cleanContent = preg_replace('/\s+<\/p>/', '</p>', $cleanContent);
                        $cleanContent = preg_replace('/<p>\s+/', '<p>', $cleanContent);
                    @endphp
                    {!! $cleanContent !!}
                </div>
            </div>

            <!-- Article Footer -->
            <footer class="article-footer">
                Published: {{ $news->created_at->format('F j, Y \a\t g:i A') }}
            </footer>
        </article>

        <!-- Sidebar Section -->
        <div class="sidebar">
            @include('newspage.partial.sidebar')
        </div>
    </div>
</div>

@endsection