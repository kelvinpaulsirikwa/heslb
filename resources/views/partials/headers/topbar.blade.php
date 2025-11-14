<div class="d-none d-lg-block px-4 py-2" style="background-color: #0c1f38; color: white;">
    <div class="d-flex justify-content-between align-items-center">
         <div class="d-flex align-items-center" style="gap: 1.5rem;">
            <a href="{{ config('links.heslb_systems.staff_mail') }}" class="text-light text-decoration-none">{{ __('menu.staffmail') }}</a>
            <a href="{{ url('lang/en') }}" class="text-light text-decoration-none">ENG</a>
            <a href="{{ url('lang/sw') }}" class="text-light text-decoration-none">SWL</a>
        </div>


        <div class="d-flex align-items-center" style="gap: 0.75rem;">
          
            <a href="{{ config('links.social_media.twitter') }}" target="_blank" class="icon-box">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                </svg>
            </a>
            <a href="{{ config('links.social_media.facebook') }}" target="_blank" class="icon-box">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="{{ config('links.social_media.instagram') }}" target="_blank" class="icon-box">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="{{ config('links.social_media.youtube') }}" target="_blank" class="icon-box">
                <i class="fab fa-youtube"></i>
            </a>
            
        </div>       
    </div>
</div>

<style>
    .icon-box {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 1px solid #ffffff40;
        border-radius: 8px;
        color: white;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .icon-box:hover {
        background-color: #ffffff20;
    }

    .icon-box i {
        font-size: 16px;
    }
</style>

