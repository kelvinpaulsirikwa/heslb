<!-- HESLB News Slider with Fade Transition -->
<div class="heslb-v2-container">
    <!-- Government Header Section -->
  

    @if(isset($latestNews) && $latestNews)
    <section class="heslb-v2-news-section" id="heslb-v2-featured-news">
        <div class="heslb-v2-inner-container">
            <div class="heslb-v2-featured-card">
                <div class="heslb-v2-flex-row heslb-v2-align-center">
                    <div class="heslb-v2-img-col">
                        <div class="heslb-v2-img-wrapper">
                            <img src="{{ ($latestNews->front_image && file_exists(public_path('images/storage/' . $latestNews->front_image))) ? asset('images/storage/' . $latestNews->front_image) : asset('images/static_files/noimagenews.png') }}"
                                 alt="{{ $latestNews->title }}" class="heslb-v2-featured-img">
                            <div class="heslb-v2-img-badge">
                                <span class="heslb-v2-category-tag">HABARI MPYA</span>
                            </div>
                        </div>
                    </div>
                    <div class="heslb-v2-content-col">
                        <div class="heslb-v2-news-content">
                            <h3 class="heslb-v2-news-title">{{ $latestNews->title }}</h3>
                            <p class="heslb-v2-news-summary">{{ Str::limit(html_entity_decode(strip_tags($latestNews->content)), 250) }}</p>
                            <div class="heslb-v2-meta-info">
                                <span class="heslb-v2-meta-item">
                                    <i class="heslb-v2-icon fas fa-clock"></i>
                                    {{ $latestNews->created_at->format('j F Y') }}
                                </span>
                                <span class="heslb-v2-meta-item">
                                    <i class="heslb-v2-icon fas fa-tag"></i>
                                    {{ strtoupper($latestNews->category) }}
                                </span>
                            </div>
                            <a href="{{ route('newscenter.singlenews', $latestNews->id) }}" class="heslb-v2-action-btn">
                                SOMA TAARIFA KAMILI
                                <i class="heslb-v2-icon fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @else
    <header class="heslb-v2-header">
    <div class="heslb-hero">
    <div class="container">
        <h1 class="heslb-hero-title">{{ __('newsevents.heslb_news') }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white">{{ __('publications.home') }}</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    <span>{{ date('l, j F Y') }}</span>
                    </li>
            </ol>
        </nav>
    </div>
</div>
      
    </header>
    @endif

    <style>
    /* Unique CSS for HESLB News Slider v2 */
    .heslb-v2-container {
        font-family: 'Roboto', 'Arial', sans-serif;
        color: #2d3748;
        line-height: 1.6;
    }
    
    .heslb-v2-header {
background: linear-gradient(135deg, #0e9bd5 0%, #2080b3ff 70%, #0e9bd5 100%);
        color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .heslb-v2-inner-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 10px;
        margin-top: 0;
    }
    
    .heslb-v2-flex-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px;
    }
    
    .heslb-v2-align-center {
        align-items: center;
    }
    
    .heslb-v2-py-4 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
    
    .heslb-v2-col-main {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        padding: 0 15px;
    }
    
    .heslb-v2-col-secondary {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
        padding: 0 15px;
    }
    
    .heslb-v2-main-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .heslb-v2-sub-title {
        font-size: 1.1rem;
        margin-bottom: 1rem;
        opacity: 0.9;
    }
    
    .heslb-v2-title-divider {
        height: 4px;
        width: 80px;
        background: linear-gradient(90deg, #0e9bd5 0%, #0c1f38 100%);
        margin: 1rem 0;
        border-radius: 2px;
    }
    
    .heslb-v2-info-group {
        display: flex;
        flex-direction: column;
        gap: 0.8rem;
    }
    
    .heslb-v2-date-info, 
    .heslb-v2-location-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }
    
    .heslb-v2-icon {
        display: inline-block;
        font-style: normal;
        font-weight: 900;
        font-size: 1rem;
    }
    
    .heslb-v2-calendar::before { content: "\f073"; }
    .heslb-v2-location::before { content: "\f3c5"; }
    .heslb-v2-time::before { content: "\f017"; }
    .heslb-v2-author::before { content: "\f007"; }
    .heslb-v2-arrow::before { content: "\f061"; }
    .heslb-v2-chevron-left::before { content: "\f053"; }
    .heslb-v2-chevron-right::before { content: "\f054"; }
    
    .heslb-v2-news-section {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 0.5rem 0 1rem 0;
        position: relative;
        border-bottom: 1px solid #e2e8f0;
    }
    
    .heslb-v2-featured-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        padding: 1rem;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 1px solid #e2e8f0;
        margin-top: 0.5rem;
    }
    
    .heslb-v2-featured-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .heslb-v2-img-col {
        flex: 0 0 41.666667%;
        max-width: 41.666667%;
        padding: 0 10px;
    }
    
    .heslb-v2-content-col {
        flex: 0 0 58.333333%;
        max-width: 58.333333%;
        padding: 0 10px;
    }
    
    .heslb-v2-img-wrapper {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    .heslb-v2-featured-img {
        width: 100%;
        height: auto;
        display: block;
        transition: transform 0.5s ease;
    }
    
    .heslb-v2-img-wrapper:hover .heslb-v2-featured-img {
        transform: scale(1.03);
    }
    
    .heslb-v2-img-badge {
        position: absolute;
        top: 20px;
        left: 20px;
        background: linear-gradient(135deg, #0e9bd5 0%, #0c1f38 100%);
        color: white;
        padding: 0.6rem 1.5rem;
        border-radius: 25px;
        font-size: 0.85rem;
        font-weight: bold;
        text-transform: uppercase;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .heslb-v2-news-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a365d;
        line-height: 1.3;
        text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
        max-width: 100%;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .heslb-v2-news-summary {
        font-size: 1rem;
        line-height: 1.7;
        margin-bottom: 1.8rem;
        color: #4a5568;
        word-wrap: break-word;
        overflow-wrap: break-word;
        hyphens: auto;
        max-width: 100%;
        display: -webkit-box;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .heslb-v2-meta-info {
        display: flex;
        gap: 1.5rem;
        margin-bottom: 2rem;
        font-size: 0.9rem;
        color: #718096;
    }
    
    .heslb-v2-meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .heslb-v2-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.8rem;
        background: linear-gradient(135deg, #0e9bd5 0%, #0c1f38 100%);
        color: white;
        padding: 1rem 2rem;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .heslb-v2-action-btn:hover {
        background: linear-gradient(135deg, #153a6e 0%, #1a365d 100%);
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
    }
    
    
    @media (max-width: 992px) {
        .heslb-v2-col-main,
        .heslb-v2-col-secondary {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .heslb-v2-col-secondary {
            margin-top: 1.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .heslb-v2-main-title {
            font-size: 1.6rem;
        }
        
        .heslb-v2-img-col,
        .heslb-v2-content-col {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .heslb-v2-img-col {
            margin-bottom: 1.5rem;
        }
        
        .heslb-v2-news-title {
            font-size: 1.5rem;
            -webkit-line-clamp: 2;
        }
        
        .heslb-v2-news-summary {
            -webkit-line-clamp: 3;
        }
        
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add smooth animations to the featured news card
        const featuredCard = document.querySelector('.heslb-v2-featured-card');
        if (featuredCard) {
            // Add entrance animation
            featuredCard.style.opacity = '0';
            featuredCard.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                featuredCard.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                featuredCard.style.opacity = '1';
                featuredCard.style.transform = 'translateY(0)';
            }, 100);
        }
    });
    </script>
</div>