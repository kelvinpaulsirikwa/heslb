@extends('loan.loanapplication.loanapplication')

@php($pageTitle = __('loanservice.obligations'))

@section('aboutus-content')
<div class="obligations-container" style="padding: 40px 0;">
    <div class="container">
        <div class="content-wrapper">

            <!-- Prospective Students Section -->
            <div class="prospective-students-section mb-5">
                <h2 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px; font-size: 28px; font-weight: 600;">
                    {{ __('loanservice.prospective_students') }}
                </h2>
                <ul class="custom-list" style="list-style: none; padding-left: 0;">
                    @foreach(__('loanservice.prospective_students_obligations') as $item)
                        <li class="obligation-item mb-4">
                            <div class="d-flex align-items-start">
                                <span class="bullet-point">•</span>
                                <p>{{ $item }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Student Beneficiaries Section -->
            <div class="student-beneficiaries-section mb-5">
                <h2 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px; font-size: 28px; font-weight: 600;">
                    {{ __('loanservice.student_beneficiaries') }}
                </h2>
                <ul class="custom-list" style="list-style: none; padding-left: 0;">
                    @foreach(__('loanservice.student_beneficiaries_obligations') as $item)
                        <li class="obligation-item mb-4">
                            <div class="d-flex align-items-start">
                                <span class="bullet-point">•</span>
                                <p>{{ $item }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Guarantors Section -->
            <div class="guarantors-section">
                <h2 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px; font-size: 28px; font-weight: 600;">
                    {{ __('loanservice.guarantors') }}
                </h2>
                <ul class="custom-list" style="list-style: none; padding-left: 0;">
                    @foreach(__('loanservice.guarantors_obligations') as $item)
                        <li class="obligation-item mb-4">
                            <div class="d-flex align-items-start">
                                <span class="bullet-point">•</span>
                                <p>{{ $item }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
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
    padding: 40px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
    color: #2c5aa0;
    font-size: 18px;
    font-weight: bold;
    min-width: 20px;
    display: block;
    margin-right: 10px;
    margin-top: 2px;
}

@media (max-width: 768px) {
    .content-wrapper {
        padding: 20px;
        margin: 10px;
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
