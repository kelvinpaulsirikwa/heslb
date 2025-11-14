@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-book me-2 text-primary"></i>
                        Admin Validation Documentation
                    </h2>
                    <p class="text-muted mb-0">Complete guide to form validation rules and requirements</p>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Dashboard
                </a>
            </div>

            <!-- Validation Rules Overview -->
            <div class="row">
                @foreach($validationRules as $formType => $formData)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-{{ $formType === 'user_management' ? 'users' : ($formType === 'success_stories' ? 'star' : ($formType === 'partners' ? 'handshake' : ($formType === 'shortcut_links' ? 'link' : ($formType === 'faqs' ? 'question-circle' : ($formType === 'publications' ? 'file-alt' : ($formType === 'categories' ? 'tags' : ($formType === 'events' ? 'calendar' : ($formType === 'photo_gallery' ? 'images' : ($formType === 'contact_feedback' ? 'envelope' : 'cog'))))))))) }} me-2"></i>
                                {{ $formData['title'] }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordion{{ $loop->index }}">
                                @foreach($formData['rules'] as $field => $rules)
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="heading{{ $loop->parent->index }}{{ $loop->index }}">
                                        <button class="accordion-button collapsed bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->parent->index }}{{ $loop->index }}" aria-expanded="false" aria-controls="collapse{{ $loop->parent->index }}{{ $loop->index }}">
                                            <strong>{{ ucwords(str_replace('_', ' ', $field)) }}</strong>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $loop->parent->index }}{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $loop->parent->index }}{{ $loop->index }}" data-bs-parent="#accordion{{ $loop->index }}">
                                        <div class="accordion-body">
                                            <ul class="list-unstyled mb-0">
                                                @foreach($rules as $rule => $description)
                                                <li class="mb-2">
                                                    <span class="badge bg-info me-2">{{ $rule }}</span>
                                                    <small class="text-muted">{{ $description }}</small>
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-footer bg-light">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ count($formData['rules']) }} validation rules
                            </small>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Validation Best Practices -->
            <div class="row mt-5">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-lightbulb me-2"></i>
                                Validation Best Practices
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-check-circle me-2"></i>
                                        Frontend Validation
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-success me-2"></i>
                                            Real-time validation on input change
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-success me-2"></i>
                                            Immediate feedback for user errors
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-success me-2"></i>
                                            Prevents form submission with errors
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-success me-2"></i>
                                            Smooth scrolling to first error
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-shield-alt me-2"></i>
                                        Backend Validation
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            Server-side security validation
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            Database integrity protection
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            Custom validation rules
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-primary me-2"></i>
                                            Comprehensive error handling
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- File Upload Guidelines -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-upload me-2"></i>
                                File Upload Guidelines
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-image me-2"></i>
                                        Images
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <strong>Formats:</strong> JPEG, PNG, JPG, GIF
                                        </li>
                                        <li class="mb-2">
                                            <strong>Max Size:</strong> 100MB per image
                                        </li>
                                        <li class="mb-2">
                                            <strong>Max Count:</strong> 5 images per form
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-file-video me-2"></i>
                                        Videos
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <strong>Formats:</strong> MP4, AVI, MOV, WMV
                                        </li>
                                        <li class="mb-2">
                                            <strong>Max Size:</strong> 10MB per video
                                        </li>
                                        <li class="mb-2">
                                            <strong>Max Count:</strong> 3 videos per form
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-file me-2"></i>
                                        Documents
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <strong>Formats:</strong> PDF, DOC, DOCX
                                        </li>
                                        <li class="mb-2">
                                            <strong>Max Size:</strong> 100MB per document
                                        </li>
                                        <li class="mb-2">
                                            <strong>Max Count:</strong> 5 documents per form
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Error Handling Information -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error Handling & Display
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-info mb-3">
                                        <i class="fas fa-eye me-2"></i>
                                        Visual Feedback
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <span class="badge bg-danger me-2">Invalid</span>
                                            Red border and error message below field
                                        </li>
                                        <li class="mb-2">
                                            <span class="badge bg-success me-2">Valid</span>
                                            Green border and success message below field
                                        </li>
                                        <li class="mb-2">
                                            <span class="badge bg-warning me-2">Warning</span>
                                            Yellow alert for general form errors
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-info mb-3">
                                        <i class="fas fa-cog me-2"></i>
                                        Error Management
                                    </h6>
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-info me-2"></i>
                                            Automatic error clearing on input
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-info me-2"></i>
                                            Form submission prevention with errors
                                        </li>
                                        <li class="mb-2">
                                            <i class="fas fa-arrow-right text-info me-2"></i>
                                            Server-side error display
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>
@endpush
@endsection
