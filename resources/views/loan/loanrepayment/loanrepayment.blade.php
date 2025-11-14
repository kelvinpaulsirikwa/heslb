@extends('layouts.app')

<style>
    body {
        background-color: #f8f9fa;
    }

    .heslb-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)),
            url('{{ asset("images/static_files/un-home-bg.jpg") }}');
        background-size: cover;
        background-position: left center;
        height: 320px;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        color: white;
    }

    .heslb-hero-title {
        font-size: 2.3rem;
        font-weight: bold;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        text-transform: uppercase;
    }

    .breadcrumb {
        background: transparent;
        padding: 0;
        margin-top: 10px;
    }

    .breadcrumb-item.active {
        text-decoration: none;
        color: #009fe3 !important;
    }

    .heslb-sidebar a {
        display: block;
        padding: 15px 20px;
        margin-bottom: 10px;
        background-color: #f1f1f1;
        color: #222;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.2s ease-in-out;
    }

    .heslb-sidebar a:hover,
    .heslb-sidebar a.active {
        background-color: #009fe3;
        color: #fff;
    }

    .heslb-content-box {
        background: white;
        border-radius: 0;
        box-shadow: none;
        overflow: visible;
        padding: 0;
    }

    .heslb-content-body {
        padding: 0;
        color: #333;
        font-size: 1.05rem;
        line-height: 1.7;
    }
</style>

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <ul class="heslb-sidebar list-unstyled">
            <li><a href="{{ route('loanrepayment.repaymentlink') }}" class="{{ request()->routeIs('loanrepayment.repaymentlink') ? 'active' : '' }}">{{ __('loanrepayment.sidebar.repaymentlink') }}</a></li>

           
                <li><a href="{{ route('loanrepayment.about') }}" class="{{ request()->routeIs('loanrepayment.about') ? 'active' : '' }}">{{ __('loanrepayment.sidebar.about') }}</a></li>
                <li><a href="{{ route('loanrepayment.obligation') }}" class="{{ request()->routeIs('loanrepayment.obligation') ? 'active' : '' }}">{{ __('loanrepayment.sidebar.obligation') }}</a></li>
                <li><a href="{{ route('loanrepayment.faqs') }}" class="{{ request()->routeIs('loanrepayment.faqs') ? 'active' : '' }}">{{ __('loanrepayment.sidebar.faqs') }}</a></li>
            </ul>
        </div>

        <!-- Main Content Area -->
        <div class="col-lg-9">
            <div class="heslb-content-box">
                <div class="heslb-content-body">
                    <div class="heslb-content-container">
                        @yield('aboutus-content')
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
