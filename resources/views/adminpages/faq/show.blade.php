@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #ffffff;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">FAQ Details</h2>
                            <p class="text-muted mb-0 small">View frequently asked question information</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('faq.edit', $faq->id) }}" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-edit me-2"></i>Edit FAQ
                            </a>
                            <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- FAQ Details Card -->
                <div class="bg-white shadow-sm border rounded-3 overflow-hidden">
                    <!-- Question Section -->
                    <div class="p-4 border-bottom bg-light bg-opacity-50">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-question-circle text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div class="flex-grow-1">
                                <h5 class="mb-2 text-dark fw-semibold">Question</h5>
                                <p class="mb-0 text-dark fs-5 lh-base">{{ $faq->question }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Answer / Steps Section -->
                    <div class="p-4 border-bottom">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-list-ol text-success me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div class="flex-grow-1">
                                <h5 class="mb-3 text-dark fw-semibold">Answer / Steps</h5>
                                @php
                                    $steps = json_decode($faq->answer, true) ?? [];
                                @endphp
                                @if(count($steps))
                                    <div class="bg-light bg-opacity-30 rounded-3 p-3">
                                        <ol class="mb-0 ps-3">
                                            @foreach($steps as $step)
                                                <li class="text-dark mb-2 lh-base">{{ $step }}</li>
                                            @endforeach
                                        </ol>
                                    </div>
                                @else
                                    <div class="bg-light bg-opacity-30 rounded-3 p-3">
                                        <p class="mb-0 text-muted fst-italic">No steps provided</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Metadata Section -->
                    <div class="p-4">
                        <div class="row g-4">
                            <!-- Type -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tag text-info me-3" style="font-size: 1.1rem;"></i>
                                    <div>
                                        <h6 class="mb-1 text-dark fw-semibold">Type</h6>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">
                                            {{ ucwords(str_replace('_', ' ', $faq->type)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Question Category -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-folder text-warning me-3" style="font-size: 1.1rem;"></i>
                                    <div>
                                        <h6 class="mb-1 text-dark fw-semibold">Question Category</h6>
                                        <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2">
                                            {{ ucwords(str_replace('_', ' ', $faq->qnstype)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Posted By -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user text-secondary me-3" style="font-size: 1.1rem;"></i>
                                    <div>
                                        <h6 class="mb-1 text-dark fw-semibold">Posted By</h6>
                                        <p class="mb-0 text-dark">{{ $faq->user->username ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- FAQ ID -->
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-hashtag text-muted me-3" style="font-size: 1.1rem;"></i>
                                    <div>
                                        <h6 class="mb-1 text-dark fw-semibold">FAQ ID</h6>
                                        <span class="badge bg-light text-dark border px-3 py-2">{{ $faq->id }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex flex-column flex-md-row gap-3 mt-4">
                    <a href="{{ route('faq.edit', $faq->id) }}" class="btn btn-primary btn-lg px-5">
                        <i class="fas fa-edit me-2"></i>Edit This FAQ
                    </a>
                    <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                        <i class="fas fa-list me-2"></i>View All FAQs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for government FAQ view */
.card {
    border: none;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
    letter-spacing: 0.3px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1rem;
}

.badge {
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 0.375rem;
}

/* Section styling */
.border-bottom {
    border-color: #e9ecef !important;
}

/* Icon styling */
.fas {
    flex-shrink: 0;
}

/* Typography improvements */
.fs-5 {
    font-weight: 400;
}

.lh-base {
    line-height: 1.6;
}

/* Responsive design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .fs-5 {
        font-size: 1.1rem !important;
    }
}

/* Card improvements */
.bg-white {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Hover effects for interactive elements */
.badge {
    transition: all 0.2s ease;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endsection