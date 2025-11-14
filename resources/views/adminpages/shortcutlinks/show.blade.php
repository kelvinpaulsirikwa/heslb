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
                            <i class="fas fa-eye me-3 text-secondary"></i>
                            Link Details
                        </h1>
                        <p class="mb-0 text-muted fs-6">View complete information about this shortcut link</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('shortcut-links.edit', $link->id) }}" class="btn btn-outline-primary btn-lg shadow-sm">
                            <i class="fas fa-edit me-2"></i>
                            <span class="fw-semibold">Edit Link</span>
                        </a>
                        <a href="{{ route('shortcut-links.index') }}" class="btn btn-dark btn-lg shadow-sm">
                            <i class="fas fa-arrow-left me-2"></i>
                            <span class="fw-semibold">Back to Registry</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Status Badge -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm border border-light p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                            <i class="fas {{ $link->is_file ? 'fa-file-alt' : 'fa-globe' }} text-secondary"></i>
                        </div>
                        <div>
                            <h6 class="mb-1 fw-semibold text-dark">{{ $link->link_name }}</h6>
                            <div class="text-muted">Active Government Resource</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $link->is_file ? 'success' : 'primary' }} px-3 py-2 fs-6">
                            <i class="fas fa-{{ $link->is_file ? 'file' : 'link' }} me-2"></i>
                            {{ $link->is_file ? 'File Resource' : 'External Link' }}
                        </span>
                        <div class="text-muted small mt-1">
                            ID: #{{ str_pad($link->id, 3, '0', STR_PAD_LEFT) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Link Details -->
    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm border border-light overflow-hidden">
                <!-- Card Header -->
                <div class="bg-light border-bottom px-4 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="mb-0 fw-semibold text-dark">
                                <i class="fas fa-info-circle text-secondary me-2"></i>
                                Complete Link Information
                            </h5>
                            <small class="text-muted">Detailed view of the shortcut link properties</small>
                        </div>
                     
                    </div>
                </div>

                <!-- Card Content -->
                <div class="p-5">
                    <div class="row g-4">
                        <!-- Link Name Section -->
                        <div class="col-12">
                            <div class="detail-item border-bottom pb-4 mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="icon-wrapper bg-light rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px;">
                                        <i class="fas fa-tag text-secondary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-2" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <i class="fas fa-bookmark me-1"></i>
                                            Link Identifier
                                        </h6>
                                        <h3 class="text-dark fw-bold mb-2">{{ $link->link_name }}</h3>
                                        <div class="text-muted">Official Government Resource Link</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Resource Access Section -->
                        <div class="col-12">
                            <div class="detail-item border-bottom pb-4 mb-4">
                                <div class="d-flex align-items-start">
                                    <div class="icon-wrapper bg-light rounded-circle d-flex align-items-center justify-content-center me-4" style="width: 60px; height: 60px;">
                                        <i class="fas fa-{{ $link->is_file ? 'file-download' : 'external-link-alt' }} text-secondary fs-4"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="text-muted text-uppercase fw-semibold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                            <i class="fas fa-{{ $link->is_file ? 'folder-open' : 'globe' }} me-1"></i>
                                            {{ $link->is_file ? 'File Resource Access' : 'Website Resource Access' }}
                                        </h6>
                                        
                                        @if($link->is_file)
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="bg-light rounded-3 p-3 border">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-file text-white"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-semibold text-dark">{{ basename($link->link) }}</div>
                                                                <small class="text-muted">Stored in secure government server</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <a href="{{ asset('images/storage/' . $link->link) }}" 
                                                           target="_blank" 
                                                           class="btn btn-success px-4">
                                                            <i class="fas fa-download me-2"></i>
                                                            Download
                                                        </a>
                                                        <a href="{{ asset('images/storage/' . $link->link) }}" 
                                                           target="_blank" 
                                                           class="btn btn-outline-secondary px-4">
                                                            <i class="fas fa-eye me-2"></i>
                                                            Preview
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="bg-light rounded-3 p-3 border">
                                                        <div class="d-flex align-items-center">
                                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                                <i class="fas fa-globe text-white"></i>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="fw-semibold text-dark word-break">{{ $link->link }}</div>
                                                                <small class="text-muted">External website resource</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 text-end">
                                                    <a href="{{ $link->link }}" 
                                                       target="_blank" 
                                                       class="btn btn-primary px-4">
                                                        <i class="fas fa-external-link-alt me-2"></i>
                                                        Visit Website
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- System Information -->
                        <div class="col-md-6">
                            <div class="detail-item bg-light rounded-3 p-4 h-100">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-user-shield me-1"></i>
                                    Created By
                                </h6>
                                <div class="d-flex align-items-center">
                                    <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold fs-5">{{ $link->user->username ?? 'System' }}</div>
                                        <small class="text-muted">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            System Administrator
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamp Information -->
                        <div class="col-md-6">
                            <div class="detail-item bg-light rounded-3 p-4 h-100">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-clock me-1"></i>
                                    Timeline
                                </h6>
                                <div class="timeline-info">
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-plus-circle text-success me-2"></i>
                                            <strong class="text-dark">Created:</strong>
                                        </div>
                                        <div class="text-muted ms-4">
                                            {{ $link->created_at->format('F j, Y \a\t g:i A') }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-edit text-primary me-2"></i>
                                            <strong class="text-dark">Last Modified:</strong>
                                        </div>
                                        <div class="text-muted ms-4">
                                            {{ $link->updated_at->format('F j, Y \a\t g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Details -->
                        <div class="col-12">
                            <div class="detail-item bg-light rounded-3 p-4">
                                <h6 class="text-muted text-uppercase fw-semibold mb-3" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-cog me-1"></i>
                                    Technical Information
                                </h6>
                                <div class="row">
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="tech-detail">
                                            <strong class="text-dark d-block mb-1">Record ID</strong>
                                            <span class="badge bg-secondary text-white px-3 py-2">
                                                #{{ str_pad($link->id, 3, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="tech-detail">
                                            <strong class="text-dark d-block mb-1">Resource Type</strong>
                                            <span class="text-muted">{{ $link->is_file ? 'File Upload' : 'Direct URL' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="tech-detail">
                                            <strong class="text-dark d-block mb-1">Status</strong>
                                            <span class="badge bg-success text-white px-2 py-1">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Active
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="tech-detail">
                                            <strong class="text-dark d-block mb-1">Last Access</strong>
                                            <span class="text-muted">{{ $link->updated_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if($link->is_file)
                                    <div class="row mt-3 pt-3 border-top">
                                        <div class="col-12">
                                            <strong class="text-dark d-block mb-2">File Properties</strong>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">File Name:</small>
                                                    <span class="text-dark">{{ basename($link->link) }}</span>
                                                </div>
                                                <div class="col-md-6">
                                                    <small class="text-muted d-block">Storage Path:</small>
                                                    <span class="text-dark font-monospace small">storage/{{ $link->link }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Management Actions Footer -->
                <div class="bg-light border-top px-4 py-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center text-muted">
                            <i class="fas fa-info-circle me-2"></i>
                            <span>Link created {{ $link->created_at->diffForHumans() }}</span>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('shortcut-links.edit', $link->id) }}" class="btn btn-outline-primary px-4">
                                <i class="fas fa-edit me-2"></i>
                                Edit Link
                            </a>
                            <button type="button" class="btn btn-outline-secondary px-4" onclick="copyToClipboard()">
                                <i class="fas fa-copy me-2"></i>
                                Copy Details
                            </button>
                            <form action="{{ route('shortcut-links.destroy', $link->id) }}" method="POST" style="display:inline;" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Are you sure you want to delete this link? This action cannot be undone.')" 
                                        class="btn btn-outline-danger px-4">
                                    <i class="fas fa-trash me-2"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm border border-light p-4">
                <h6 class="fw-semibold text-dark mb-3">
                    <i class="fas fa-bolt text-secondary me-2"></i>
                    Quick Actions
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('shortcut-links.create') }}" class="btn btn-outline-dark w-100 text-start">
                            <i class="fas fa-plus-circle me-2"></i>
                            Create New Link
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        <a href="{{ route('shortcut-links.index') }}" class="btn btn-outline-dark w-100 text-start">
                            <i class="fas fa-list me-2"></i>
                            View All Links
                        </a>
                    </div>
                    <div class="col-md-4 mb-2">
                        @if($link->is_file)
                            <a href="{{ asset('images/storage/' . $link->link) }}" target="_blank" class="btn btn-outline-success w-100 text-start">
                                <i class="fas fa-download me-2"></i>
                                Access Resource
                            </a>
                        @else
                            <a href="{{ $link->link }}" target="_blank" class="btn btn-outline-primary w-100 text-start">
                                <i class="fas fa-external-link-alt me-2"></i>
                                Access Resource
                            </a>
                        @endif
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

/* Detail Item Styling */
.detail-item {
    transition: all 0.2s ease;
}

.icon-wrapper {
    flex-shrink: 0;
}

.tech-detail {
    padding: 0.75rem;
    border-radius: 6px;
    background-color: white;
    border: 1px solid #e9ecef;
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

.btn-outline-dark {
    color: #212529;
    border-color: #212529;
    background-color: white;
}

.btn-outline-dark:hover {
    background-color: #212529;
    border-color: #212529;
    color: white;
}

.btn-outline-success {
    color: #198754;
    border-color: #198754;
    background-color: white;
}

.btn-outline-success:hover {
    background-color: #198754;
    border-color: #198754;
    color: white;
}

/* Utility Classes */
.word-break {
    word-break: break-all;
    word-wrap: break-word;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.border-light {
    border-color: #e9ecef !important;
}

.rounded-3 {
    border-radius: 0.75rem !important;
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Timeline Styling */
.timeline-info {
    position: relative;
}

.timeline-info::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 25px;
    bottom: 25px;
    width: 2px;
    background-color: #e9ecef;
}

/* Professional Copy Animation */
.copy-success {
    animation: copyFeedback 0.3s ease;
}

@keyframes copyFeedback {
    0% { transform: scale(1); }
    50% { transform: scale(1.05); }
    100% { transform: scale(1); }
}

/* Responsive Design */
@media (max-width: 768px) {
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1.5rem;
        text-align: center;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .btn-lg {
        width: 100%;
        padding: 1rem;
    }
    
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .icon-wrapper {
        width: 45px !important;
        height: 45px !important;
    }
    
    .fs-4 {
        font-size: 1.1rem !important;
    }
    
    .row.align-items-center {
        flex-direction: column;
        gap: 1rem;
    }
    
    .col-md-8, .col-md-4 {
        width: 100%;
        text-align: center;
    }
    
    .justify-content-end {
        justify-content: center !important;
    }
    
    .text-end {
        text-align: center !important;
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
    
    .flex-wrap.gap-3 {
        flex-direction: column !important;
        gap: 0.75rem !important;
    }
}

/* Accessibility Improvements */
.btn:focus {
    box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
}

.btn-outline-primary:focus {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.btn-outline-danger:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
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
    
    .bg-light {
        background: #f8f9fa !important;
        border: 1px solid #dee2e6 !important;
    }
    
    .border {
        border: 1px solid #dee2e6 !important;
    }
}
</style>

<div id="link-data" data-is-file="{{ $link->is_file ? 'true' : 'false' }}" data-link="{{ $link->link }}" style="display: none;"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Copy to clipboard functionality
    window.copyToClipboard = function() {
        const linkName = "{{ $link->link_name }}";
        const linkUrl = "{{ $link->is_file ? asset('images/storage/' . $link->link) : $link->link }}";
        const linkType = "{{ $link->is_file ? 'File Resource' : 'External Link' }}";
        const createdBy = "{{ $link->user->username ?? 'System' }}";
        const createdAt = "{{ $link->created_at->format('F j, Y \a\t g:i A') }}";
        
        const copyText = `Government Link Details:
Name: ${linkName}
Type: ${linkType}
URL: ${linkUrl}
Created By: ${createdBy}
Created: ${createdAt}`;

        navigator.clipboard.writeText(copyText).then(function() {
            // Success feedback
            const copyBtn = document.querySelector('button[onclick="copyToClipboard()"]');
            const originalText = copyBtn.innerHTML;
            copyBtn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';
            copyBtn.classList.add('copy-success', 'btn-success');
            copyBtn.classList.remove('btn-outline-secondary');
            
            setTimeout(() => {
                copyBtn.innerHTML = originalText;
                copyBtn.classList.remove('copy-success', 'btn-success');
                copyBtn.classList.add('btn-outline-secondary');
            }, 2000);
        }).catch(function(err) {
            console.error('Could not copy text: ', err);
            alert('Copy to clipboard failed. Please try again.');
        });
    };

    // Enhanced delete confirmation
    const deleteForm = document.querySelector('form[action*="destroy"]');
    if (deleteForm) {
        deleteForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (confirm('⚠️ CONFIRMATION REQUIRED\n\nAre you sure you want to permanently delete this government link?\n\n"{{ $link->link_name }}"\n\nThis action cannot be undone and will remove the link from all government systems.')) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Deleting...';
                submitBtn.disabled = true;
                
                this.submit();
            }
        });
    }

    // Link accessibility testing
    const linkData = document.getElementById('link-data');
    const isFile = linkData.dataset.isFile === 'true';
    if (!isFile) {
        // Test external link accessibility (non-blocking)
        const testLink = linkData.dataset.link;
        const linkStatus = document.createElement('span');
        linkStatus.className = 'badge bg-light text-muted border ms-2';
        linkStatus.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Testing...';
        
        // Add status badge to the URL display
        const urlDisplay = document.querySelector('.word-break');
        if (urlDisplay && urlDisplay.parentNode) {
            urlDisplay.parentNode.appendChild(linkStatus);
            
            // Simple link test (this would typically be done server-side)
            setTimeout(() => {
                linkStatus.innerHTML = '<i class="fas fa-globe me-1"></i>External';
                linkStatus.className = 'badge bg-light text-muted border ms-2';
            }, 1500);
        }
    }

    // Print functionality
    window.printLinkDetails = function() {
        window.print();
    };

    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Ctrl+E for edit
        if (e.ctrlKey && e.key === 'e') {
            e.preventDefault();
            window.location.href = "{{ route('shortcut-links.edit', $link->id) }}";
        }
        
        // Ctrl+B for back
        if (e.ctrlKey && e.key === 'b') {
            e.preventDefault();
            window.location.href = "{{ route('shortcut-links.index') }}";
        }
        
        // Ctrl+C for copy (when copy button is focused)
        if (e.ctrlKey && e.key === 'c' && document.activeElement.getAttribute('onclick') === 'copyToClipboard()') {
            e.preventDefault();
            copyToClipboard();
        }
    });

    // Add tooltip for keyboard shortcuts
    const editBtn = document.querySelector('a[href*="edit"]');
    const backBtn = document.querySelector('a[href*="index"]');
    
    if (editBtn) {
        editBtn.setAttribute('title', 'Edit Link (Ctrl+E)');
    }
    if (backBtn) {
        backBtn.setAttribute('title', 'Back to Registry (Ctrl+B)');
    }

    // Enhanced accessibility
    const actionButtons = document.querySelectorAll('.btn');
    actionButtons.forEach(btn => {
        btn.addEventListener('focus', function() {
            this.style.outline = '2px solid #0d6efd';
            this.style.outlineOffset = '2px';
        });
        
        btn.addEventListener('blur', function() {
            this.style.outline = 'none';
        });
    });

    // Auto-refresh timestamp display
    function updateTimestamp() {
        const timestampElements = document.querySelectorAll('[data-timestamp]');
        timestampElements.forEach(el => {
            const timestamp = el.getAttribute('data-timestamp');
            if (timestamp) {
                el.textContent = moment(timestamp).fromNow();
            }
        });
    }

    // Update every minute
    setInterval(updateTimestamp, 60000);
});
</script>
@endsection