<!-- News Articles Grid -->
<section class="news-section">
    <div class="section-header">
        <h2 class="news-section-title">HABARI ZINGINE</h2>
        <div class="section-divider"></div>
        <br>
    </div>
    <div class="news-grid">
        @forelse($newsArticles as $article)
        <article class="news-item">
            <div class="news-image-container">
                @if($article['image'] && file_exists(public_path('images/storage/' . $article['image'])))
                    <img src="{{ asset('images/storage/' . $article['image']) }}" 
                         alt="{{ $article['title'] }}" class="news-image">
                @else
                    <img src="{{ asset('images/static_files/noimagenews.png') }}" 
                         alt="{{ $article['title'] }}" class="news-image">
                @endif
                <div class="news-category {{ $article['type'] === 'story' ? 'category-success' : '' }}">
                    {{ strtoupper($article['category']) }}
                </div>
            </div>
            <div class="news-content">
                <h4 class="news-title">{{ Str::limit($article['title'], 65) }}</h4>
                <p class="news-excerpt">{{ Str::limit(strip_tags($article['content']), 100) }}</p>
                <div class="news-meta">
                    <span class="meta-date">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($article['created_at'])->format('d M Y') }}
                    </span>
                    <span class="meta-time">
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($article['created_at'])->diffForHumans() }}
                    </span>
                </div>
                <a href="{{ route($article['route'], $article['id']) }}" class="read-more">
                    {{ $article['type'] === 'news' ? 'Soma Zaidi' : 'Soma Hadithi' }} <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </article>
        @empty
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-newspaper"></i>
            </div>
            <h4>Hakuna Habari Kwa Sasa</h4>
            <p>Rudi baadaye kuona habari mpya za HESLB</p>
        </div>
        @endforelse
    </div>

    <!-- Professional Pagination -->
    @if(isset($newsArticles) && $newsArticles->hasPages())
    <div class="pagination-container">
        {{ $newsArticles->links('pagination::bootstrap-4') }}
    </div>
    @endif

    <br>
</section>

<style>
/* Reset and override all pagination styles */
.pagination-container {
    text-align: center !important;
    margin: 2rem 0 !important;
    padding: 1rem !important;
}

.pagination {
    display: inline-flex !important;
    flex-wrap: wrap !important;
    justify-content: center !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding: 0 !important;
    margin: 0 !important;
    list-style: none !important;
}

.pagination .page-item {
    display: inline-block !important;
    margin: 0 !important;
}

.pagination .page-link {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 40px !important;
    height: 40px !important;
    padding: 0.5rem 0.75rem !important;
    margin: 0 !important;
    border: 1px solid #e2e8f0 !important;
    background: #ffffff !important;
    color: #1a365d !important;
    text-decoration: none !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    font-size: 0.9rem !important;
    line-height: 1.5 !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
}

.pagination .page-link:hover {
    background: #0e9bd5 !important;
    color: #ffffff !important;
    border-color: #0e9bd5 !important;
    text-decoration: none !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(14, 155, 213, 0.3) !important;
}

.pagination .page-item.active .page-link {
    background: #0e9bd5 !important;
    color: #ffffff !important;
    border-color: #0e9bd5 !important;
    box-shadow: 0 2px 4px rgba(14, 155, 213, 0.2) !important;
}

.pagination .page-item.disabled .page-link {
    background: #f8f9fa !important;
    color: #6c757d !important;
    border-color: #e9ecef !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

.pagination .page-item.disabled .page-link:hover {
    background: #f8f9fa !important;
    color: #6c757d !important;
    border-color: #e9ecef !important;
    transform: none !important;
    box-shadow: none !important;
}

/* Target specific pagination elements */
.pagination li {
    display: inline-block !important;
    margin: 0 !important;
}

.pagination li a,
.pagination li span {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 40px !important;
    height: 40px !important;
    padding: 0.5rem 0.75rem !important;
    margin: 0 !important;
    border: 1px solid #e2e8f0 !important;
    background: #ffffff !important;
    color: #1a365d !important;
    text-decoration: none !important;
    border-radius: 8px !important;
    font-weight: 500 !important;
    font-size: 0.9rem !important;
    line-height: 1.5 !important;
    transition: all 0.3s ease !important;
}

.pagination li a:hover,
.pagination li span:hover {
    background: #0e9bd5 !important;
    color: #ffffff !important;
    border-color: #0e9bd5 !important;
    text-decoration: none !important;
    transform: translateY(-2px) !important;
    box-shadow: 0 4px 8px rgba(14, 155, 213, 0.3) !important;
}

.pagination li.active a,
.pagination li.active span {
    background: #0e9bd5 !important;
    color: #ffffff !important;
    border-color: #0e9bd5 !important;
    box-shadow: 0 2px 4px rgba(14, 155, 213, 0.2) !important;
}

.pagination li.disabled a,
.pagination li.disabled span {
    background: #f8f9fa !important;
    color: #6c757d !important;
    border-color: #e9ecef !important;
    cursor: not-allowed !important;
    opacity: 0.6 !important;
}

/* Mobile responsive */
@media (max-width: 768px) {
    .pagination {
        gap: 0.3rem !important;
        flex-wrap: wrap !important;
    }
    
    .pagination .page-link,
    .pagination li a,
    .pagination li span {
        min-width: 35px !important;
        height: 35px !important;
        font-size: 0.8rem !important;
        padding: 0.4rem 0.6rem !important;
    }
    
    .pagination-container {
        margin: 1.5rem 0 !important;
        padding: 0.5rem !important;
    }
}
</style>