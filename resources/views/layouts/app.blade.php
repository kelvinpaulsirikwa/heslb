<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HESLB | Higher Education Students' Loans Board</title>

    <!-- ================= PRIMARY META ================= -->
    <meta name="title" content="HESLB | Higher Education Students' Loans Board">
    <meta name="description" content="Higher Education Students' Loans Board (HESLB) - Providing financial assistance to Tanzanian students pursuing higher education.">
    <meta name="keywords" content="HESLB, student loans, Tanzania, higher education">
    <meta name="author" content="Higher Education Students' Loans Board">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#0066cc">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ================= FAVICON ================= -->
    <link rel="icon" href="{{ asset('/images/static_files/heslblogos.png') }}" type="image/png">

    @php
        $currentRoute = Route::currentRouteName();
    @endphp

    <!-- =====================================================
         🔥 CRITICAL CSS (INLINE – FOR LCP & FCP)
    ====================================================== -->
    <style>
        body {
            margin: 0;
            font-family: Tahoma, Geneva, sans-serif;
            background-color: #ffffff;
        }

        header, nav {
            width: 100%;
            background: #ffffff;
        }

        main {
            display: block;
            min-height: 60vh;
        }

        h1, h2, h3, h4, h5, h6, p, span, a, button {
            font-family: Tahoma, Geneva, sans-serif;
        }

        /* Preserve Icon Fonts */
        .fas, .far, .fab, .fa,
        i[class*="fa-"] {
            font-family: "Font Awesome 6 Free","Font Awesome 5 Free","FontAwesome" !important;
        }

        .bi, i[class*="bi-"] {
            font-family: "Bootstrap Icons" !important;
        }
    </style>

    <!-- =====================================================
         🚀 NON-CRITICAL CSS (ASYNC / NON-BLOCKING)
    ====================================================== -->

    <!-- Bootstrap Icons -->
    <link rel="preload" href="{{ config('links.cdn.bootstrap_icons') }}"
          as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ config('links.cdn.bootstrap_icons') }}"></noscript>

    <!-- Bootstrap CSS -->
    <link rel="preload" href="{{ config('links.cdn.bootstrap_css') }}"
          as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ config('links.cdn.bootstrap_css') }}"></noscript>

    <!-- Font Awesome -->
    <link rel="preload" href="{{ config('links.cdn.fontawesome_css') }}"
          as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ config('links.cdn.fontawesome_css') }}"></noscript>

    <!-- App Core CSS -->
    <link rel="preload" href="{{ asset('css/app.css') }}"
          as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/app.css') }}"></noscript>

    <link rel="preload" href="{{ asset('css/organization.css') }}"
          as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/organization.css') }}"></noscript>

    <link rel="preload" href="{{ asset('css/footers.css') }}"
          as="style" onload="this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('css/footers.css') }}"></noscript>

    <!-- =====================================================
         PAGE-SPECIFIC CSS
    ====================================================== -->
    @if($currentRoute === 'home')
        <link rel="preload" href="{{ asset('css/ourproductheader.css') }}" as="style" onload="this.rel='stylesheet'">
        <link rel="preload" href="{{ asset('css/countdowntime.css') }}" as="style" onload="this.rel='stylesheet'">
        <link rel="preload" href="{{ asset('css/ourproduct.css') }}" as="style" onload="this.rel='stylesheet'">
    @else
        @foreach([
            'faq','contactus','newscenter','countdowntime','publication',
            'ourproduct','tellusstories','ourservice','ourproductheader',
            'applicationlink','showstories','storycontent','searching'
        ] as $css)
            <link rel="preload" href="{{ asset("css/$css.css") }}" as="style" onload="this.rel='stylesheet'">
        @endforeach
    @endif

    <!-- =====================================================
         JS (DEFERRED – NON-BLOCKING)
    ====================================================== -->
    <script src="{{ config('links.cdn.bootstrap_js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/organization.js') }}" defer></script>

    @if($currentRoute === 'home')
        <script src="{{ asset('js/countdowntime.js') }}" defer></script>
        <script src="{{ asset('js/ourproduct.js') }}" defer></script>
    @else
        <script src="{{ asset('js/contactus.js') }}" defer></script>
        <script src="{{ asset('js/newscenter.js') }}" defer></script>
        <script src="{{ asset('js/ourproduct.js') }}" defer></script>
    @endif

    <!-- Leaflet (ONLY WHEN NEEDED) -->
    @if(in_array($currentRoute, ['contactus.formandregion','contactus.getusintouch','contact.store']))
        <link rel="preload" href="{{ config('links.cdn.leaflet_css') }}" as="style" onload="this.rel='stylesheet'">
        <script src="{{ config('links.cdn.leaflet_js') }}" defer></script>
    @endif
</head>

<body style="min-height:100vh; display:flex; flex-direction:column;">

    <!-- HEADER -->
    @include('partials.headers.topbar')
    @include('partials.headers.organization')

    <!-- MAIN -->
    <main style="flex:1 0 auto;">
        @yield('content')
        @yield('scripts')
    </main>

    <!-- FOOTER -->
    @include('partials.footer')

    <!-- FLOATING BUTTONS -->
    @unless(in_array($currentRoute, ['contactus.formandregion','contactus.getusintouch','contact.store']))
        @include('components.floating-buttons')
    @endunless

</body>
</html>
