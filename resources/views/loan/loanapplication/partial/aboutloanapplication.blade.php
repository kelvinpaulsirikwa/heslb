@extends('loan.loanapplication.loanapplication')

@php($pageTitle = __('loanservice.general_information'))

@section('aboutus-content')
<div class="general-info-container" style="padding: 40px 0;">
    <div class="container">
        <div class="content-wrapper" style="background: rgba(255, 255, 255, 0.95); padding: 40px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">

            <!-- General Information -->
            <div class="general-info-section mb-5">
                <h2 class="mb-4" style="color: #2c5aa0; border-bottom: 3px solid #2c5aa0; padding-bottom: 10px;">
                    {{ __('loanservice.general_info_heading') }}
                </h2>
                <p style="font-size: 16px; line-height: 1.6; color: #333;">
                    {{ __('loanservice.heslb_mandate') }} {{ __('loanservice.annual_almanac') }} {{ __('loanservice.application_window') }} {{ __('loanservice.eligible_students') }}
                </p>

                <p style="font-size: 16px; line-height: 1.6; color: #333;">
                    {{ __('loanservice.eligibility_criteria') }} {{ __('loanservice.guidelines_download') }} {{ __('loanservice.read_guidelines') }} {{ __('loanservice.prepare_requirements') }}
                </p>
            </div>

            <div class="image-section mt-5">
                <div class="text-center">
                    <img src="{{ asset('images/static_files/loanapplication.png') }}"
                         alt="{{ __('loanservice.image_alt') }}"
                         class="img-fluid rounded shadow-lg"
                         style="max-width: 100%; height: auto; border: 3px solid #2c5aa0;">
                </div>
            </div>

        </div>
    </div>
</div>

<style>
.general-info-container {
    position: relative;
}

.content-wrapper {
    background: white !important;
    border: 1px solid #e0e0e0;
}

.note-item {
    padding: 10px 0;
    border-left: 4px solid #2c5aa0;
    padding-left: 15px;
    margin-left: 10px;
}

.note-item p {
    margin-bottom: 0;
    color: #444;
    font-size: 15px;
}

.image-section img {
    transition: transform 0.3s ease;
}

.image-section img:hover {
    transform: scale(1.02);
}

@media (max-width: 768px) {
    .content-wrapper {
        padding: 20px;
        margin: 10px;
    }

    .general-info-container {
        padding: 20px 0;
    }

    .image-section img {
        border-width: 2px !important;
    }
}
</style>
@endsection
