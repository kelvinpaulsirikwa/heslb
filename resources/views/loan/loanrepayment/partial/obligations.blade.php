@extends('loan.loanrepayment.loanrepayment')
@php($pageTitle = 'Obligations')

@section('aboutus-content')
<div class="obligations-container" style="padding: 40px 0;">
    <div class="container">
        <div class="content-wrapper" style="background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border: 1px solid #e0e0e0;">

            <!-- Employers' Obligations Section -->
            <div class="employers-obligations-section mb-5">
                <h2 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px; font-size: 28px; font-weight: 600;">
                    {{ __('loanrepaymentobligation.employers_title') }}
                </h2>
                <div class="obligations-list">
                    <ul class="custom-list" style="list-style: none; padding-left: 0;">
                        @foreach(__('loanrepaymentobligation.employers') as $item)
                            <li class="obligation-item mb-4">
                                <div class="d-flex align-items-start">
                                    <span class="bullet-point" style="color: #2c5aa0; font-size: 18px; font-weight: bold; margin-right: 10px; margin-top: 2px;">•</span>
                                    <p style="font-size: 16px; line-height: 1.6; color: #333; margin: 0;">{{ $item }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Beneficiaries' Obligations Section -->
            <div class="beneficiaries-obligations-section mb-5">
                <h2 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px; font-size: 28px; font-weight: 600;">
                    {{ __('loanrepaymentobligation.beneficiaries_title') }}
                </h2>
                <div class="obligations-list">
                    <ul class="custom-list" style="list-style: none; padding-left: 0;">
                        @foreach(__('loanrepaymentobligation.beneficiaries') as $item)
                            <li class="obligation-item mb-4">
                                <div class="d-flex align-items-start">
                                    <span class="bullet-point" style="color: #2c5aa0; font-size: 18px; font-weight: bold; margin-right: 10px; margin-top: 2px;">•</span>
                                    <p style="font-size: 16px; line-height: 1.6; color: #333; margin: 0;">{{ $item }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.obligations-container {
    position: relative;
    min-height: 100vh;
}

.content-wrapper {
    background: white !important;
    border: 1px solid #e0e0e0;
}

.obligation-item {
    padding: 15px;
    border-left: 4px solid #2c5aa0;
    margin-left: 20px;
    background: rgba(44, 90, 160, 0.02);
    border-radius: 5px;
    transition: all 0.3s ease;
}

.obligation-item:hover {
    background: rgba(44, 90, 160, 0.05);
    transform: translateX(5px);
}

.bullet-point {
    min-width: 20px;
    display: block;
}

.image-section img {
    transition: transform 0.3s ease;
}

.image-section img:hover {
    transform: scale(1.02);
}

.employers-obligations-section, .beneficiaries-obligations-section {
    position: relative;
    padding: 20px 0;
}

@media (max-width: 768px) {
    .content-wrapper {
        padding: 20px;
        margin: 10px;
    }
    
    .obligations-container {
        padding: 20px 0;
    }
    
    .obligation-item {
        margin-left: 10px;
        padding: 12px;
    }
    
    h2 {
        font-size: 24px !important;
    }
    
    .bullet-point {
        font-size: 16px !important;
    }
    
    .obligation-item p {
        font-size: 14px !important;
    }
    
    .image-section img {
        border-width: 2px !important;
    }
}

@media (max-width: 576px) {
    .d-flex {
        flex-direction: column;
    }
    
    .bullet-point {
        margin-bottom: 5px;
        margin-right: 0 !important;
    }
    
    .obligation-item {
        margin-left: 5px;
    }
}
</style>

@endsection
