<style>
    .ceo-shortcut-section-head {
        margin-bottom: 20px;
    }

    .ceo-shortcut-section-head h1 {
        color: #2c3e50;
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .ceo-shortcut-section-head::after {
        content: '';
        display: block;
        width: 100%;
        height: 4px;
        background: #3498db;
        margin-top: 10px;
    }

    /* Single prominent link design with left icon */
    .ceo-shortcut-link {
        display: flex;
        align-items: center;
        width: 100%;
        max-width: 100%;
        padding: 20px;
        background: #f8f9fa;
        color: #333;
        text-decoration: none;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.2s ease;
        margin-bottom: 20px;
        box-sizing: border-box;
        min-height: 80px;
    }

    .ceo-shortcut-link:hover {
        background: #f0f8ff;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    /* Circular icon design on the left */
    .ceo-shortcut-link-icon {
        width: 40px;
        height: 40px;
        background: #3498db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
        flex-shrink: 0;
    }

    .ceo-shortcut-link-icon::before {
        content: '›';
        color: white;
        font-size: 20px;
        font-weight: bold;
        transform: translateX(1px);
    }

    /* Link name with proper spacing */
    .ceo-shortcut-link-name {
        font-size: 18px;
        font-weight: 600;
        line-height: 1.4;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        margin-bottom: 0;
        color: #2c3e50;
        flex-grow: 1;
    }

    /* All links button - positioned at bottom with proper spacing */
    .ceo-shortcut-all-links-container {
        display: flex;
        justify-content: flex-end;
        margin-top: 40px;
        padding-right: 0;
        position: relative;
    }

    .ceo-shortcut-all-links-arrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        background: transparent;
        color: #0e9bd5;
        text-decoration: none;
        font-weight: 600;
        font-size: 16px;
        transition: all 0.2s ease;
        border-radius: 6px;
    }

    .ceo-shortcut-all-links-arrow:hover {
        color: #0c87be;
        background: #f8f9fa;
    }

    .ceo-shortcut-arrow-icon {
        width: 25px;
        height: 25px;
        background: #3498db;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: transform 0.2s ease;
    }

    .ceo-shortcut-arrow-icon::before {
        content: '›';
        color: white;
        font-size: 14px;
        font-weight: bold;
        transform: translateX(1px);
    }

    .ceo-shortcut-all-links-arrow:hover .ceo-shortcut-arrow-icon {
        transform: translateX(3px);
    }

    /* Container for mobile */
    .ceo-shortcut-mobile-container {
        width: 100%;
        padding: 15px 0;
        margin: 0;
        box-sizing: border-box;
        min-height: 200px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Links container with flex grow to push view all to bottom */
    .ceo-shortcut-links-container {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
    }
</style>

<div class="ceo-shortcut-mobile-container">
    <div class="ceo-shortcut-section-head">
        <h1>{{ __('shortcutlinks.shortcut_links') }}</h1>
    </div>   

    <div class="ceo-shortcut-links-container">
        @forelse ($shortcutlinks as $link)
            <a href="{{ $link->is_file ? asset('images/storage/' . $link->link) : $link->link }}" target="_blank" class="ceo-shortcut-link">
                <div class="ceo-shortcut-link-icon"></div>
                <div class="ceo-shortcut-link-name">{{ $link->link_name }}</div>
            </a>
        @empty
            <p style="color: #666; padding: 20px; text-align: center;">{{ __('shortcutlinks.no_links_available') }}</p>
        @endforelse
    </div>

    @if(\App\Models\Link::count() > 0)
        <div class="ceo-shortcut-all-links-container">
            <a href="{{ route('shortcutlinks.all') }}" class="ceo-shortcut-all-links-arrow">
                {{ __('shortcutlinks.view_all_links') }}
                <div class="ceo-shortcut-arrow-icon"></div>
            </a>
        </div>
    @endif
</div>