@extends('loan.loanapplication.loanapplication')

@php($pageTitle = __('loan_guideline.page_title'))

@section('aboutus-content')

<style>
    /* Card Styling */
    .guideline-card {
        border: 1px solid #dee2e6 !important;
        border-radius: 8px !important;
        padding: 25px 30px !important;
        margin-bottom: 25px !important;
        background-color: #fff !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05) !important;
        transition: transform 0.2s ease !important;
        width: 100% !important; /* Make card full width */
        max-width: 100% !important;
    }

    .guideline-card:hover {
        transform: translateY(-3px) !important;
    }

    .guideline-title {
        font-size: 1.25rem !important;
        font-weight: 600 !important;
        color: #0e9bd5 !important;
        margin-bottom: 10px !important;
    }

    .guideline-info {
        font-size: 0.9rem !important;
        color: #6c757d !important;
        margin-bottom: 12px !important;
    }

    .guideline-info i {
        margin-right: 5px !important;
    }

    .guideline-description {
        font-size: 0.95rem !important;
        color: #495057 !important;
        margin-bottom: 15px !important;
        line-height: 1.5 !important;
    }

    .button-group {
        display: flex !important;
        gap: 10px !important;
        flex-wrap: wrap !important;
    }

    .download-button,
    .read-button {
        padding: 10px 25px !important;
        border-radius: 6px !important;
        font-weight: 600 !important;
        text-decoration: none !important;
        font-size: 0.95rem !important;
        display: inline-block !important;
        transition: background-color 0.3s ease !important;
    }

    .download-button {
        background-color: #0e9bd5 !important;
        color: #fff !important;
    }

    .download-button:hover {
        background-color: #0c89c1 !important;
    }

    .read-button {
        background-color: #1f2937 !important;
        color: #fff !important;
    }

    .read-button:hover {
        background-color: #111827 !important;
    }

    /* Ensure container is full width */
    .full-width-container {
        width: 100% !important;
        max-width: 100% !important;
        padding-left: 15px !important;
        padding-right: 15px !important;
    }
</style>

<div class="container-fluid full-width-container py-4" style="min-height: 100vh; background-color: #f8f9fa !important;">
    <div class="row justify-content-center">
        <div class="col-12">
            @if($guidelines->count() > 0)
                @foreach($guidelines as $guideline)
                    <div class="guideline-card">
                        <!-- Title -->
                        <div class="guideline-title">{{ $guideline->title }}</div>

                        <!-- Info -->
                        <div class="guideline-info">
                            <span><i class="far fa-calendar-alt"></i> {{ $guideline->updated_at->format('F d, Y') }}</span> &nbsp;&nbsp;
                            <span><i class="far fa-file-alt"></i> {{ $guideline->formatted_file_size }}</span>
                        </div>

                        <!-- Description -->
                        @if($guideline->description)
                            <div class="guideline-description">
                                {{ $guideline->description }}
                            </div>
                        @endif

                        <!-- Buttons -->
                        <div class="button-group">
                            <a href="{{ asset($guideline->file_path) }}" target="_blank" class="download-button">
                                <i class="fas fa-download"></i> {{ __('publications.download') }}
                            </a>
                            <a href="{{ asset($guideline->file_path) }}" target="_blank" class="read-button">
                                <i class="fas fa-eye"></i> {{ __('publications.read') }}
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <div class="no-guidelines-card border rounded shadow-sm p-5 bg-white">
                        <h4 class="fw-semibold text-dark mb-3">No Guidelines Available</h4>
                        <p class="text-muted mb-0">{!! __('loan_guideline.no_guideline_message') !!}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
