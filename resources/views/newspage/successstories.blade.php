@extends('layouts.app')

@section('content')

<div class="container-fluid px-1 py-1">
    <div class="row">
        <!-- Main Content Area -->
        <div class="col-lg-9">
         
        <div class="heslb-hero">
    <div class="container">
        <h1 class="heslb-hero-title">{{ strtoupper($category) }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white">{{ __('publications.home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page" >
                {{ strtoupper($category) }}     
            </li>
            </ol>
        </nav>
    </div>
</div>
<br>

<!-- Success Stories Grid -->
<section class="news-section">
   

    <div class="news-grid">
        @forelse($newsArticles as $story)
        <article class="news-item">
            <div class="news-image-container">
                @if($story['image'] && file_exists(public_path('images/storage/' . $story['image'])))
                    <img src="{{ asset('images/storage/' . $story['image']) }}" 
                         alt="{{ $story['title'] }}" class="news-image">
                @else
                    <img src="{{ asset('images/static_files/noimagenews.png') }}" 
                         alt="{{ $story['title'] }}" class="news-image">
                @endif
                <div class="news-category-badge">
                    @if($story['type'] === 'admin_news')
                    Testimonial Story
                                        @else
                        SUCCESS STORY
                    @endif
                </div>
            </div>
            <div class="news-content">
                <h4 class="news-title">{{ Str::limit($story['title'], 65) }}</h4>
                <p class="news-excerpt">{{ Str::limit(strip_tags($story['content']), 100) }}</p>
                <div class="news-meta">
                    <span class="meta-date">
                        <i class="fas fa-user"></i>
                        @if($story['type'] === 'admin_news')
                            Admin
                        @else
                            User Story
                        @endif
                    </span>
                    <span class="meta-time">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($story['created_at'])->format('j M Y') }}
                    </span>
                </div>
                <a href="{{ route($story['route'], $story['id']) }}" class="read-more">
                    Soma Zaidi <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </article>
        @empty
        <div class="empty-state text-center">
            <div class="empty-icon">
                <i class="fas fa-trophy fa-3x"></i>
            </div>
            <h4>Hakuna Hadithi za Mafanikio</h4>
            <p>Tafadhali rudi baadaye kwa hadithi mpya za mafanikio.</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($newsArticles->hasPages())
    <div class="pagination-container">
        {{ $newsArticles->links() }}
    </div>
    @endif

    <br>
</section>   </div>

        <!-- Government Sidebar -->
        <div class="col-lg-3">
            @include('newspage.partial.sidebar')
        </div>
    </div>
</div>




@include('newspage.partial.script')

<style>
.news-image-container {
    position: relative;
}

.news-category-badge {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(14, 155, 213, 0.9);
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
    max-width: 120px;
    text-align: center;
    line-height: 1.2;
}

.news-title {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    line-height: 1.3 !important;
    margin-bottom: 8px !important;
}

.news-excerpt {
    font-size: 0.95rem !important;
    line-height: 1.4 !important;
    margin-bottom: 12px !important;
}

.news-meta {
    font-size: 0.9rem !important;
}

.read-more {
    font-size: 0.95rem !important;
    font-weight: 500 !important;
}
</style>
@endsection
