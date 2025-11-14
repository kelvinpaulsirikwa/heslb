{{-- resources/views/layouts/header.blade.php --}}
@php
    if (session_status() === PHP_SESSION_NONE) session_start();
    $role = session('auth_user_role'); // Accessing role from session
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> Heslb | Admin {{ $pageTitle ?? 'Dashboard' }}</title>
    <link rel="icon" href="{{ asset('/images/static_files/heslblogos.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ config('links.cdn.bootstrap_css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="{{ config('links.cdn.fontawesome_css') }}" crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Boxicons -->
    <link href="{{ config('links.cdn.boxicons') }}" rel="stylesheet"> 
    <link rel="stylesheet" href="{{ asset('css/searching.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/adminlogin.css') }}">
    
    <link rel="stylesheet" href="{{ asset('css/adminsidebar.css') }}">

    <!-- JS Files -->
    <script src="{{ config('links.cdn.bootstrap_js') }}"></script>
    <script src="{{ asset('js/adminsidebar.js') }}"></script>
    <script src="{{ asset('js/admin-validation.js') }}"></script>
    <script src="{{ asset('js/image-fallback.js') }}"></script>
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>


  
	

</head>
<body style="min-height:100vh; display:flex; flex-direction:column;">

 
        @include('adminpages.layouts.partials.sidebar')
        @include('adminpages.layouts.partials.navbar')
   

    {{-- Page content --}}
    <div class="main-content" style="flex:1 0 auto;">
        @yield('content')
    </div>

    {{-- Footer --}}
    @include('adminpages.layouts.partials.footer')

</body>
</html>
