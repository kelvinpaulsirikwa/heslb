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
                            <i class="fas fa-plus-circle me-3 text-secondary"></i>
                            Create Shortcut Link
                        </h1>
                        <p class="mb-0 text-muted fs-6">Add a new shortcut link or file resource to the government portal</p>
                    </div>
                    <a href="{{ route('shortcut-links.index') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back to Registry</span>
                    </a>
                </div>
            </div>
        </div>
    </div>



    <!-- Create Form -->
    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm border border-light overflow-hidden">
                <!-- Form Header -->
                <div class="bg-light border-bottom px-4 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0 fw-semibold text-dark">
                                <i class="fas fa-form text-secondary me-2"></i>
                                New Link Registration
                            </h5>
                            <small class="text-muted">Complete the form below to add a new shortcut</small>
                        </div>
                      
                    </div>
                </div>

                <!-- Form Content -->
                <div class="p-5">
                    <x-admin-validation-errors />
                    
                    <form id="shortcutLinksForm" action="{{ route('shortcut-links.store') }}" method="POST" enctype="multipart/form-data" class="gov-form" data-admin-validation="shortcutLinks">
                        @csrf

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
                                   value="{{ old('link_name') }}"
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
                                               {{ old('link_type', 'link') == 'link' ? 'checked' : '' }}>
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
                                               {{ old('link_type') == 'file' ? 'checked' : '' }}>
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
                                       value="{{ old('link') }}"
                                       class="form-control border-2 border-start-0 gov-input"
                                       placeholder="https://example.gov.tz"
                                       required>
                            </div>
                            <div class="form-text text-muted mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Enter a valid URL starting with http:// or https://
                            </div>
                        </div>

                        <!-- File Input Container -->
                        <div class="mb-5 d-none" id="file_input_container">
                            <label for="file_input" class="form-label fw-semibold text-dark mb-3">
                                <i class="fas fa-cloud-upload-alt text-secondary me-2"></i>
                                Upload File
                                <span class="text-danger">*</span>
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
                                Supported formats: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG (Maximum size: 10MB)
                            </div>
                            
                            <!-- File Preview Area -->
                            <div class="mt-3 p-4 bg-light rounded-3 border d-none" id="file_preview">
                                <div class="d-flex align-items-center">
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="fas fa-file text-white"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <small class="text-muted text-uppercase fw-semibold">Selected File</small>
                                        <div class="fw-semibold text-dark" id="file_name"></div>
                                        <div class="text-muted small" id="file_size"></div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearFile()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-12">
                                <div class="bg-light rounded-3 p-4 border">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <a href="{{ route('shortcut-links.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                            <i class="fas fa-times me-2"></i>
                                            Cancel
                                        </a>
                                        <button type="submit" class="btn btn-dark btn-lg px-5" id="submit_btn">
                                            <i class="fas fa-plus-circle me-2"></i>
                                            Create Link
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

    <!-- Instructions Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm border border-light p-4">
                <h6 class="fw-semibold text-dark mb-3">
                    <i class="fas fa-lightbulb text-secondary me-2"></i>
                    Guidelines for Government Links
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-dark d-block mb-1">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                External Links
                            </strong>
                            <small class="text-muted">Use for official government websites, policy documents, or approved external resources</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong class="text-dark d-block mb-1">
                                <i class="fas fa-check-circle text-success me-1"></i>
                                File Resources
                            </strong>
                            <small class="text-muted">Upload official documents, forms, reports, or reference materials</small>
                        </div>
                    </div>
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

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
    background-color: white;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
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

/* File Preview Styling */
#file_preview {
    border: 2px dashed #28a745;
    background: linear-gradient(to right, #f8fff9 0%, white 100%);
}

.form-control.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.15);
}

/* Loading State */
.btn-loading {
    position: relative;
    pointer-events: none;
}

.btn-loading::after {
    content: "";
    position: absolute;
    width: 16px;
    height: 16px;
    margin: auto;
    border: 2px solid transparent;
    border-top-color: #ffffff;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
    const filePreview = document.getElementById('file_preview');
    const fileName = document.getElementById('file_name');
    const fileSize = document.getElementById('file_size');

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
            fileInput.required = true;
            
            // Update label text
            document.querySelector('label[for="file_input"]').innerHTML = `
                <i class="fas fa-cloud-upload-alt text-secondary me-2"></i>
                Upload File
                <span class="text-danger">*</span>
            `;
        }
    }

    // Event listeners
    linkRadio.addEventListener('change', toggleInputs);
    fileRadio.addEventListener('change', toggleInputs);

    // File input handling
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Validate file size (10MB)
            const maxSize = 10 * 1024 * 1024;
            if (file.size > maxSize) {
                alert('File size must be less than 10MB');
                this.value = '';
                filePreview.classList.add('d-none');
                return;
            }

            // Validate file type
            const allowedTypes = [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'image/jpeg',
                'image/png'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Please select a valid file type (PDF, DOC, DOCX, XLS, XLSX, JPG, PNG)');
                this.value = '';
                filePreview.classList.add('d-none');
                return;
            }

            // Show file preview
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.classList.remove('d-none');
        } else {
            filePreview.classList.add('d-none');
        }
    });

    // Form submission handling
    const form = document.querySelector('.gov-form');
    const submitBtn = document.getElementById('submit_btn');
    
    form.addEventListener('submit', function(e) {
        const linkType = document.querySelector('input[name="link_type"]:checked').value;
        
        // Validate based on link type
        if (linkType === 'link') {
            const urlValue = linkInput.value.trim();
            if (!urlValue) {
                e.preventDefault();
                linkInput.focus();
                linkInput.classList.add('is-invalid');
                return false;
            }
        } else if (linkType === 'file') {
            const fileValue = fileInput.files[0];
            if (!fileValue) {
                e.preventDefault();
                fileInput.focus();
                fileInput.classList.add('is-invalid');
                alert('Please select a file to upload');
                return false;
            }
        }
        
        // Add loading state to submit button
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating Link...';
        submitBtn.disabled = true;
        submitBtn.classList.add('btn-loading');
        
        // Re-enable after 10 seconds as fallback
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
            submitBtn.classList.remove('btn-loading');
        }, 10000);
    });

    // Remove invalid class on input
    linkInput.addEventListener('input', function() {
        this.classList.remove('is-invalid');
    });

    fileInput.addEventListener('change', function() {
        this.classList.remove('is-invalid');
    });

    // Initialize on page load
    toggleInputs();

    // Clear file function
    window.clearFile = function() {
        fileInput.value = '';
        filePreview.classList.add('d-none');
    };

    // Format file size helper
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    // Auto-save form data to prevent loss
    const formInputs = form.querySelectorAll('input[type="text"], input[type="url"], textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            const key = `gov_form_${this.name}`;
            localStorage.setItem(key, this.value);
        });

        // Restore saved data
        const key = `gov_form_${input.name}`;
        const savedValue = localStorage.getItem(key);
        if (savedValue && !input.value) {
            input.value = savedValue;
        }
    });

    // Clear saved data on successful submission
    form.addEventListener('submit', function() {
        formInputs.forEach(input => {
            const key = `gov_form_${input.name}`;
            localStorage.removeItem(key);
        });
    });
});
</script>

<!-- Admin Validation Script -->
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
@endsection