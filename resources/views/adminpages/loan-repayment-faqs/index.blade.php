@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #f8f9fa;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">
                                <i class="fas fa-credit-card text-success me-2"></i>
                                Loan Repayment FAQs
                            </h2>
                            <p class="text-muted mb-0 small">Manage and organize loan repayment FAQs</p>
                        </div>
                        <a href="{{ route('loan-repayment-faqs.create') }}" class="btn btn-success px-4 py-2">
                            <i class="fas fa-plus me-2"></i>Add New FAQ
                        </a>
                    </div>
                </div>

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Loan Repayment FAQs Section -->
                <div class="bg-white shadow-sm border rounded-3 mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h4 class="mb-0 text-dark fw-semibold">
                            <i class="fas fa-credit-card text-success me-2"></i>
                            Loan Repayment FAQs
                            <span class="badge bg-success ms-2">{{ $faqs->count() }}</span>
                        </h4>
                    </div>
                    
                    <div class="card-body p-0">
                        @php
                            $popularLoanRepay = $faqs->where('qnstype', 'popular_questions');
                            $generalLoanRepay = $faqs->where('qnstype', 'general_questions');
                        @endphp
                        
                        @if($faqs->count() > 0)
                            <div class="row">
                                <!-- Popular Questions Column -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light bg-opacity-50 border-bottom">
                                        <h5 class="mb-0 text-dark fw-medium">
                                            <i class="fas fa-star text-warning me-2"></i>
                                            Popular Questions
                                            <span class="badge bg-warning ms-2">{{ $popularLoanRepay->count() }}</span>
                                        </h5>
                                    </div>
                                    
                                    @if($popularLoanRepay->count() > 0)
                                        <div class="faq-items-container">
                                            @foreach($popularLoanRepay as $faq)
                                                <div class="faq-item" id="faq-{{ $faq->id }}">
                                                    <div class="faq-question" onclick="toggleFAQ('faq-{{ $faq->id }}', this)">
                                                        <div class="faq-question-content">
                                                            <span class="question-text">{{ $faq->question }}</span>
                                                            <div class="faq-meta">
                                                                <span class="badge bg-light text-dark me-2">ID: {{ $faq->id }}</span>
                                                                <span class="badge bg-secondary me-2">{{ $faq->user->username ?? 'N/A' }}</span>
                                                                <small class="text-muted">{{ $faq->created_at->format('M d, Y') }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="toggle-icon">
                                                            <i class="fas fa-plus"></i>
                                                        </div>
                                                    </div>
                                                    <div id="faq-{{ $faq->id }}-answer" class="faq-answer">
                                                        <div class="answer-content">
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
                                                            
                                                            <div class="faq-actions mt-3 pt-3 border-top">
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    <a href="{{ route('loan-repayment-faqs.edit', $faq->id) }}" class="btn btn-outline-success btn-sm">
                                                                        <i class="fas fa-edit me-1"></i>Edit
                                                                    </a>
                                                                    <a href="{{ route('loan-repayment-faqs.show', $faq->id) }}" class="btn btn-outline-info btn-sm">
                                                                        <i class="fas fa-eye me-1"></i>View
                                                                    </a>
                                                                    <button class="btn btn-outline-danger btn-sm" 
                                                                            data-bs-toggle="modal" 
                                                                            data-bs-target="#deleteModal{{ $faq->id }}">
                                                                        <i class="fas fa-trash me-1"></i>Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-star text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mt-2 mb-0">No Popular Questions</p>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- General Questions Column -->
                                <div class="col-md-6">
                                    <div class="p-3 bg-light bg-opacity-50 border-bottom">
                                        <h5 class="mb-0 text-dark fw-medium">
                                            <i class="fas fa-list text-info me-2"></i>
                                            General Questions
                                            <span class="badge bg-info ms-2">{{ $generalLoanRepay->count() }}</span>
                                        </h5>
                                    </div>
                                    
                                    @if($generalLoanRepay->count() > 0)
                                        <div class="faq-items-container">
                                            @foreach($generalLoanRepay as $faq)
                                                <div class="faq-item" id="faq-{{ $faq->id }}">
                                                    <div class="faq-question" onclick="toggleFAQ('faq-{{ $faq->id }}', this)">
                                                        <div class="faq-question-content">
                                                            <span class="question-text">{{ $faq->question }}</span>
                                                            <div class="faq-meta">
                                                                <span class="badge bg-light text-dark me-2">ID: {{ $faq->id }}</span>
                                                                <span class="badge bg-secondary me-2">{{ $faq->user->username ?? 'N/A' }}</span>
                                                                <small class="text-muted">{{ $faq->created_at->format('M d, Y') }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="toggle-icon">
                                                            <i class="fas fa-plus"></i>
                                                        </div>
                                                    </div>
                                                    <div id="faq-{{ $faq->id }}-answer" class="faq-answer">
                                                        <div class="answer-content">
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
                                                            
                                                            <div class="faq-actions mt-3 pt-3 border-top">
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    <a href="{{ route('loan-repayment-faqs.edit', $faq->id) }}" class="btn btn-outline-success btn-sm">
                                                                        <i class="fas fa-edit me-1"></i>Edit
                                                                    </a>
                                                                    <a href="{{ route('loan-repayment-faqs.show', $faq->id) }}" class="btn btn-outline-info btn-sm">
                                                                        <i class="fas fa-eye me-1"></i>View
                                                                    </a>
                                                                    <button class="btn btn-outline-danger btn-sm" 
                                                                            data-bs-toggle="modal" 
                                                                            data-bs-target="#deleteModal{{ $faq->id }}">
                                                                        <i class="fas fa-trash me-1"></i>Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-list text-muted" style="font-size: 2rem;"></i>
                                            <p class="text-muted mt-2 mb-0">No General Questions</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-credit-card text-muted" style="font-size: 3rem;"></i>
                                <h5 class="text-muted mt-3">No Loan Repayment FAQs</h5>
                                <p class="text-muted mb-0">No loan repayment questions have been added yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Back to All FAQs -->
                <div class="text-center mt-4">
                    <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i>Back to All FAQs
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modals -->
@foreach($faqs as $faq)
    <div class="modal fade" id="deleteModal{{ $faq->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $faq->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteModalLabel{{ $faq->id }}">
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
                    <form action="{{ route('loan-repayment-faqs.destroy', $faq->id) }}" method="POST" style="display: inline;">
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
@endforeach

<style>
/* FAQ Container Styles */
.faq-items-container {
    max-height: none;
}

.faq-item {
    margin: 0;
    background: #fff;
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.faq-item:last-child {
    border-bottom: none;
}

.faq-item:hover {
    background: #f8f9fa;
}

.faq-question {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
    cursor: pointer;
    background: transparent;
    border: none;
    transition: all 0.3s ease;
    position: relative;
    user-select: none;
}

.faq-question:hover {
    background: #f8f9fa;
}

.faq-question.active {
    background: #e8f5e8;
    border-left: 4px solid #28a745;
}

.faq-question-content {
    flex: 1;
    margin-right: 15px;
}

.question-text {
    font-size: 16px;
    font-weight: 500;
    line-height: 1.4;
    color: #2c3e50;
    display: block;
    margin-bottom: 8px;
}

.faq-meta {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
}

.toggle-icon {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    flex-shrink: 0;
    pointer-events: none;
    box-shadow: 0 2px 4px rgba(40, 167, 69, 0.3);
}

.faq-question.active .toggle-icon {
    background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
    transform: rotate(45deg);
}

.toggle-icon i {
    font-size: 16px;
    transition: transform 0.3s ease;
}

.faq-answer {
    background: white;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out, padding 0.3s ease-out;
}

.faq-answer.show {
    max-height: 1000px;
    transition: max-height 0.5s ease-in;
}

.answer-content {
    padding: 25px 20px;
    color: #555;
    line-height: 1.6;
    border-top: 1px solid #e8f5e8;
}

.answer-content ol {
    margin: 0;
    padding-left: 20px;
}

.answer-content li {
    margin-bottom: 8px;
    font-size: 15px;
}

.answer-content li:last-child {
    margin-bottom: 0;
}

.faq-actions {
    background: #f8f9fa;
    margin: 0 -20px -25px -20px;
    padding: 15px 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .faq-question {
        padding: 15px;
        flex-direction: column;
        align-items: flex-start;
    }
    
    .faq-question-content {
        margin-right: 0;
        margin-bottom: 10px;
        width: 100%;
    }
    
    .toggle-icon {
        align-self: flex-end;
    }
    
    .question-text {
        font-size: 15px;
    }
    
    .answer-content {
        padding: 20px 15px;
    }
    
    .faq-meta {
        font-size: 12px;
    }
    
    .toggle-icon {
        width: 32px;
        height: 32px;
    }
    
    .toggle-icon i {
        font-size: 14px;
    }
}

/* Card improvements */
.card-header {
    border-radius: 0.375rem 0.375rem 0 0;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
}

/* Button improvements */
.btn-sm {
    font-size: 0.8rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-sm:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Auto-dismiss alerts */
.alert {
    border-radius: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ toggle functionality
    window.toggleFAQ = function(faqId, element) {
        const answer = document.getElementById(faqId + '-answer');
        const isActive = element.classList.contains('active');
        
        // Close all other FAQs
        document.querySelectorAll('.faq-question.active').forEach(activeElement => {
            if (activeElement !== element) {
                activeElement.classList.remove('active');
                const activeAnswer = activeElement.nextElementSibling;
                if (activeAnswer && activeAnswer.classList.contains('faq-answer')) {
                    activeAnswer.classList.remove('show');
                }
            }
        });
        
        // Toggle current FAQ
        if (isActive) {
            element.classList.remove('active');
            answer.classList.remove('show');
        } else {
            element.classList.add('active');
            answer.classList.add('show');
        }
    };
    
    // Auto-dismiss success alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert-success');
        alerts.forEach(alert => {
            if (alert.classList.contains('show')) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        });
    }, 5000);
});
</script>
@endsection
