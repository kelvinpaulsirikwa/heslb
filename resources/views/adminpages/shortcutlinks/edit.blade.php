@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid px-4 py-5">
    <!-- Header Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="fas fa-edit me-3 text-secondary"></i>
                            Edit Shortcut Link
                        </h1>
                        <p class="mb-0 text-muted fs-6">Modify your existing shortcut link or file resource</p>
                    </div>
                    <a href="{{ route('shortcut-links.index') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back to Registry</span>
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Link Information Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm border border-light p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                        <i class="fas {{ $link->is_file ? 'fa-file-alt' : 'fa-globe' }} text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 fw-semibold text-dark">Currently Editing</h6>
                        <div class="text-muted">{{ $link->link_name }}</div>
                        <small class="badge bg-light text-dark border mt-1">
                            ID: #{{ str_pad($link->id, 3, '0', STR_PAD_LEFT) }} | 
                            Type: {{ $link->is_file ? 'File Resource' : 'Web Link' }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm border border-light overflow-hidden">
                <!-- Form Header -->
                <div class="bg-light border-bottom px-4 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0 fw-semibold text-dark">
                                <i class="fas fa-edit text-secondary me-2"></i>
                                Modification Form
                            </h5>
                            <small class="text-muted">Update the link details below</small>
                        </div>
                      
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-5">
                    <x-admin-validation-errors />
                    
                    <form id="shortcutLinksEditForm" action="{{ route('shortcut-links.update', $link->id) }}" method="POST" enctype="multipart/form-data" class="gov-form" data-admin-validation="shortcutLinks">
                        @csrf
                        @method('PUT')

                        <!-- Link Name Field -->
                        <div class="mb-5">
                            <label for="link_name" class="form-label fw-semibold text-dark mb-3">
                                <i class="fas fa-tag text-secondary me-2"></i>
                                Link Name
                                <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="link_name" 
                                   id="link_name"
                                   value="{{ old('link_name', $link->link_name) }}" 
                                   class="form-control form-control-lg border-2 rounded-3 gov-input" 
                                   placeholder="Enter a descriptive name for your government link"
                                   required>
                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Provide a clear, descriptive name that identifies the purpose of this link
                            </div>
                        </div>

                        <!-- Link Type Selection -->
                        <div class="mb-5">
                            <label class="form-label fw-semibold text-dark mb-3">
                                <i class="fas fa-link text-secondary me-2"></i>
                                Resource Type
                                <span class="text-danger">*</span>
                            </label>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check p-0">
                                        <input type="radio" 
                                               class="btn-check" 
                                               name="link_type" 
                                               id="type_link" 
                                               value="link"
                                               {{ filter_var($link->is_file, FILTER_VALIDATE_BOOLEAN) ? '' : 'checked' }}>
                                        <label class="btn btn-outline-dark w-100 p-4 rounded-3 border-2 gov-radio-btn" for="type_link">
                                            <i class="fas fa-external-link-alt mb-3 d-block text-secondary" style="font-size: 1.5rem;"></i>
                                            <strong class="d-block mb-1">External Website</strong>
                                            <small class="text-muted d-block">Link to external web resource</small>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check p-0">
                                        <input type="radio" 
                                               class="btn-check" 
                                               name="link_type" 
                                               id="type_file" 
                                               value="file"
                                               {{ filter_var($link->is_file, FILTER_VALIDATE_BOOLEAN) ? 'checked' : '' }}>
                                        <label class="btn btn-outline-dark w-100 p-4 rounded-3 border-2 gov-radio-btn" for="type_file">
                                            <i class="fas fa-file-upload mb-3 d-block text-secondary" style="font-size: 1.5rem;"></i>
                                            <strong class="d-block mb-1">File Resource</strong>
                                            <small class="text-muted d-block">Upload local file document</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- URL Input Container -->
                        <div class="mb-5" id="link_input_container">
                            <label for="link_input" class="form-label fw-semibold text-dark mb-3">
                                <i class="fas fa-globe text-secondary me-2"></i>
                                Website URL
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-2 border-end-0 text-secondary">
                                    <i class="fas fa-link"></i>
                                </span>
                                <input type="url" 
                                       name="link" 
                                       id="link_input" 
                                       value="{{ old('link', $link->is_file ? '' : $link->link) }}" 
                                       class="form-control border-2 border-start-0 gov-input"
                                       placeholder="https://example.gov.tz"
                                       {{ $link->is_file ? '' : 'required' }}>
                            </div>
                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter a valid URL starting with http:// or https://
                            </div>
                        </div>

                        <!-- File Input Container -->
                        <div class="mb-5 {{ $link->is_file ? '' : 'd-none' }}" id="file_input_container">
                            <label for="file_input" class="form-label fw-semibold text-dark mb-3">
                                <i class="fas fa-cloud-upload-alt text-secondary me-2"></i>
                                Upload New File
                            </label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light border-2 border-end-0 text-secondary">
                                    <i class="fas fa-file"></i>
                                </span>
                                <input type="file" 
                                       name="file" 
                                       id="file_input" 
                                       class="form-control border-2 border-start-0 gov-input">
                            </div>
                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Leave empty to keep existing file. Supported: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Max: 100MB)
                            </div>
                            
                            @if($link->is_file && $link->link)
                                <div class="mt-3 p-4 bg-light rounded-3 border">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-file text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <small class="text-muted text-uppercase fw-semibold">Current File</small>
                                            <div class="fw-semibold text-dark">{{ basename($link->link) }}</div>
                                            <div class="d-flex gap-3 mt-2">
                                                <a href="{{ asset('images/storage/' . $link->link) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-download me-1"></i>
                                                    Download
                                                </a>
                                                <a href="{{ asset('images/storage/' . $link->link) }}" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i>
                                                    Preview
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-light rounded-3 p-4 border">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <a href="{{ route('shortcut-links.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                            <i class="fas fa-times me-2"></i>
                                            Cancel Changes
                                        </a>
                                        <button type="submit" class="btn btn-dark btn-lg px-5">
                                            <i class="fas fa-save me-2"></i>
                                            Update Link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Government Portal Base Styling */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    background-color: #f8f9fa;
}

/* Form Styling */
.gov-form {
    background-color: white;
}

.gov-input {
    transition: all 0.3s ease;
    font-weight: 500;
    background-color: white;
}

.gov-input:focus {
    box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.15);
    border-color: #495057;
    background-color: white;
}

.gov-radio-btn {
    transition: all 0.3s ease;
    background-color: white;
    text-align: center;
    height: auto;
    min-height: 120px;
}

.gov-radio-btn:hover {
    background-color: #f8f9fa;
    border-color: #495057;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.btn-check:checked + .gov-radio-btn {
    background-color: #212529;
    border-color: #212529;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.btn-check:checked + .gov-radio-btn .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.btn-check:checked + .gov-radio-btn .text-secondary {
    color: rgba(255, 255, 255, 0.9) !important;
}

/* Button Styling */
.btn {
    transition: all 0.2s ease;
    font-weight: 500;
    border-radius: 6px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 6px rgba(0,0,0,0.15);
}

.btn-dark {
    background-color: #212529;
    border-color: #212529;
}

.btn-dark:hover {
    background-color: #1a1e21;
    border-color: #1a1e21;
}

.btn-outline-secondary {
    color: #6c757d;
    border-color: #6c757d;
    background-color: white;
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-outline-primary {
    color: #0d6efd;
    border-color: #0d6efd;
    background-color: white;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

/* Alert Styling */
.alert {
    border: 1px solid #f5c6cb;
    border-left: 4px solid #dc3545;
}

.alert-danger {
    background-color: white;
    color: #721c24;
}

/* Input Group Styling */
.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
    color: #6c757d;
}

/* Shadow and Border Utilities */
.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.border-light {
    border-color: #e9ecef !important;
}

.rounded-3 {
    border-radius: 0.75rem !important;
}

/* Form Text Styling */
.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    font-weight: 400;
}

.form-label {
    color: #212529;
    font-weight: 600;
    margin-bottom: 0.75rem;
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Container Transitions */
.fade-transition {
    transition: opacity 0.3s ease, max-height 0.3s ease, padding 0.3s ease;
}

/* Professional Table-like Current File Display */
.current-file-display {
    background: linear-gradient(to right, #f8f9fa 0%, white 100%);
    border-left: 4px solid #28a745;
}

/* Responsive Design */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .btn-lg {
        width: 100%;
        padding: 1rem;
    }
    
    .d-flex.gap-3 {
        flex-direction: column-reverse;
        gap: 0.75rem !important;
    }
    
    .justify-content-end {
        justify-content: center !important;
    }
    
    .px-5 {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }
    
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .gov-radio-btn {
        min-height: 100px;
        padding: 1.5rem 1rem !important;
    }
    
    .gov-radio-btn i {
        font-size: 1.25rem !important;
    }
}

@media (max-width: 576px) {
    .px-4 {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .py-4 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
    }
    
    .p-5 {
        padding: 1.5rem !important;
    }
}

/* Accessibility Improvements */
.btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
}

.btn-outline-secondary:focus {
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
}

.gov-input:focus {
    outline: none;
    box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.15);
}

