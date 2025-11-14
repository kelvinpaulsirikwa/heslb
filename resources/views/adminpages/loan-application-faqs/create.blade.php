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
                            <h2 class="mb-1 text-dark fw-bold">
                                <i class="fas fa-file-alt text-primary me-2"></i>
                                Add New Loan Application FAQ
                            </h2>
                            <p class="text-muted mb-0 small">Create a new frequently asked question for loan applications</p>
                        </div>
                        <a href="{{ route('loan-application-faqs.index') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <div>
                                <strong>Please correct the following errors:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Form Card -->
                <div class="bg-white shadow-sm border rounded-3 p-4">
                    <form action="{{ route('loan-application-faqs.store') }}" method="POST">
                        @csrf
                        
                        <!-- Question Field -->
                        <div class="mb-4">
                            <label for="question" class="form-label fw-semibold text-dark">
                                <i class="fas fa-question-circle me-2 text-primary"></i>Question
                            </label>
                            <input type="text" id="question" name="question" class="form-control form-control-lg" 
                                   value="{{ old('question') }}" placeholder="Enter the frequently asked question..." required>
                        </div>

                        <!-- Steps / Answer Field -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-dark">
                                <i class="fas fa-list-ol me-2 text-success"></i>Steps / Answer
                            </label>
                            <div id="steps-wrapper" class="border rounded-3 p-3 bg-light bg-opacity-50">
                                <input type="text" name="steps[]" class="form-control mb-2" placeholder="Step 1: Enter the first step..." required>
                            </div>
                            <button type="button" id="add-step" class="btn btn-outline-success btn-sm mt-2">
                                <i class="fas fa-plus me-1"></i>Add Another Step
                            </button>
                            <small class="form-text text-muted d-block mt-1">Add step-by-step instructions to help citizens complete the loan application process</small>
                        </div>

                        <!-- Type Field (Hidden for Loan Application FAQs) -->
                        <input type="hidden" name="type" value="loan_application">

                        <!-- QnsType Field -->
                        <div class="mb-4">
                            <label for="qnstype" class="form-label fw-semibold text-dark">
                                <i class="fas fa-folder me-2 text-warning"></i>Question Category
                            </label>
                            <select id="qnstype" name="qnstype" class="form-select form-select-lg" required>
                                <option value="">Select Question Category</option>
                                <option value="popular_questions" {{ old('qnstype') == 'popular_questions' ? 'selected' : '' }}>Popular Questions</option>
                                <option value="general_questions" {{ old('qnstype') == 'general_questions' ? 'selected' : '' }}>General Questions</option>
                            </select>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-md-row gap-3 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Save FAQ
                            </button>
                            <a href="{{ route('loan-application-faqs.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stepsWrapper = document.getElementById('steps-wrapper');
    const addStepBtn = document.getElementById('add-step');
    let stepCount = 1;

    addStepBtn.addEventListener('click', function() {
        stepCount++;
        const stepInput = document.createElement('input');
        stepInput.type = 'text';
        stepInput.name = 'steps[]';
        stepInput.className = 'form-control mb-2';
        stepInput.placeholder = `Step ${stepCount}: Enter the next step...`;
        stepInput.required = true;
        
        stepsWrapper.appendChild(stepInput);
    });
});
</script>
@endsection
