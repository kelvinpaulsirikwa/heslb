@extends('loan.loanrepayment.loanrepayment')

@section('aboutus-content')
<div class="olams-main-container h-100 d-flex align-items-center justify-content-center">
    <div class="w-100" style="max-width: 1400px;">
        <div class="olams-hero-card border-0 shadow-lg h-100 position-relative overflow-hidden">
            
            <!-- Background Image with Overlay -->
            <div class="olams-bg-image position-absolute w-100 h-100"></div>
            
            <!-- Decorative Pattern Overlay -->
            <div class="olams-pattern-overlay position-absolute w-100 h-100"></div>
            
            <div class="card-body p-5 d-flex align-items-center h-100 position-relative">
                <div class="row align-items-center w-100 g-4">
                    
                    <!-- Left Content -->
                    <div class="col-lg-7 col-md-7">
                        <div class="pe-lg-4">
                            <h1 class="olams-main-title fw-bold mb-4">
                                {{ __('repaymentservice.higher_education_loan_repayment') }}
                                <div class="olams-subtitle mt-2">
                                    ({{ __('repaymentservice.employer_repayment_portal') }})
                                </div>
                            </h1>
                            <p class="olams-description card-text mb-4">
                                {{ __('repaymentservice.loan_repayment_portal') }} - {{ __('repaymentservice.make_repayment') }}, {{ __('repaymentservice.track_repayment') }}, {{ __('repaymentservice.secure_payment') }}.
                            </p>
                            <div class="olams-badges-container d-flex flex-wrap gap-3 mb-4">
                                <span class="olams-feature-badge px-3 py-2">{{ __('repaymentservice.convenient') }}</span>
                                <span class="olams-feature-badge px-3 py-2">{{ __('repaymentservice.secure') }}</span>
                                <span class="olams-feature-badge px-3 py-2">{{ __('repaymentservice.transparent') }}</span>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

            <!-- Circle Button on Top Right -->
            <div class="olams-btn-container position-absolute top-0 end-0 p-4">
                <a href="{{ config('links.heslb_systems.olams_employer_login') }}" target="_blank" class="text-decoration-none">
                    <div class="olams-circular-button btn btn-light shadow-lg border-0">
                        <div class="olams-pulse-ring"></div>
                        <div class="olams-btn-content">
                            <div class="olams-btn-main-text fw-bold">{{ __('repaymentservice.erp') }}</div>
                            <div class="olams-btn-sub-text mt-1 small fw-semibold">{{ __('repaymentservice.start_repayment') }}</div>

                            <div class="olams-btn-action-text mt-2 ultra-small">{{ __('repaymentservice.click_to_proceed') }}</div>
                            <div class="olams-btn-arrow-icon mt-2">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8.59 16.59L13.17 12L8.59 7.41L10 6l6 6-6 6-1.41-1.41z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
