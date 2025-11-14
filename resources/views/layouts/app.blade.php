<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<title>HESLB | Higher Education Students' Loans Board</title>



<!-- Favicon -->
<link rel="icon" href="{{ asset('/images/static_files/heslblogos.png') }}" type="image/png">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="{{ config('links.cdn.bootstrap_icons') }}">

<!-- Bootstrap 5 CSS -->
<link href="{{ config('links.cdn.bootstrap_css') }}" rel="stylesheet">

<!-- Font Awesome 6 (Primary CDN) -->
<link rel="stylesheet" href="{{ config('links.cdn.fontawesome_css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- Font Awesome 6 (Fallback CDN) -->
<link rel="stylesheet" href="{{ config('links.cdn.fontawesome_v6_css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- Leaflet CSS -->
<link rel="stylesheet" href="{{ config('links.cdn.leaflet_css') }}" />

<!-- Leaflet JavaScript -->
<script src="{{ config('links.cdn.leaflet_js') }}"></script>

<!-- Bootstrap JavaScript -->
<script src="{{ config('links.cdn.bootstrap_js') }}"></script>

 
<meta name="csrf-token" content="{{ csrf_token() }}">


  <!-- CSS Files -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/footers.css') }}">
<link rel="stylesheet" href="{{ asset('css/faq.css') }}">
<link rel="stylesheet" href="{{ asset('css/contactus.css') }}">
<link rel="stylesheet" href="{{ asset('css/newscenter.css') }}">
<link rel="stylesheet" href="{{ asset('css/organization.css') }}">
<link rel="stylesheet" href="{{ asset('css/countdowntime.css') }}">
<link rel="stylesheet" href="{{ asset('css/publication.css') }}">
<link rel="stylesheet" href="{{ asset('css/ourproduct.css') }}">
<link rel="stylesheet" href="{{ asset('css/tellusstories.css') }}">
<link rel="stylesheet" href="{{ asset('css/ourservice.css') }}">
<link rel="stylesheet" href="{{ asset('css/ourproductheader.css') }}">
<link rel="stylesheet" href="{{ asset('css/applicationlink.css') }}">
<link rel="stylesheet" href="{{ asset('css/showstories.css') }}">
<link rel="stylesheet" href="{{ asset('css/storycontent.css') }}">
<link rel="stylesheet" href="{{ asset('css/searching.css') }}">

<!-- JS Files -->
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/organization.js') }}"></script>
<script src="{{ asset('js/contactus.js') }}"></script>
<script src="{{ asset('js/newscenter.js') }}"></script>
<script src="{{ asset('js/countdowntime.js') }}"></script>
<script src="{{ asset('js/ourproduct.js') }}"></script>
<-- <script src="https://chatbot.heslb.go.tz/chatbot_general_obs.js"></script> -->

    <style>
        /* Global Font Family for Website (excluding admin pages) */
        body {
            font-family: Tahoma, Geneva, sans-serif !important;
            
            background: none !important;
            background-color: white !important; 
        }

        /* Apply font family to text elements only, NOT to icons */
        h1, h2, h3, h4, h5, h6, p, span, div, a, button, input, textarea, select, label {
            font-family: Tahoma, Geneva, sans-serif !important;
        }

        /* IMPORTANT: Preserve icon font families */
        .fas, .far, .fab, .fa, i[class*="fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 5 Free", "Font Awesome 5 Pro", "FontAwesome" !important;
        }
        

        .bi, i[class*="bi-"] {
            font-family: "Bootstrap Icons" !important;
        }

        /* Ensure category icons are visible */
        .category-icon i,
        .category-icon .fas,
        .category-icon .far,
        .category-icon .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "Font Awesome 5 Free", "Font Awesome 5 Pro", "FontAwesome" !important;
            display: inline-block !important;
            visibility: visible !important;
        }

        /* Debug: Make sure icons are visible */
        .category-icon {
            position: relative;
        }
        
        .category-icon i {
            position: relative;
            z-index: 10;
        }
    </style>



	<!-- Favicone Icon -->

</head>
<body style="min-height:100vh; display:flex; flex-direction:column;">


    <!-- Header -->
     @include('partials.headers.topbar')
    @include('partials.headers.organization')

    <!-- Main Content -->
    <main style="flex:1 0 auto;">
        @yield('content')
            @yield('scripts')

    </main>

    <!-- Footer -->
    @include('partials.footer')

    <!-- Floating Action Buttons (exclude from contact us pages) -->
    @unless(in_array(Route::currentRouteName(), ['contactus.formandregion', 'contactus.getusintouch', 'contact.store']))
        @include('components.floating-buttons')
    @endunless

</body>
</html>
