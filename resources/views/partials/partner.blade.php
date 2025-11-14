<div id="partner-section" class="py-5" style="background-color: #f9f9f9;">
    <div class="container text-center">
        <h2 style="font-weight: 600; font-size: 2rem; color: #333;">{{ __('partners.title') }}</h2>
        <p style="color: #888;">{{ __('partners.subtitle') }}</p>
        
        <div class="row justify-content-center mt-4">
            @foreach ($strategicPartners as $partner)
                <div class="col-4 col-md-2 mb-3">
                    <a href="{{ $partner['url'] }}" target="_blank" style="text-decoration: none;">
                        <div class="partner-card" style="background: rgba(255, 255, 255, 0.7); padding: 5px 2px; border-radius: 5px; box-shadow: 0 0 6px rgba(0,0,0,0.05); transition: all 0.3s ease; position: relative; overflow: hidden;">
                            @if($partner['logo'])
                                <img src="{{ $partner['logo'] }}" 
                                     alt="{{ $partner['acronym_name'] ?? $partner['name'] }}" 
                                     style="max-width: 100%; height: 60px; object-fit: contain; margin-bottom: 5px; transition: all 0.3s ease;">
                            @else
                                <img src="{{ asset('images/static_files/no-logo.png') }}" 
                                     alt="No logo" 
                                     style="max-width: 100%; height: 60px; object-fit: contain; margin-bottom: 5px; transition: all 0.3s ease;">
                            @endif
                            <p class="partner-acronym" style="color: #444; font-size: 12px; margin: 0; font-weight: 600; transition: all 0.3s ease;">
                                {{ $partner['acronym_name'] }}
                            </p>
                            <p class="partner-fullname" style="color: #444; font-size: 10px; margin: 0; font-weight: 500; opacity: 0; position: absolute; bottom: 2px; left: 2px; right: 2px; text-align: center; transition: all 0.3s ease; background: rgba(255, 255, 255, 0.9); padding: 2px; border-radius: 3px;">
                                {{ $partner['name'] }}
                            </p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    .partner-card:hover {
        background: rgba(255, 255, 255, 0.95) !important;
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    
    .partner-card:hover .partner-acronym {
        opacity: 0;
    }
    
    .partner-card:hover .partner-fullname {
        opacity: 1 !important;
    }
    
    .partner-card:hover img {
        transform: scale(1.05);
    }
</style>