@extends('layouts.app')

@section('title', __('contactservice.page_title'))

@section('content')
<div class="contact-us-hero">
    <div class="container">
        <h1 class="contact-us-hero-title" id="page-title">{{ strtoupper(__('contactservice.mawasiliano')) }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item active" id="breadcrumb-title" aria-current="page">{{ __('contactservice.contact_breadcrumb') }}</li>
            </ol>
        </nav>
    </div>
</div>
   

    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <h2 class="contact-section-title">{{ __('contactservice.regional_offices') }}</h2>

        @php
            $headquarters = null;
            $otherRegions = [];
            
            foreach($contacts as $region => $contact) {
                if (strtolower($region) === 'dodoma') {
                    $headquarters = [$region, $contact];
                } else {
                    $otherRegions[$region] = $contact;
                }
            }
        @endphp

        <!-- All Regional Offices with uniform styling -->
        <div class="contact-office-rows">
            @if($headquarters)
                <!-- Main Office - Headquarters with star -->
                <div class="contact-office-row">
                    <div class="contact-office-name-section">
                        <div class="region-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>{{ strtoupper($headquarters[0]) }}</h3>
                         <div class="contact-main-office-badge">
                            <div class="contact-main-office-star">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="contact-main-office-text">{{ __('contactservice.headquarters') }}</div>
                        </div>
                    </div>
                    
                    <div class="contact-office-details-section">
                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.location') }}:</strong>
                                {{ $headquarters[1]['address'] }}
                            </div>
                        </div>

                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.postal_address') }}:</strong>
                                {{ $headquarters[1]['postal_address'] ?? __('contactservice.no_information') }}
                            </div>
                        </div>

                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-at"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.email') }}:</strong>
                                <a href="mailto:{{ $headquarters[1]['email'] }}">{{ $headquarters[1]['email'] }}</a>
                            </div>
                        </div>

                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.phone') }}:</strong>
                                {{ $headquarters[1]['phone1'] }}
                                @if(!empty($headquarters[1]['phone2']))
                                    , {{ $headquarters[1]['phone2'] }}
                                @endif
                            </div>
                        </div>

                        @if(!empty($headquarters[1]['website']))
                            <div class="contact-detail-item">
                                <div class="contact-detail-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="contact-detail-content">
                                    <strong>{{ __('contactservice.website') }}:</strong>
                                    <a href="{{ $headquarters[1]['website'] }}" target="_blank">{{ $headquarters[1]['website'] }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="contact-office-map-section" id="map-{{ Str::slug($headquarters[0]) }}"></div>
                </div>
            @endif

            @foreach($otherRegions as $region => $contact)
                <div class="contact-office-row">
                    <div class="contact-office-name-section">
                        <div class="region-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3>{{ strtoupper($region) }}</h3>
                    </div>
                    
                    <div class="contact-office-details-section">
                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.location') }}:</strong>
                                {{ $contact['address'] }}
                            </div>
                        </div>

                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.postal_address') }}:</strong>
                                {{ $contact['postal_address'] ?? __('contactservice.no_information') }}
                            </div>
                        </div>

                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-at"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.email') }}:</strong>
                                <a href="mailto:{{ $contact['email'] }}">{{ $contact['email'] }}</a>
                            </div>
                        </div>

                        <div class="contact-detail-item">
                            <div class="contact-detail-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div class="contact-detail-content">
                                <strong>{{ __('contactservice.phone') }}:</strong>
                                {{ $contact['phone1'] }}
                                @if(!empty($contact['phone2']))
                                    , {{ $contact['phone2'] }}
                                @endif
                            </div>
                        </div>

                        @if(!empty($contact['website']))
                            <div class="contact-detail-item">
                                <div class="contact-detail-icon">
                                    <i class="fas fa-globe"></i>
                                </div>
                                <div class="contact-detail-content">
                                    <strong>{{ __('contactservice.website') }}:</strong>
                                    @php
                                        $websiteUrl = $contact['website'];
                                        // Add https:// if the URL doesn't start with http:// or https://
                                            if (preg_match('/www\..*/i', $websiteUrl, $matches)) {
    $websiteUrl = $matches[0]; // Keep only from "www." onwards
    $websiteUrl = 'https://' . $websiteUrl; // Add protocol
}

                                    @endphp
                                    <a href="{{ $websiteUrl }}" target="_blank">{{ $contact['website'] }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <div class="contact-office-map-section" id="map-{{ Str::slug($region) }}"></div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Floating Action Button -->
    <style>
        .contact-fab {
            position: fixed;
            bottom: 30px;
            left: 30px;
            right: auto;
            z-index: 1000;
        }

        @media (max-width: 768px) {
            .contact-fab {
                bottom: 20px;
                left: 20px;
            }
        }

        @media (max-width: 480px) {
            .contact-fab {
                bottom: 15px;
                left: 15px;
            }
        }
    </style>
  <a id="contactFab" href="{{ route('contactus.getusintouch') }}" class="contact-fab">
    <i class="fas fa-envelope"></i>
</a>



    <!-- Contact Modal -->

    <!-- Scripts -->
    <div id="contact-data" data-contacts="{{ json_encode($contacts) }}" style="display: none;"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    // Enhanced FAB animations
    const fab = document.getElementById('contactFab');
    
    // Add floating animation
    function floatAnimation() {
        fab.style.animation = 'float 3s ease-in-out infinite';
    }
    
    // CSS for float animation
    const floatKeyframes = `
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
    `;
    
    const style = document.createElement('style');
    style.textContent = floatKeyframes;
    document.head.appendChild(style);
    
    // Start floating animation
    setTimeout(floatAnimation, 1000);
    
    // Add click ripple effect
    fab.addEventListener('click', function(e) {
        const ripple = document.createElement('span');
        const rect = this.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = e.clientX - rect.left - size / 2;
        const y = e.clientY - rect.top - size / 2;
        
        ripple.style.cssText = `
            position: absolute;
            left: ${x}px;
            top: ${y}px;
            width: ${size}px;
            height: ${size}px;
            background: rgba(255,255,255,0.5);
            border-radius: 50%;
            transform: scale(0);
            animation: ripple 0.6s ease-out;
            pointer-events: none;
        `;
        
        this.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    });
    
    // Add ripple animation CSS
    const rippleKeyframes = `
        @keyframes ripple {
            to {
                transform: scale(2);
                opacity: 0;
            }
        }
    `;
    
    const rippleStyle = document.createElement('style');
    rippleStyle.textContent = rippleKeyframes;
    document.head.appendChild(rippleStyle);
});

        document.addEventListener('DOMContentLoaded', function () {
            // Initialize maps
            const contactLocations = JSON.parse(document.getElementById('contact-data').dataset.contacts);

            Object.entries(contactLocations).forEach(([region, data]) => {
                const mapId = 'map-' + region.toLowerCase().replace(/\s+/g, '-');
                const mapContainer = document.getElementById(mapId);
                
                if (mapContainer && data.latitude && data.longitude) {
                    const map = L.map(mapId, {
                        zoomControl: false,
                        scrollWheelZoom: false,
                        doubleClickZoom: false,
                        boxZoom: false,
                        keyboard: false,
                        dragging: false
                    }).setView([data.latitude, data.longitude], 17);

                    L.tileLayer('{{ config("links.maps.openstreetmap_tiles") }}', {
                        attribution: '&copy; <a href="{{ config("links.maps.openstreetmap_attribution") }}">OpenStreetMap</a> contributors'
                    }).addTo(map);

                    // Custom marker icon
                    const customIcon = L.divIcon({
                        html: '<i class="fas fa-map-marker-alt" style="color: #dc3545; font-size: 24px;"></i>',
                        iconSize: [30, 30],
                        className: 'contact-custom-div-icon'
                    });

                    L.marker([data.latitude, data.longitude], { icon: customIcon })
                        .addTo(map)
                        .bindPopup(`
                            <div style="text-align: center; padding: 5px;">
                                <strong style="color: #007bff;">${region.toUpperCase()}</strong><br>
                                <small style="color: #666;">${data.address}</small>
                            </div>
                        `);

                    // Force map to resize and fit container
                    setTimeout(() => {
                        map.invalidateSize();
                    }, 100);
                }
            });

            // FAB animation on scroll
            let lastScrollTop = 0;
           // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    if (alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });
    </script>
@endsection