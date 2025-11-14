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
                            <h2 class="mb-1 text-dark fw-bold">Add New FAQ</h2>
                            <p class="text-muted mb-0 small">Create a new frequently asked question for citizens</p>
                        </div>
                        <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary px-4 py-2">
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
                    <form action="{{ route('faq.store') }}" method="POST">
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
                            <small class="form-text text-muted d-block mt-1">Add step-by-step instructions to help citizens complete the process</small>
                        </div>

                        <!-- Type Field -->
                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold text-dark">
                                <i class="fas fa-tag me-2 text-info"></i>Type
                            </label>
                            <select id="type" name="type" class="form-select form-select-lg" required>
                                <option value="">Select FAQ Type</option>
                                <option value="loan_application" {{ old('type') == 'loan_application' ? 'selected' : '' }}>Loan Application</option>
                                <option value="loan_repayment" {{ old('type') == 'loan_repayment' ? 'selected' : '' }}>Loan Repayment</option>
                            </select>
                        </div>

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

                        <!-- Posted By Field -->
                       

                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-md-row gap-3 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Save FAQ
                            </button>
                            <a href="{{ route('faq.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dynamic Steps Script -->
<script>
    document.getElementById('add-step').addEventListener('click', function() {
        var wrapper = document.getElementById('steps-wrapper');
        var stepCount = wrapper.querySelectorAll('input').length + 1;
        
        // Create step container
        var stepContainer = document.createElement('div');
        stepContainer.className = 'd-flex align-items-center mb-2';
        
        // Create input
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'steps[]';
        input.className = 'form-control me-2';
        input.placeholder = 'Step ' + stepCount + ': Enter step description...';
        
        // Create remove button
        var removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'btn btn-outline-danger btn-sm';
        removeBtn.innerHTML = '<i class="fas fa-minus"></i>';
        removeBtn.onclick = function() {
            stepContainer.remove();
            updateStepNumbers();
        };
        
        stepContainer.appendChild(input);
        stepContainer.appendChild(removeBtn);
        wrapper.appendChild(stepContainer);
        
        updateStepNumbers();
    });
    
    function updateStepNumbers() {
        var inputs = document.querySelectorAll('#steps-wrapper input');
        inputs.forEach(function(input, index) {
            input.placeholder = 'Step ' + (index + 1) + ': Enter step description...';
        });
    }
</script>

<!-- Dynamic Steps Script -->
<script>
    document.getElementById('add-step').addEventListener('click', function() {
        var wrapper = document.getElementById('steps-wrapper');
        var stepCount = wrapper.querySelectorAll('input').length + 1;
        
        // Create step container
        var stepContainer = document.createElement('div');
        stepContainer.className = 'd-flex align-items-center mb-2';
        
        // Create input
        var input = document.createElement('input');
        input.type = 'text';
        input.name = 'steps[]';
        input.className = 'form-control me-2';
        input.placeholder = 'Step ' + stepCount + ': Enter step description...';
        
        // Create remove button (only show if more than 1 step)
        if (stepCount > 1) {
            var removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'btn btn-outline-danger btn-sm';
            removeBtn.innerHTML = '<i class="fas fa-minus"></i>';
            removeBtn.onclick = function() {
                stepContainer.remove();
                updateStepNumbers();
            };
            stepContainer.appendChild(removeBtn);
        }
        
        stepContainer.appendChild(input);
        wrapper.appendChild(stepContainer);
        
        updateStepNumbers();
    });
    
    function updateStepNumbers() {
        var inputs = document.querySelectorAll('#steps-wrapper input');
        inputs.forEach(function(input, index) {
            input.placeholder = 'Step ' + (index + 1) + ': Enter step description...';
        });
    }
</script>

<style>
/* Custom styles for government theme */
.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #e0e0e0;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
}

.form-label {
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.form-label i {
    width: 20px;
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

.alert {
    border-radius: 0.5rem;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Steps wrapper styling */
#steps-wrapper {
    min-height: 60px;
}

#steps-wrapper input {
    background-color: #ffffff;
}

/* Responsive design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .form-control, .form-select {
        font-size: 0.9rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
    }
}

/* Card improvements */
.bg-white {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Form improvements */
.border-top {
    border-color: #e9ecef !important;
}
</style>
@endsection