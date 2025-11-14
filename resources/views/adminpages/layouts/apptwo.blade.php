{{-- resources/views/layouts/header.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Admin Pages</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap -->
    <link href="{{ config('links.cdn.bootstrap_css') }}" rel="stylesheet">
    <script src="{{ config('links.cdn.bootstrap_js') }}"></script>

    <!-- Boxicons (optional) -->
    <link href="{{ config('links.cdn.boxicons') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('/images/static_files/heslblogos.png') }}" type="image/png">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('css/adminlogin.css') }}">

    <link href="{{ config('links.cdn.fontawesome_css') }}" rel="stylesheet" crossorigin="anonymous" referrerpolicy="no-referrer">

     
    <script>
        // Prevent back button from showing cached pages
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>

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
        
        /* Mobile responsive adjustments */
        @media (max-width: 767.98px) {
            .header-top {
                padding: 0.75rem 1rem !important;
            }
            .header-links {
                gap: 1rem !important;
                font-size: 0.875rem;
            }
            .header-social {
                gap: 0.5rem !important;
            }
            .icon-box {
                width: 32px;
                height: 32px;
            }
            .icon-box i {
                font-size: 14px;
            }
        }
        
        @media (max-width: 575.98px) {
            .header-top {
                padding: 0.5rem 0.75rem !important;
            }
            .header-links {
                gap: 0.75rem !important;
                font-size: 0.8rem;
            }
            .header-social {
                gap: 0.375rem !important;
            }
            .icon-box {
                width: 28px;
                height: 28px;
            }
            .icon-box i {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>

<!-- Header now visible on all screen sizes -->
<div class="header-top px-4 py-2" style="background-color: #0c1f38; color: white;">
    <div class="d-flex justify-content-between align-items-center">
        
        <!-- Left Side Links -->
        <div class="header-links d-flex align-items-center" style="gap: 1.5rem;">
            <a href="{{ config('links.heslb_systems.staff_mail') }}" class="text-light text-decoration-none">{{ __('menu.staffmail') }}</a>
            <a href="{{ url('lang/en') }}" class="text-light text-decoration-none">ENG</a>
            <a href="{{ url('lang/sw') }}" class="text-light text-decoration-none">SWL</a>
        </div>

        <!-- Right Side Social Icons -->
        <div class="header-social d-flex align-items-center" style="gap: 0.75rem;">
            <a href="{{ route('home') }}" class="icon-box" title="Home">
                <i class="fas fa-home"></i>
            </a>
            <a href="{{ config('links.social_media.twitter') }}" target="_blank" class="icon-box" title="X">
                <i class="fab fa-x-twitter"></i>
            </a>
            <a href="{{ config('links.social_media.facebook') }}" target="_blank" class="icon-box" title="Facebook">
                <i class="fab fa-facebook-f"></i>
            </a>
            <a href="{{ config('links.social_media.instagram') }}" target="_blank" class="icon-box" title="Instagram">
                <i class="fab fa-instagram"></i>
            </a>
            <a href="{{ config('links.social_media.youtube') }}" target="_blank" class="icon-box" title="YouTube">
                <i class="fab fa-youtube"></i>
            </a>
        </div>
        
    </div>
</div>

{{-- Page Content --}}
<div class="main-content">
    @yield('content')
</div>

</body>
</html>