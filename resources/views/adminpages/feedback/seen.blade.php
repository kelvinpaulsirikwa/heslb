@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="border-bottom pb-3 mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center">
                    <div class="mb-3 mb-lg-0">
                        <h2 class="h3 text-dark fw-semibold mb-1">Reviewed Feedback</h2>
                        <p class="text-muted mb-0 small">
                            Manage feedback that have been reviewed and processed
                        </p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('adminpages.feedback.index') }}" 
                           class="btn btn-outline-dark btn-sm px-3">
                            <i class="fas fa-eye me-1"></i>
                            View Pending Feedback
                        </a>
                        <a href="{{ route('adminpages.feedback.deleted') }}" 
                           class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-archive me-1"></i>
                            View Archived Feedback
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

            <!-- Statistics Cards -->
          

            <!-- Contacts Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 text-dark fw-medium">
                            <i class="fas fa-list me-2"></i>Reviewed Contacts List
                        </h6>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-success fw-medium">
                                <i class="fas fa-check-circle me-1"></i>{{ $contacts->count() }} Total
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light border-bottom">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-hashtag me-1"></i>Reference
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-user me-1"></i>Contact Details
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-lg-table-cell">
                                        <i class="fas fa-envelope me-1"></i>Email Address
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-xl-table-cell">
                                        <i class="fas fa-phone me-1"></i>Phone Number
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-tag me-1"></i>Type
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-md-table-cell">
                                        <i class="fas fa-user-check me-1"></i>Reviewed By
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-lg-table-cell">
                                        <i class="fas fa-clock me-1"></i>Review Date
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-cogs me-1"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr class="clickable-row border-0" 
                                        style="cursor:pointer;" 
                                        onclick="window.location='{{ route('adminpages.feedback.show', $contact->id) }}'">
                                        <td class="px-4 py-3 align-middle border-0">
                                            <span class="badge bg-light text-dark fw-medium px-2 py-2">
                                                #{{ str_pad($contact->id, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0">
                                            <div>
                                                <div class="fw-semibold text-dark mb-1">
                                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-venus-mars me-1"></i>{{ ucfirst($contact->gender) }}
                                                </div>
                                                <!-- Mobile responsive info -->
                                                <div class="d-lg-none mt-2">
                                                    <div class="small text-muted">
                                                        <i class="fas fa-envelope me-1"></i>{{ $contact->email }}
                                                    </div>
                                                </div>
                                                <div class="d-xl-none d-lg-block mt-1">
                                                    <div class="small text-muted">
                                                        <i class="fas fa-phone me-1"></i>{{ $contact->phone }}
                                                    </div>
                                                </div>
                                                <div class="d-md-none mt-1">
                                                    <div class="small text-muted">
                                                        <i class="fas fa-user-check me-1"></i>{{ $contact->seenByUser ? $contact->seenByUser->username : 'System' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-lg-table-cell">
                                            <a href="mailto:{{ $contact->email }}" 
                                               class="text-decoration-none text-dark small" 
                                               onclick="event.stopPropagation()">
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-xl-table-cell">
                                            <a href="tel:{{ $contact->phone }}" 
                                               class="text-decoration-none text-dark small"
                                               onclick="event.stopPropagation()">
                                                {{ $contact->phone }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0">
                                            <span class="badge bg-light text-dark px-3 py-2">
                                                @switch($contact->contact_type)
                                                    @case('complaint')
                                                        <i class="fas fa-exclamation-triangle me-1 text-warning"></i>Complaint
                                                        @break
                                                    @case('inquiry')
                                                        <i class="fas fa-question-circle me-1 text-info"></i>Inquiry
                                                        @break
                                                    @case('feedback')
                                                        <i class="fas fa-comment me-1 text-primary"></i>Feedback
                                                        @break
                                                    @case('application')
                                                        <i class="fas fa-file-alt me-1 text-success"></i>Application
                                                        @break
                                                    @case('other')
                                                        <i class="fas fa-ellipsis-h me-1 text-secondary"></i>Other
                                                        @break
                                                    @default
                                                        <i class="fas fa-circle me-1"></i>{{ ucfirst($contact->contact_type) }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 24px; height: 24px;">
                                                    <i class="fas fa-user text-white" style="font-size: 10px;"></i>
                                                </div>
                                                <span class="small fw-medium text-dark">
                                                    {{ $contact->seenByUser ? $contact->seenByUser->username : 'System' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-lg-table-cell">
                                            <div class="small text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $contact->updated_at->format('M d, Y') }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $contact->updated_at->format('g:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('adminpages.feedback.show', $contact->id) }}" 
                                                   class="btn btn-outline-dark btn-sm px-3"
                                                   onclick="event.stopPropagation()"
                                                   title="View Details">
                                                    <i class="fas fa-eye d-sm-none"></i>
                                                    <span class="d-none d-sm-inline">View</span>
                                                </a>
                                                <form action="{{ route('adminpages.feedback.destroy', $contact->id) }}" 
                                                      method="POST" 
                                                      class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-secondary btn-sm px-3" 
                                                            onclick="event.stopPropagation(); return confirmArchive()"
                                                            title="Archive Contact">
                                                        <i class="fas fa-archive d-sm-none"></i>
                                                        <span class="d-none d-sm-inline">Archive</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-5 text-center border-0">
                                            <div class="d-flex flex-column align-items-center py-4">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" 
                                                     style="width: 80px; height: 80px;">
                                                    <i class="fas fa-eye-slash fa-2x text-muted"></i>
                                                </div>
                                                <h5 class="text-dark fw-semibold mb-2">No Reviewed Feedback Found</h5>
                                                <p class="text-muted mb-3">Feedback that have been reviewed and processed will appear here.</p>
                                                <a href="{{ route('adminpages.feedback.index') }}" 
                                                   class="btn btn-outline-dark px-4">
                                                    <i class="fas fa-eye me-2"></i>
                                                    View Pending Feedback
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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

    function exportToCSV() {
        // Basic CSV export functionality
        alert('CSV export functionality would be implemented here.');
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
                .btn, .card-footer, .alert, .d-none { display: none !important; }
                .card { border: 1px solid #dee2e6 !important; box-shadow: none !important; }
                .container-fluid { padding: 0 !important; }
                .card-header { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; }
                body { background: white !important; }
                .table th, .table td { border: 1px solid #dee2e6 !important; }
                .clickable-row { cursor: default !important; }
                .d-lg-table-cell, .d-xl-table-cell, .d-md-table-cell { display: table-cell !important; }
            </style>
        `;
        document.head.insertAdjacentHTML('beforeend', printStyles);

        // Add hover effect for table rows
        const tableRows = document.querySelectorAll('.clickable-row');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });
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
    
    .card-title {
        font-size: 0.95rem;
        letter-spacing: 0.025em;
    }
    
    /* Table styling improvements */
    .table th {
        font-size: 0.875rem;
        border-bottom: 2px solid #dee2e6 !important;
        background-color: #f8f9fa !important;
    }
    
    .table td {
        font-size: 0.875rem;
        vertical-align: middle;
    }
    
    .clickable-row {
        transition: background-color 0.15s ease-in-out;
    }
    
    .clickable-row:hover {
        background-color: #f8f9fa !important;
    }
    
    /* Statistics cards styling */
    .card-body .fa-2x {
        opacity: 0.8;
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
        
        .table th, .table td {
            padding: 0.75rem 0.5rem !important;
        }
    }
    
    @media (max-width: 768px) {
        .card-header h6 {
            font-size: 0.875rem;
        }
        
        .row.g-3 {
            --bs-gutter-y: 1rem;
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
        
        .table th, .table td {
            border: 1px solid #dee2e6 !important;
        }
        
        .clickable-row {
            cursor: default !important;
        }
        
        .d-lg-table-cell, .d-xl-table-cell, .d-md-table-cell {
            display: table-cell !important;
        }
    }
    
    /* Hover effects */
    .btn-outline-dark:hover {
        background-color: #212529;
        border-color: #212529;
    }
    
    .btn-outline-secondary:hover {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    /* Badge color variations */
    .badge.bg-light {
        color: #212529 !important;
        border: 1px solid #dee2e6;
    }
    
    /* Enhanced focus states */
    .btn:focus {
        box-shadow: 0 0 0 0.2rem rgba(33, 37, 41, 0.25);
    }
    
    .btn-outline-secondary:focus {
        box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
    }
    
    /* Statistics cards hover effect */
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        transition: all 0.15s ease-in-out;
    }
</style>
@endsection