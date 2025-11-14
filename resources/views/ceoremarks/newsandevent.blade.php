<div class="ceo-news-wrapper-box">
    <div class="ceo-news-section-head">
        <h1>{{ __('newsevents.news_events') }}</h1>
    </div>

    <div class="ceo-news-events-feed">
        @forelse($latestNews as $index => $news)
            <div class="ceo-news-event-block">
                
                <div class="ceo-news-event-badge">
                    <img src="{{ asset('images/static_files/new.svg') }}" alt="NEW" class="new-badge-svg">
                </div>
                <div class="ceo-news-event-body">
                    <a href="{{ route('newscenter.singlenews', $news->id) }}" class="ceo-news-event-title">
                        {{ $news->title }}
                    </a>
                    <div class="ceo-news-event-meta">
                        <svg class="ceo-news-icon-calendar" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                        </svg>
                        {{ $news->created_at->format('F d, Y') }}

                        {{-- Show "NEW" badge only for the latest item --}}
                        @if($loop->first)
                        <span></span>
                        <img src="{{ asset('images/static_files/new.gif') }}" 
                                     alt="NEW" 
                                     style="width: 36px; height: 36px; margin-right: 4px;"
                                     onerror="this.style.display='none'">
                           
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p>{{ __('newsevents.no_news_available') }}</p>
        @endforelse
    </div>
</div>

<style>
    .ceo-news-wrapper-box {
        width: 100%; /* full width */
        margin: 0 auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .ceo-news-section-head {
        margin-bottom: 30px;
    }

    .ceo-news-section-head h1 {
        color: #0e9bd5 ;
        font-size: 32px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .ceo-news-section-head::after {
        content: '';
        display: block;
        width: 100%;
        height: 4px;
        background: #0e9bd5;
        margin-top: 10px;
    }

    .ceo-news-events-feed {
        width: 100%;
    }

    .ceo-news-event-block {
        display: flex;
        align-items: flex-start;
        margin-bottom: 25px;
        padding-bottom: 25px;
        border-bottom: 1px solid #e9ecef;
        width: 100%;
        gap: 15px;
    }

    .ceo-news-event-block:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .ceo-news-event-badge {
        flex-shrink: 0;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        margin-top: 5px;
    }

    .new-badge-svg {
        width: 70px;
        height: 50px;
        object-fit: contain;
    }

    .ceo-news-event-body {
        flex: 1;
        width: 100%;
    }

    .ceo-news-event-title {
        color: #2c3e50;
        font-size: 18px;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 8px;
        text-decoration: none;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .ceo-news-event-title:hover {
        color: #3498db;
    }

    .ceo-news-event-meta {
        display: flex;
        align-items: center;
        color: #95a5a6;
        font-size: 14px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .ceo-news-event-meta .ceo-news-icon-calendar {
        width: 16px;
        height: 16px;
        margin-right: 8px;
        opacity: 0.7;
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .ceo-news-wrapper-box {
            padding: 20px 10px; /* Reduced from 20px 15px */
        }
        
        .ceo-news-event-block {
            display: flex;
            align-items: flex-start;
            padding: 15px 10px;
            margin-left: 5px;
            margin-right: 5px;
            gap: 12px;
        }
        
        .ceo-news-event-badge {
            margin-bottom: 0;
            flex-shrink: 0;
            margin-top: 3px;
        }

        .new-badge-svg {
            width: 50px;
            height: 38px;
        }
        
        .ceo-news-event-body {
            text-align: left;
            width: 100%;
            flex: 1;
        }
        
        .ceo-news-event-title {
            text-align: left;
            display: block;
            margin-bottom: 8px;
            line-height: 1.4;
            word-break: break-word;
        }
        
        .ceo-news-event-meta {
            justify-content: flex-start;
            flex-wrap: nowrap;
            gap: 8px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .ceo-news-section-head h1 {
            text-align: center;
        }
    }
    
    @media (max-width: 480px) {
        .ceo-news-wrapper-box {
            padding: 15px 8px; /* Reduced from 15px 10px */
        }
        
        .ceo-news-event-block {
            padding: 15px 8px; /* Reduced from 15px 10px */
            margin-left: 3px; /* Further reduced */
            margin-right: 3px; /* Further reduced */
        }
        
        .ceo-news-event-title {
            font-size: 16px;
        }
        
        .ceo-news-section-head h1 {
            font-size: 24px;
        }

        .new-badge-svg {
            width: 45px;
            height: 35px;
        }
    }
</style>
