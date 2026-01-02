<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<title>HESLB | Higher Education Students' Loans Board</title>

    <!-- Primary Meta Tags -->
    <meta name="title" content="HESLB | Higher Education Students' Loans Board">
    <meta name="description" content="Higher Education Students' Loans Board (HESLB) - Providing financial assistance to Tanzanian students pursuing higher education. Apply for loans, manage repayments, and access educational resources.">
    <meta name="keywords" content="HESLB, Higher Education Students Loans Board, student loans, Tanzania, education loans, loan application, loan repayment, scholarships, higher education, Tanzania education">
    <meta name="author" content="Higher Education Students' Loans Board">
    <meta name="robots" content="index, follow">
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    <meta name="theme-color" content="#0066cc">
    <meta name="msapplication-TileColor" content="#0066cc">
    <meta name="application-name" content="HESLB">



<!-- Favicon -->
<link rel="icon" href="{{ asset('/images/static_files/heslblogos.png') }}" type="image/png">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="{{ config('links.cdn.bootstrap_icons') }}">

<!-- Bootstrap 5 CSS -->
<link href="{{ config('links.cdn.bootstrap_css') }}" rel="stylesheet">

<!-- Font Awesome 6 (Primary CDN) -->
<link rel="stylesheet" href="{{ config('links.cdn.fontawesome_css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">

<!-- Leaflet CSS (only on contact us pages) -->
@if(in_array(Route::currentRouteName(), ['contactus.formandregion', 'contactus.getusintouch', 'contact.store']))
<link rel="stylesheet" href="{{ config('links.cdn.leaflet_css') }}" />
@endif

<!-- Bootstrap JavaScript -->
<script src="{{ config('links.cdn.bootstrap_js') }}"></script>

<!-- Leaflet JavaScript (only on contact us pages) -->
@if(in_array(Route::currentRouteName(), ['contactus.formandregion', 'contactus.getusintouch', 'contact.store']))
<script src="{{ config('links.cdn.leaflet_js') }}"></script>
@endif

 
<meta name="csrf-token" content="{{ csrf_token() }}">


  <!-- CSS Files - Always Loaded -->
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('css/footer.css') }}">
<link rel="stylesheet" href="{{ asset('css/footers.css') }}">

@php
    $currentRoute = Route::currentRouteName();
    $routePrefix = explode('.', $currentRoute)[0] ?? '';
@endphp

<!-- Conditional CSS Files - Loaded based on current page -->
@if(in_array($currentRoute, ['loanapplication.faqs', 'loanrepayment.faqs']))
<link rel="stylesheet" href="{{ asset('css/faq.css') }}">
@endif

@if(in_array($currentRoute, ['contactus.formandregion', 'contactus.getusintouch', 'contact.store']))
<link rel="stylesheet" href="{{ asset('css/contactus.css') }}">
@endif

@if(str_starts_with($currentRoute, 'newscenter.'))
<link rel="stylesheet" href="{{ asset('css/newscenter.css') }}">
@endif

@if($currentRoute === 'newscenter.searching' || $currentRoute === 'story.search')
<link rel="stylesheet" href="{{ asset('css/searching.css') }}">
@endif

@if(str_starts_with($currentRoute, 'aboutus.'))
<link rel="stylesheet" href="{{ asset('css/organization.css') }}">
@endif

@if(str_starts_with($currentRoute, 'publications.'))
<link rel="stylesheet" href="{{ asset('css/publication.css') }}">
@endif

@if(str_starts_with($currentRoute, 'story.'))
<link rel="stylesheet" href="{{ asset('css/tellusstories.css') }}">
<link rel="stylesheet" href="{{ asset('css/showstories.css') }}">
@endif

@if($currentRoute === 'story.showspecific')
<link rel="stylesheet" href="{{ asset('css/storycontent.css') }}">
@endif

@if($currentRoute === 'loanapplication.applicationlink')
<link rel="stylesheet" href="{{ asset('css/applicationlink.css') }}">
@endif

@if($currentRoute === 'home')
<link rel="stylesheet" href="{{ asset('css/ourproduct.css') }}">
<link rel="stylesheet" href="{{ asset('css/ourproductheader.css') }}">
<link rel="stylesheet" href="{{ asset('css/countdowntime.css') }}">
@endif

<!-- JS Files - Always Loaded -->
<script src="{{ asset('js/app.js') }}" defer></script>

<!-- Conditional JS Files - Loaded based on current page -->
@if(str_starts_with($currentRoute, 'aboutus.'))
<script src="{{ asset('js/organization.js') }}" defer></script>
@endif

@if(in_array($currentRoute, ['contactus.formandregion', 'contactus.getusintouch', 'contact.store']))
<script src="{{ asset('js/contactus.js') }}" defer></script>
@endif

@if(str_starts_with($currentRoute, 'newscenter.'))
<script src="{{ asset('js/newscenter.js') }}" defer></script>
@endif

@if($currentRoute === 'home')
<script src="{{ asset('js/countdowntime.js') }}" defer></script>
<script src="{{ asset('js/ourproduct.js') }}" defer></script>
@endif
<!-- <script src="https://chatbot.heslb.go.tz/chatbot_general_obs.js"></script> -->


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
