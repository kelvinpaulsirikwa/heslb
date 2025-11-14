@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="border-bottom pb-3 mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center">
                    <div class="mb-3 mb-lg-0">
                        <h2 class="h3 text-dark fw-semibold mb-1">Feedback Details</h2>
                        <p class="text-muted mb-0 small">
                            Feedback Reference: #{{ str_pad($contact->id, 4, '0', STR_PAD_LEFT) }}
                        </p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('adminpages.feedback.index') }}" 
                           class="btn btn-outline-dark btn-sm px-3">
                            <i class="fas fa-arrow-left me-1"></i>
                            Back to Contacts
                        </a>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Contact Information Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 text-dark fw-medium">
                            <i class="fas fa-user me-2"></i>Contact Information
                        </h6>
                        <div class="d-flex align-items-center gap-2">
                            @if($contact->status === 'seen')
                                <span class="badge bg-success fw-medium">
                                    <i class="fas fa-check-circle me-1"></i>Reviewed
                                </span>
                            @else
                                <span class="badge bg-warning text-dark fw-medium">
                                    <i class="fas fa-clock me-1"></i>Pending Review
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Personal Information -->
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-semibold mb-3 border-bottom pb-2">
                                <i class="fas fa-id-card me-2"></i>Personal Information
                            </h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="form-label text-muted small fw-medium">Full Name</label>
                                    <p class="text-dark mb-0 fw-medium">{{ $contact->first_name }} {{ $contact->last_name }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label text-muted small fw-medium">Gender</label>
                                    <p class="text-dark mb-0">{{ ucfirst($contact->gender) }}</p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label text-muted small fw-medium">Email Address</label>
                                    <p class="text-dark mb-0">
                                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none text-dark">
                                            {{ $contact->email }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-sm-6">
                                    <label class="form-label text-muted small fw-medium">Phone Number</label>
                                    <p class="text-dark mb-0">
                                        <a href="tel:{{ $contact->phone }}" class="text-decoration-none text-dark">
                                            {{ $contact->phone }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Type & Additional Info -->
                        <div class="col-lg-6">
                            <h6 class="text-dark fw-semibold mb-3 border-bottom pb-2">
                                <i class="fas fa-info-circle me-2"></i>Feeback Details
                            </h6>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-medium">Description Type</label>
                                    <p class="text-dark mb-0">
                                        <span class="badge bg-light text-dark px-3 py-2">
                                            @switch($contact->contact_type)
                                                @case('suggestions')
                                                    <i class="fas fa-lightbulb me-1"></i>Suggestions
                                                    @break
                                                @case('inquiries')
                                                    <i class="fas fa-question-circle me-1"></i>Inquiries
                                                    @break
                                                @case('success_stories')
                                                    <i class="fas fa-star me-1"></i>Success Stories
                                                    @break
                                                @case('complaint')
                                                    <i class="fas fa-exclamation-triangle me-1"></i>Complaint
                                                    @break
                                                @case('other')
                                                    <i class="fas fa-ellipsis-h me-1"></i>Other
                                                    @break
                                                @default
                                                    {{ ucfirst($contact->contact_type) }}
                                            @endswitch
                                        </span>
                                    </p>
                                </div>
                                @if($contact->date_of_incident)
                                    <div class="col-sm-6">
                                        <label class="form-label text-muted small fw-medium">Date of Incident</label>
                                        <p class="text-dark mb-0">
                                            <i class="fas fa-calendar me-1"></i>
                                            {{ \Carbon\Carbon::parse($contact->date_of_incident)->format('M d, Y') }}
                                        </p>
                                    </div>
                                @endif
                                @if($contact->location)
                                    <div class="col-sm-6">
                                        <label class="form-label text-muted small fw-medium">Location</label>
                                        <p class="text-dark mb-0">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $contact->location }}
                                        </p>
                                    </div>
                                @endif
                                <div class="col-12">
                                    <label class="form-label text-muted small fw-medium">Data Consent</label>
                                    <p class="text-dark mb-0">
                                        @if($contact->consent)
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Consent Provided
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times me-1"></i>No Consent
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="card-title mb-0 text-dark fw-medium">
                        <i class="fas fa-envelope me-2"></i>Message Content
                    </h6>
                </div>
                <div class="card-body">
                    <div class="bg-light p-4 rounded">
                        <p class="text-dark mb-0 lh-lg">{{ $contact->message }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="card-title mb-0 text-dark fw-medium">
                        <i class="fas fa-cogs me-2"></i>Administrative Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-between">
                        <div class="d-flex flex-column flex-sm-row gap-2">
                            <a href="{{ route('adminpages.feedback.index') }}" 
                               class="btn btn-outline-dark px-4">
                                <i class="fas fa-arrow-left me-2"></i>
                                Return to Contact List
                            </a>
                            
                            @if($contact->status !== 'seen')
                                <form action="{{ route('adminpages.feedback.markAsSeen', $contact->id) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-outline-success px-4">
                                        <i class="fas fa-check me-2"></i>
                                        Mark as Reviewed
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <div class="d-flex flex-column flex-sm-row gap-2">
                           
                            
                            <a href="{{ route('adminpages.feedback.print', $contact->id) }}" 
                               class="btn btn-outline-info px-4"
                               target="_blank">
                                <i class="fas fa-file-pdf me-2"></i>
                                Print View
                            </a>
                            
                            <form action="{{ route('adminpages.feedback.destroy', $contact->id) }}" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirmArchive()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-secondary px-4">
                                    <i class="fas fa-archive me-2"></i>
                                    Archive Contact
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit Trail (if needed) -->
            <div class="mt-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0 py-3">
                        <h6 class="card-title mb-0 text-dark fw-medium">
                            <i class="fas fa-history me-2"></i>Record Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-medium">Submitted On</label>
                                <p class="text-dark mb-0">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $contact->created_at->format('F d, Y \a\t g:i A') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-medium">Last Updated</label>
                                <p class="text-dark mb-0">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $contact->updated_at->format('F d, Y \a\t g:i A') }}
                                </p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small fw-medium">Current Status</label>
                                <p class="text-dark mb-0">
                                    @if($contact->status === 'seen')
                                        <span class="badge bg-success fw-medium">
                                            <i class="fas fa-check-circle me-1"></i>Reviewed
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark fw-medium">
                                            <i class="fas fa-clock me-1"></i>Pending Review
                                        </span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmArchive() {
        return confirm('Are you sure you want to archive this contact?\n\nThis action will move the contact to the archived list. This action can be undone if needed.');
    }

    function printFeedback() {
        // Get the current user info for watermark
        const userName = '{{ Auth::user()->name ?? "Unknown User" }}';
        const userEmail = '{{ Auth::user()->email ?? "" }}';
        const printTime = new Date().toLocaleString();
        const logoPath = '{{ asset("images/static_files/logoandcoatofarm.jpg") }}';
        
        // Hide sidebar, navigation, and non-essential elements
        const elementsToHide = document.querySelectorAll(
            '.sidebar, .navbar, .btn, .alert, .card-footer, .border-bottom, ' +
            '.admin-sidebar, .main-sidebar, .sidebar-wrapper, .navbar-nav, ' +
            '.breadcrumb, .page-header, .sidebar-menu, .sidebar-dark-primary, ' +
            '.main-header, .content-header, .navbar-expand, .nav, ' +
            '[class*="sidebar"], [class*="navbar"], [class*="nav-"]'
        );
        
        elementsToHide.forEach(el => {
            el.style.display = 'none';
        });

        // Create print container with only feedback content
        const printContainer = document.createElement('div');
        printContainer.className = 'print-only-container';
        printContainer.innerHTML = `
            <!-- Print Header with Logo Only -->
            <div class="print-header" style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #000; padding-bottom: 15px;">
                <img src="${logoPath}" 
                     alt="HESLB Logo and Coat of Arms" 
                     style="width: 100%; height: auto; display: block; margin: 0; padding: 0;">
            </div>
            
            <!-- Watermark -->
            <div class="print-watermark" style="
                position: fixed; 
                top: 50%; 
                left: 50%; 
                transform: translate(-50%, -50%) rotate(-45deg);
                font-size: 80px; 
                color: rgba(0,0,0,0.05); 
                z-index: -1; 
                pointer-events: none;
                font-weight: bold;
                white-space: nowrap;
            ">HESLB CONFIDENTIAL</div>
            
            <!-- User Watermark -->
            <div class="user-watermark" style="
                position: fixed; 
                bottom: 20px; 
                right: 20px; 
                font-size: 10px; 
                color: rgba(0,0,0,0.7); 
                background: rgba(255,255,255,0.8);
                padding: 5px 10px;
                border-radius: 3px;
                border: 1px solid #ddd;
            ">
                Printed by: ${userName}<br>
                ${userEmail}<br>
                ${printTime}
            </div>
            
            <!-- Copy feedback content -->
            ${document.querySelector('.container-fluid').innerHTML}
        `;
        
        // Create temporary body for printing
        const originalBody = document.body.innerHTML;
        document.body.innerHTML = printContainer.innerHTML;

        // Trigger print
        window.print();

        // Restore original content
        setTimeout(() => {
            document.body.innerHTML = originalBody;
            // Re-bind event listeners if needed
            location.reload();
        }, 1000);
    }

    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                if (bootstrap && bootstrap.Alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        });

        // Add print-specific styling
        const printStyles = `
            <style media="print">
                /* Hide all navigation and sidebar elements */
                .sidebar, .navbar, .btn, .alert, .card-footer, .border-bottom,
                .admin-sidebar, .main-sidebar, .sidebar-wrapper, .navbar-nav,
                .breadcrumb, .page-header, .sidebar-menu, .sidebar-dark-primary,
                .main-header, .content-header, .navbar-expand, .nav,
                [class*="sidebar"], [class*="navbar"], [class*="nav-"],
                .main-sidebar, .navbar-light, .navbar-dark, 
                .sidebar-dark-primary, .layout-fixed { 
                    display: none !important; 
                    visibility: hidden !important;
                }
                
                /* Print layout */
                body { 
                    background: white !important; 
                    font-size: 12px; 
                    margin: 0 !important;
                    padding: 0 !important;
                }
                
                .print-only-container {
                    width: 100% !important;
                    max-width: 100% !important;
                    margin: 0 !important;
                    padding: 20px !important;
                }
                
                .card { 
                    border: 1px solid #dee2e6 !important; 
                    box-shadow: none !important; 
                    margin-bottom: 15px !important; 
                    break-inside: avoid;
                }
                
                .container-fluid { 
                    padding: 0 !important; 
                    max-width: 100% !important; 
                    margin: 0 !important;
                }
                
                .card-header { 
                    background-color: #f8f9fa !important; 
                    -webkit-print-color-adjust: exact; 
                }
                
                .print-header { 
                    page-break-after: avoid; 
                    margin-bottom: 20px !important;
                    text-align: center !important;
                }
                
                .print-header img {
                    width: 100% !important;
                    height: auto !important;
                    display: block !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
                
                h1, h2, h3, h4, h5, h6 { 
                    color: #000 !important; 
                    page-break-after: avoid;
                }
                
                .badge { 
                    background-color: #e9ecef !important; 
                    color: #000 !important; 
                    border: 1px solid #000 !important; 
                }
                
                /* Watermark styles */
                .print-watermark {
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
                
                .user-watermark {
                    -webkit-print-color-adjust: exact !important;
                    color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    background: rgba(255,255,255,0.9) !important;
                    border: 1px solid #333 !important;
                }
                
                @page { 
                    margin: 1in; 
                    size: A4; 
                }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', printStyles);
    });
</script>

<style>
    /* Professional styling matching government theme */
    .card {
        border-radius: 8px;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .btn {
        font-weight: 500;
        letter-spacing: 0.025em;
        border-radius: 4px;
    }
    
    .form-label {
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }
    
    .card-title {
        font-size: 0.95rem;
        letter-spacing: 0.025em;
    }
    
    /* Message content styling */
    .bg-light p {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
    
    /* Responsive improvements */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        .d-flex.gap-2 {
            flex-direction: column;
        }
        
        .btn {
            text-align: center;
        }
    }
    
    @media (max-width: 768px) {
        .card-header h6 {
            font-size: 0.875rem;
        }
        
        .row.g-4 {
            --bs-gutter-y: 1.5rem;
        }
    }
    
    /* Print styles */
    @media print {
        .btn, .card-footer, .alert {
            display: none !important;
        }
        
        .card {
            border: 1px solid #dee2e6 !important;
            box-shadow: none !important;
            break-inside: avoid;
        }
        
        .container-fluid {
            padding: 0 !important;
        }
        
        .card-header {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
        }
        
        body {
            background: white !important;
        }
        
        h2, h6 {
            color: #000 !important;
        }
    }
    
    /* Hover effects */
    .btn-outline-dark:hover {
        background-color: #212529;
        border-color: #212529;
    }
    
    .btn-outline-success:hover {
        background-color: #198754;
        border-color: #198754;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }
</style>
@endsection