@extends('layouts.app')

@php
    // Define page titles for each route
    $pageTitles = [
        'about' => __('messages.page_title'),
        'aboutus.visionmission' => __('messages.sidebar.visionmission'),
        'aboutus.function' => __('messages.sidebar.function'),
        'aboutus.organogram' => __('messages.sidebar.organogram'),
        'aboutus.executivedirector' => __('messages.sidebar.executivedirector'),
        'aboutus.boardofdirector' => __('messages.sidebar.board'),
        'aboutus.strategicpartners' => __('messages.sidebar.partners'),
        'aboutus.photogallery' => __('messages.sidebar.photogallery'),
        'aboutus.videopodcast' => __('messages.sidebar.videopodcast'),
    ];
    
    // Get current page title or use default
    $currentPageTitle = $pageTitles[request()->route()->getName()] ?? __('messages.sidebar.photogallery');
@endphp

@section('content')
<div class="heslb-hero">
    <div class="container">
        <h1 class="heslb-hero-title" id="page-title">{{ $currentPageTitle }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb text-white">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}" class="text-white">{{ __('messages.breadcrumb_home') }}</a>
                </li>
                <li class="breadcrumb-item active" id="breadcrumb-title" aria-current="page">{{ $currentPageTitle }}</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4 align-items-stretch">
        <!-- Who We Are Section -->
        <div class="col-lg-8">
            <div style="background-color: white; padding: 40px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); height: 100%;">
                <h2 style="font-weight: 700; border-left: 5px solid #0e9bd5; padding-left: 16px; color: #1b1f3b;">{{ __('messages.who_we_are') }}</h2>
                <p style="margin-top: 1.5rem; font-size: 1.05rem; line-height: 1.7; color: #555;">
                    {{ __('messages.who_we_are_text') }}
                </p>
                <p style="font-weight: 600; color: #1b1f3b;">{{ __('messages.main_mandates') }}</p>
                <ol style="margin-left: 1rem; color: #444; line-height: 1.8; font-size: 1.02rem;">
                    <li>{{ __('messages.mandate_1') }}</li>
                    <li>{{ __('messages.mandate_2') }}</li>
                    <li>{{ __('messages.mandate_3') }}</li>
                </ol>
            </div>
        </div>

        <!-- Our Vision Section -->
        <div class="col-lg-4">
            <div style="background: linear-gradient(135deg, #0e9bd5, #0e9bd5 ); color: white; padding: 40px 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); transition: transform 0.3s;">
                <h2 style="font-weight: 700; margin-bottom: 20px;">{{ __('messages.our_vision') }}</h2>
                <p style="font-size: 1.15rem; line-height: 1.7;">
                    {{ __('messages.our_vision_text') }}
                </p>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <!-- Sidebar Navigation -->
        <div class="col-lg-3 mb-4">
            <ul class="heslb-sidebar list-unstyled">
                <li><a href="{{ route('aboutus.visionmission') }}" class="{{ request()->routeIs('aboutus.visionmission') ? 'active' : '' }}">{{ __('messages.sidebar.visionmission') }}</a></li>
                <li><a href="{{ route('aboutus.function') }}" class="{{ request()->routeIs('aboutus.function') ? 'active' : '' }}">{{ __('messages.sidebar.function') }}</a></li>
                <li><a href="{{ route('aboutus.organogram') }}" class="{{ request()->routeIs('aboutus.organogram') ? 'active' : '' }}">{{ __('messages.sidebar.organogram') }}</a></li>
                <li><a href="{{ route('aboutus.executivedirector') }}" class="{{ request()->routeIs('aboutus.executivedirector') ? 'active' : '' }}">{{ __('messages.sidebar.executivedirector') }}</a></li>
                <li><a href="{{ route('aboutus.boardofdirector') }}" class="{{ request()->routeIs('aboutus.boardofdirector') ? 'active' : '' }}">{{ __('messages.sidebar.board') }}</a></li>
                <li><a href="{{ route('aboutus.strategicpartners') }}" class="{{ request()->routeIs('aboutus.strategicpartners') ? 'active' : '' }}">{{ __('messages.sidebar.partners') }}</a></li>
                <li><a href="{{ route('aboutus.photogallery') }}" class="{{ request()->routeIs('aboutus.photogallery') ? 'active' : '' }}">{{ __('messages.sidebar.photogallery') }}</a></li>
                <li><a href="{{ route('aboutus.videopodcast') }}" class="{{ request()->routeIs('aboutus.videopodcast') ? 'active' : '' }}">{{ __('messages.sidebar.videopodcast') }}</a></li>
            </ul>
        </div>

        <!-- Right Hand Content -->
        <div class="col-lg-9">
            <div class="heslb-content-box">
                <div class="heslb-content-header">
                    {{ $currentPageTitle }}
                </div>
               <div class="heslb-content-body">
                    <div class="heslb-content-container">
                        @yield('aboutus-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('homepage.ourproduct')
@endsection
