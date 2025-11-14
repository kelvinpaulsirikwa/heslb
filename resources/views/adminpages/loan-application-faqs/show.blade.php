@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #f8f9fa;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">
                                <i class="fas fa-eye text-primary me-2"></i>
                                View Loan Application FAQ
                            </h2>
                            <p class="text-muted mb-0 small">Details of the frequently asked question</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('loan-application-faqs.edit', $faq->id) }}" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-edit me-2"></i>Edit FAQ
                            </a>
                            <a href="{{ route('loan-application-faqs.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- FAQ Details Card -->
                <div class="bg-white shadow-sm border rounded-3 p-4">
                    <!-- Question Section -->
                    <div class="mb-4">
                        <h4 class="text-dark fw-semibold mb-3">
                            <i class="fas fa-question-circle text-primary me-2"></i>
                            Question
                        </h4>
                        <div class="bg-light p-3 rounded-3">
                            <p class="mb-0 text-dark fw-medium">{{ $faq->question }}</p>
                        </div>
                    </div>

                    <!-- Answer Section -->
                    <div class="mb-4">
                        <h4 class="text-dark fw-semibold mb-3">
                            <i class="fas fa-list-ol text-success me-2"></i>
                            Answer / Steps
                        </h4>
                        <div class="bg-light p-3 rounded-3">
                            @php
                                $steps = json_decode($faq->answer, true) ?? [];
                            @endphp
                            @if(count($steps))
                                <ol class="mb-0">
                                    @foreach($steps as $step)
                                        <li class="mb-2">{{ $step }}</li>
                                    @endforeach
                                </ol>
                            @else
                                <p class="text-muted mb-0">{{ $faq->answer }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- FAQ Metadata -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h5 class="text-dark fw-semibold mb-2">
                                <i class="fas fa-tag text-info me-2"></i>
                                Category
                            </h5>
                            <div class="d-flex gap-2">
                                <span class="badge bg-primary">Loan Application</span>
                                <span class="badge bg-{{ $faq->qnstype == 'popular_questions' ? 'warning' : 'info' }}">
                                    {{ $faq->qnstype == 'popular_questions' ? 'Popular Questions' : 'General Questions' }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h5 class="text-dark fw-semibold mb-2">
                                <i class="fas fa-user text-secondary me-2"></i>
                                Posted By
                            </h5>
                            <p class="text-muted mb-0">{{ $faq->user->username ?? 'N/A' }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h5 class="text-dark fw-semibold mb-2">
                                <i class="fas fa-calendar text-success me-2"></i>
                                Created Date
                            </h5>
                            <p class="text-muted mb-0">{{ $faq->created_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <h5 class="text-dark fw-semibold mb-2">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Last Updated
                            </h5>
                            <p class="text-muted mb-0">{{ $faq->updated_at->format('F d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-md-row gap-3 pt-4 border-top">
                        <a href="{{ route('loan-application-faqs.edit', $faq->id) }}" class="btn btn-primary px-4">
                            <i class="fas fa-edit me-2"></i>Edit FAQ
                        </a>
                        <button class="btn btn-outline-danger px-4" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Delete FAQ
                        </button>
                        <a href="{{ route('loan-application-faqs.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Confirm FAQ Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="mb-3">
                        <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="text-muted mb-2">Are you sure you want to delete this FAQ?</h6>
                    <p class="fw-bold text-dark mb-0">{{ $faq->question }}</p>
                </div>
                
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-triangle me-2 mt-1"></i>
                        <div>
                            <strong>Warning:</strong> This action cannot be undone. The FAQ will be permanently removed from the system.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>
                    Cancel
                </button>
                <form action="{{ route('loan-application-faqs.destroy', $faq->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Delete FAQ
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
