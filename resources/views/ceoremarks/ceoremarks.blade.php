@php
    // Using Laravel's localization
    $name = __('managewords.name');
    $rank = __('managewords.rank'); 
    $imageUrl = asset('images/static_files/Mkurugenzi_Bill.jpg');
    
    // Get preview and full text from language files
    $welcomePreview = __('managewords.preview_text');
    $welcomeFullText = __('managewords.full_text');
@endphp

<style>
    .ceo-welcome-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 20px;
        font-family: 'Segoe UI', Arial, sans-serif;
        background-color: white;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .ceo-welcome-header {
        color: #0c1f38;
        font-size: 30px;
        margin-bottom: 25px;
        font-weight: 700;
        border-bottom: 3px solid #0096db;
        padding-bottom: 10px;
    }

    .ceo-welcome-flex {
        display: flex;
        flex-wrap: wrap;
        gap: 40px;
        align-items: flex-start;
    }

    .ceo-image-box {
        flex: 0 0 320px;
        max-width: 100%;
        text-align: center;
    }

    .ceo-image-frame {
        padding: 24px;
        background: #f9f9f9;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .ceo-image-inner {
        padding: 10px;
        background: white;
        border: 12px solid #dcdcdc;
        border-radius: 6px;
    }

    .ceo-image-inner img {
        width: 100%;
        height: auto;
        object-fit: cover;
        border-radius: 4px;
    }

    .ceo-person-name {
        margin-top: 15px;
        font-size: 20px;
        font-weight: 700;
        color: #0c1f38;
    }

    .ceo-person-rank {
        font-size: 15px;
        color: #666;
    }

    .ceo-welcome-text {
        flex: 1;
        min-width: 280px;
    }

    .ceo-welcome-text p {
        font-size: 17px;
        line-height: 1.8;
        margin-bottom: 20px;
        color: #333;
    }

    .ceo-read-more, .ceo-read-less {
        color: #0096db;
        font-weight: 500;
        text-decoration: none;
        cursor: pointer;
    }

    .ceo-read-more:hover, .ceo-read-less:hover {
        text-decoration: underline;
    }

    .ceo-hidden {
        display: none;
    }

    /* Two-column divider styles */
    .ceo-content-divider {
        max-width: 1200px;
        margin: 40px auto 0;
        padding: 0 20px;
        font-family: 'Segoe UI', Arial, sans-serif;
    }

    .ceo-divider-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        align-items: start;
        min-height: 400px; /* Minimum height for intrinsic sizing */
    }

    .ceo-divider-column {
        background-color: white;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        padding: 0;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .ceo-column-left {
        border-left: 4px solid #0096db;
    }

    .ceo-column-right {
        border-left: 4px solid #0c1f38;
    }

    .ceo-column-content {
        padding: 30px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .ceo-image-box {
            flex: 1 1 100%;
        }

        .ceo-welcome-flex {
            flex-direction: column;
        }

        .ceo-image-frame {
            margin: 0 auto;
        }

        .ceo-divider-container {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .ceo-content-divider {
            margin-top: 30px;
        }
    }

    @media (max-width: 480px) {
        .ceo-welcome-container {
            padding: 20px 15px;
        }

        .ceo-content-divider {
            padding: 0 15px;
        }

        .ceo-column-content {
            padding: 20px;
        }

        .ceo-welcome-header {
            font-size: 24px;
        }
    }
</style>

<!-- CEO Welcome Message Section -->
<div class="ceo-welcome-container">
    <h2 class="ceo-welcome-header">{{ __('managewords.title') }}</h2>
    <div class="ceo-welcome-flex">
        <!-- Image Section -->
        <div class="ceo-image-box">
            <div class="ceo-image-frame">
                <div class="ceo-image-inner">
                    <img src="{{ $imageUrl }}" alt="{{ $name }}"
                        onerror="this.style.display='none'; this.parentNode.style.background='linear-gradient(135deg, #0c1f38, #1a3a6a)';">
                </div>
            </div>
            <div class="ceo-person-name">{{ $name }}</div>
            <div class="ceo-person-rank">{{ $rank }}</div>
        </div>

        <!-- Welcome Text Section -->
        <div class="ceo-welcome-text">
            <!-- Preview Text -->
            <div id="ceo-preview-content">
                @foreach($welcomePreview as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
                <p>
                    <a href="#" class="ceo-read-more" onclick="ceoToggleContent(event)">{{ __('managewords.read_more') }}</a>
                </p>
            </div>

            <!-- Full Text (Hidden by default) -->
            <div id="ceo-full-content" class="ceo-hidden">
                @foreach($welcomeFullText as $paragraph)
                    <p>{{ $paragraph }}</p>
                @endforeach
                <p>
                    <a href="#" class="ceo-read-less" onclick="ceoToggleContent(event)">{{ __('managewords.read_less') }}</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Two-Column Content Divider -->
<div class="ceo-content-divider">
    <div class="ceo-divider-container">
        <!-- Left Column - News and Events -->
        <div class="ceo-divider-column ceo-column-left">
            <div class="ceo-column-content">
                @include('ceoremarks.newsandevent')
            </div>
        </div>

        <!-- Right Column - Shortcut Links -->
        <div class="ceo-divider-column ceo-column-right">
            <div class="ceo-column-content">
                @include('ceoremarks.shortcutlinks')
            </div>
        </div>
    </div>
</div>

<script>
    function ceoToggleContent(event) {
        event.preventDefault();
        
        const previewContent = document.getElementById('ceo-preview-content');
        const fullContent = document.getElementById('ceo-full-content');
        
        if (previewContent.classList.contains('ceo-hidden')) {
            // Show preview, hide full
            previewContent.classList.remove('ceo-hidden');
            fullContent.classList.add('ceo-hidden');
        } else {
            // Show full, hide preview
            previewContent.classList.add('ceo-hidden');
            fullContent.classList.remove('ceo-hidden');
        }
    }
</script>