@php
    $categories = [
        [
            'slug' => 'general news',
            'icon' => 'fas fa-globe-africa',
            'title' => 'GENERAL NEWS',
            'color' => '#000000',
            'bgColor' => '#ffffff',
            'description' => 'General Information & Updates'
        ],
        [
            'slug' => 'successful stories',
            'icon' => 'fas fa-trophy',
            'title' => 'SUCCESSFUL STORIES',
            'color' => '#000000',
            'bgColor' => '#ffffff',
            'description' => 'Inspiring Student Success Stories'
        ],
    ];
@endphp

<!-- Debug: Test if Font Awesome is working -->


<!-- News Categories Section -->
<section class="categories-section">
    <br>
    <div class="section-header">
        <h2 class="news-section-title">{{ __('newsevents.news_categories') }}</h2>
        <div class="section-divider"></div>
    </div>
    <br>
    <div class="categories-grid">
        
        @foreach ($categories as $category)
            <a href="{{ route('newscenter.category', $category['slug']) }}" class="category-item" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
                <div class="category-content">
                    <div class="category-icon">
                        <i class="{{ $category['icon'] }}"></i>
                    </div>
                    <div class="category-info">
                        <h4>{{ $category['title'] }}</h4>
                        <p class="category-description">{{ $category['description'] }}</p>
                    </div>
                </div>
                <div class="category-hover-effect"></div>
            </a>
        @endforeach
        <br>
    </div>
</section>

<style>
/* Compact Smart Categories Section */
.categories-section {
    padding: 0.5rem 0 1rem 0;
    background: white;
    margin: 0.5rem 0;
    border-radius: 15px;
}

.section-header {
    text-align: center;
    margin-bottom: 1rem;
    margin-top: 0.5rem;
}

.news-section-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a365d;
    margin-bottom: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.section-divider {
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, #0e9bd5 0%, #0c1f38 100%);
    margin: 0 auto;
    border-radius: 2px;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.2rem;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1.5rem;
}

.category-item {
    display: block;
    text-decoration: none;
    background: white;
    border-radius: 15px;
    padding: 1.2rem;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    border: 1px solid #e2e8f0;
}

.category-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
}

.category-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    position: relative;
    z-index: 2;
}

.category-icon {
    width: 50px !important;
    height: 50px !important;
    border-radius: 12px !important;
    background: linear-gradient(135deg, #0e9bd5 0%, #0c1f38 100%) !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    flex-shrink: 0 !important;
    transition: transform 0.3s ease !important;
    position: relative !important;
    z-index: 10 !important;
    border: none !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1) !important;
    font-size: 1.3rem !important;
    font-family: inherit !important;
    box-sizing: border-box !important;
}

.category-item:hover .category-icon {
    transform: scale(1.05) rotate(3deg) !important;
    background: linear-gradient(135deg, #0e9bd5 0%, #0c1f38 100%) !important;
    border: none !important;
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.15) !important;
}

.category-icon i {
    font-size: 1.3rem;
    color: white !important;
    opacity: 1 !important;
    visibility: visible !important;
    transition: none !important;
    text-shadow: none !important;
    filter: none !important;
    -webkit-filter: none !important;
}

.category-item:hover .category-icon {
    transform: scale(1.05) rotate(3deg);
    background: linear-gradient(135deg, #0e9bd5 0%, #0c1f38 100%);
}

.category-item:hover .category-icon i {
    color: white !important;
    opacity: 1 !important;
    visibility: visible !important;
    text-shadow: none !important;
    filter: none !important;
    -webkit-filter: none !important;
    transform: none !important;
}

/* Extra specific selector to override any conflicts */
.categories-section .category-item:hover .category-icon i {
    color: white !important;
    opacity: 1 !important;
    visibility: visible !important;
    text-shadow: none !important;
    filter: none !important;
    -webkit-filter: none !important;
}

/* Maximum specificity to override all external CSS */
.categories-section .categories-grid .category-item .category-content .category-icon i {
    color: white !important;
    opacity: 1 !important;
    visibility: visible !important;
    text-shadow: none !important;
    filter: none !important;
    -webkit-filter: none !important;
    transform: none !important;
    transition: none !important;
}

.categories-section .categories-grid .category-item:hover .category-content .category-icon i {
    color: white !important;
    opacity: 1 !important;
    visibility: visible !important;
    text-shadow: none !important;
    filter: none !important;
    -webkit-filter: none !important;
    transform: none !important;
}

/* Override Bootstrap and any external CSS completely */
.categories-section .category-icon i,
.categories-section .category-item:hover .category-icon i,
.categories-section .categories-grid .category-item .category-content .category-icon i,
.categories-section .categories-grid .category-item:hover .category-content .category-icon i {
    color: #ffffff !important;
    background: none !important;
    border: none !important;
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900 !important;
    font-style: normal !important;
    font-variant: normal !important;
    text-rendering: auto !important;
    line-height: 1 !important;
    -webkit-font-smoothing: antialiased !important;
    -moz-osx-font-smoothing: grayscale !important;
    text-shadow: none !important;
    filter: none !important;
    -webkit-filter: none !important;
    opacity: 1 !important;
    visibility: visible !important;
    transform: none !important;
    transition: none !important;
    animation: none !important;
}

.category-info h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a365d;
    margin-bottom: 0.3rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.category-description {
    font-size: 0.85rem;
    color: #64748b;
    line-height: 1.4;
    margin: 0;
}

.category-hover-effect {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(14, 155, 213, 0.03) 0%, rgba(12, 31, 56, 0.03) 100%);
    opacity: 0;
    transition: opacity 0.3s ease;
    border-radius: 15px;
    z-index: 1;
    pointer-events: none;
}

.category-item:hover .category-hover-effect {
    opacity: 1;
}

@media (max-width: 768px) {
    .categories-section {
        padding: 0.3rem 0 0.8rem 0;
        margin: 0.3rem 0;
    }
    
    .section-header {
        margin-bottom: 0.8rem;
        margin-top: 0.3rem;
    }
    
    .categories-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 0 1rem;
    }
    
    .category-item {
        padding: 1rem;
    }
    
    .category-content {
        gap: 0.8rem;
    }
    
    .category-icon {
        width: 45px;
        height: 45px;
    }
    
    .category-icon i {
        font-size: 1.2rem;
    }
    
    .news-section-title {
        font-size: 1.5rem;
    }
}
</style>