.btn-check:focus + .gov-radio-btn {
    box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
}

/* Professional Icons */
.fas {
    font-weight: 900;
}

/* Print Styles */
@media print {
    .btn {
        display: none !important;
    }
    
    .alert {
        border: 1px solid #000 !important;
        background: white !important;
    }
    
    .form-control {
        border: 1px solid #000 !important;
        background: white !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const linkRadio = document.getElementById('type_link');
    const fileRadio = document.getElementById('type_file');
    const linkInputContainer = document.getElementById('link_input_container');
    const fileInputContainer = document.getElementById('file_input_container');
    const linkInput = document.getElementById('link_input');
    const fileInput = document.getElementById('file_input');

    // Add transition classes
    linkInputContainer.classList.add('fade-transition');
    fileInputContainer.classList.add('fade-transition');

    function toggleInputs() {
        if (linkRadio.checked) {
            // Show link input, hide file input
            linkInputContainer.classList.remove('d-none');
            fileInputContainer.classList.add('d-none');
            linkInput.required = true;
            fileInput.required = false;
            
            // Update label text
            document.querySelector('label[for="link_input"]').innerHTML = `
                <i class="fas fa-globe text-secondary me-2"></i>
                Website URL
                <span class="text-danger">*</span>
            `;
        } else {
            // Show file input, hide link input
            linkInputContainer.classList.add('d-none');
            fileInputContainer.classList.remove('d-none');
            linkInput.required = false;
            fileInput.required = false;
        }
    }

    // Event listeners
    linkRadio.addEventListener('change', toggleInputs);
    fileRadio.addEventListener('change', toggleInputs);

    // Initialize on page load
    toggleInputs();

    // Form validation enhancement
    const form = document.querySelector('.gov-form');
    form.addEventListener('submit', function(e) {
        const linkType = document.querySelector('input[name="link_type"]:checked').value;
        
        if (linkType === 'link') {
            const urlValue = linkInput.value.trim();
            if (!urlValue) {
                e.preventDefault();
                linkInput.focus();
                linkInput.classList.add('is-invalid');
                return false;
            }
        }
        
        // Add loading state to submit button
        const submitBtn = document.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        submitBtn.disabled = true;
        
        // Re-enable after 5 seconds as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 5000);
    });

    // Remove invalid class on input
    linkInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });

    // File input validation
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const maxSize = 100 * 1024 * 1024; // 100MB
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'image/jpeg', 'image/png'];
            
            if (file.size > maxSize) {
                alert('File size must be less than 100MB');
                this.value = '';
                return;
            }
            
            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid file type (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG)');
                this.value = '';
                return;
            }
        }
    });
});
</script>

<!-- Admin Validation Script -->
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
@endsection