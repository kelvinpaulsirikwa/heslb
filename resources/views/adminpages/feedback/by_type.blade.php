@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="border-bottom pb-3 mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center">
                    <div class="mb-3 mb-lg-0">
                        <h2 class="h3 text-dark fw-semibold mb-1">
                            Feedback by Type: 
                            <span class="badge bg-primary px-3 py-2 ms-2">
                                @switch($type)
                                    @case('suggestions')
                                        <i class="fas fa-lightbulb me-1"></i>Suggestions
                                        @break
                                    @case('inquiries')
                                        <i class="fas fa-question-circle me-1"></i>Inquiries
                                   
                                    @case('complaint')
                                        <i class="fas fa-exclamation-triangle me-1"></i>Complaint
                                        @break
                                    @case('other')
                                        <i class="fas fa-ellipsis-h me-1"></i>Other
                                        @break
                                    @default
                                        <i class="fas fa-circle me-1"></i>{{ ucfirst($type) }}
                                @endswitch
                            </span>
                        </h2>
                        <p class="text-muted mb-0 small">
                            Filter and manage contacts based on their submission type
                        </p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('adminpages.feedback.index') }}" 
                           class="btn btn-outline-dark btn-sm px-3">
                            <i class="fas fa-eye me-1"></i>
                            View Pending Feedback
                        </a>
                        <a href="{{ route('adminpages.feedback.seen') }}" 
                           class="btn btn-outline-success btn-sm px-3">
                            <i class="fas fa-check-circle me-1"></i>
                            View Reviewed Feedback
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

            <!-- Type Filter Navigation -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="card-title mb-0 text-dark fw-medium">
                        <i class="fas fa-filter me-2"></i>Feedback Type Filters
                    </h6>
                </div>
                <div class="card-body py-3">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('feedback.byType', 'suggestions') }}" 
                           class="btn {{ $type === 'suggestions' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm px-3">
                            <i class="fas fa-lightbulb me-1"></i>
                            Suggestions
                        </a>
                        <a href="{{ route('feedback.byType', 'inquiries') }}" 
                           class="btn {{ $type === 'inquiries' ? 'btn-info' : 'btn-outline-info' }} btn-sm px-3">
                            <i class="fas fa-question-circle me-1"></i>
                            Inquiries
                        </a>
                      
                        <a href="{{ route('feedback.byType', 'complaint') }}" 
                           class="btn {{ $type === 'complaint' ? 'btn-warning' : 'btn-outline-warning' }} btn-sm px-3">
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            Complaint
                        </a>
                        <a href="{{ route('feedback.byType', 'other') }}" 
                           class="btn {{ $type === 'other' ? 'btn-secondary' : 'btn-outline-secondary' }} btn-sm px-3">
                            <i class="fas fa-ellipsis-h me-1"></i>
                            Other
                        </a>
                    </div>
                </div>
            </div>

            

            <!-- Contacts Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 text-dark fw-medium">
                            <i class="fas fa-table me-2"></i>{{ ucfirst($type) }} Contacts List
                        </h6>
                        <div class="d-flex align-items-center gap-3">
                            <span class="badge bg-light text-dark fw-medium border">
                                <i class="fas fa-list me-1"></i>{{ $contacts->count() }} Total
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-header">
                                <tr>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-hashtag me-1"></i>Reference
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-user me-1"></i> Details
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-lg-table-cell">
                                        <i class="fas fa-envelope me-1"></i>Email Address
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-xl-table-cell">
                                        <i class="fas fa-phone me-1"></i>Phone Number
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-flag me-1"></i>Status
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-md-table-cell">
                                        <i class="fas fa-user-check me-1"></i>Reviewed By
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0 d-none d-lg-table-cell">
                                        <i class="fas fa-calendar me-1"></i>Received Date
                                    </th>
                                    <th class="px-4 py-3 fw-semibold text-dark border-0">
                                        <i class="fas fa-cogs me-1"></i>Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr class="clickable-row" 
                                        onclick="window.location='{{ route('adminpages.feedback.show', $contact->id) }}';">
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
                                                        <i class="fas fa-user-check me-1"></i>{{ $contact->seenByUser ? $contact->seenByUser->username : 'N/A' }}
                                                    </div>
                                                </div>
                                                <div class="d-lg-none mt-1">
                                                    <div class="small text-muted">
                                                        <i class="fas fa-calendar me-1"></i>{{ $contact->created_at->format('M d, Y') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-lg-table-cell">
                                            <a href="mailto:{{ $contact->email }}" 
                                               class="text-decoration-none text-dark small contact-link" 
                                               onclick="event.stopPropagation()">
                                                {{ $contact->email }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-xl-table-cell">
                                            <a href="tel:{{ $contact->phone }}" 
                                               class="text-decoration-none text-dark small contact-link"
                                               onclick="event.stopPropagation()">
                                                {{ $contact->phone }}
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0">
                                            @if($contact->status === 'seen')
                                                <span class="badge bg-success fw-medium status-badge">
                                                    <i class="fas fa-check-circle me-1"></i>Reviewed
                                                </span>
                                            @else
                                                <span class="badge bg-warning text-dark fw-medium status-badge">
                                                    <i class="fas fa-clock me-1"></i>Pending Review
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-md-table-cell">
                                            @if($contact->seenByUser)
                                                <div class="d-flex align-items-center">
                                                    <div class="user-avatar bg-success rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                    <span class="small fw-medium text-dark">
                                                        {{ $contact->seenByUser->username }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted small">
                                                    <i class="fas fa-minus-circle me-1"></i>Not Assigned
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0 d-none d-lg-table-cell">
                                            <div class="small text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ $contact->created_at->format('M d, Y') }}
                                            </div>
                                            <div class="small text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $contact->created_at->format('g:i A') }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 align-middle border-0">
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('adminpages.feedback.show', $contact->id) }}" 
                                                   class="btn btn-outline-dark btn-sm px-3 action-btn"
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
                                                            class="btn btn-outline-secondary btn-sm px-3 action-btn" 
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
                                            <div class="empty-state d-flex flex-column align-items-center py-4">
                                                <div class="empty-icon bg-light rounded-circle d-flex align-items-center justify-content-center mb-3">
                                                    @switch($type)
                                                        @case('complaint')
                                                            <i class="fas fa-exclamation-triangle fa-2x text-warning"></i>
                                                            @break
                                                        @case('inquiry')
                                                            <i class="fas fa-question-circle fa-2x text-info"></i>
                                                            @break
                                                        @case('feedback')
                                                            <i class="fas fa-comment fa-2x text-primary"></i>
                                                            @break
                                                        @case('application')
                                                            <i class="fas fa-file-alt fa-2x text-success"></i>
                                                            @break
                                                        @case('other')
                                                            <i class="fas fa-ellipsis-h fa-2x text-secondary"></i>
                                                            @break
                                                        @default
                                                            <i class="fas fa-inbox fa-2x text-muted"></i>
                                                    @endswitch
                                                </div>
                                                <h5 class="text-dark fw-semibold mb-2">No {{ ucfirst($type) }} Contacts Found</h5>
                                                <p class="text-muted mb-3">There are currently no contacts of this type in the system.</p>
                                                <div class="d-flex flex-column flex-sm-row gap-2">
                                                   
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary dropdown-toggle px-4" 
                                                                type="button" 
                                                                data-bs-toggle="dropdown">
                                                            <i class="fas fa-filter me-2"></i>
                                                            Other Types
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="{{ route('feedback.byType', 'suggestions') }}">
                                                                <i class="fas fa-lightbulb me-2 text-primary"></i>Suggestions
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="{{ route('feedback.byType', 'inquiries') }}">
                                                                <i class="fas fa-question-circle me-2 text-info"></i>Inquiries
                                                            </a></li>
                                                            
                                                            <li><a class="dropdown-item" href="{{ route('feedback.byType', 'complaint') }}">
                                                                <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Complaint
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="{{ route('feedback.byType', 'other') }}">
                                                                <i class="fas fa-ellipsis-h me-2 text-secondary"></i>Other
                                                            </a></li>
                                                        </ul>
                                                    </div>
                                                </div>
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

<style>
/* ========================================
   CORE COMPONENT STYLES
======================================== */

/* Card Components */
.card {
    border-radius: 8px;
    transition: box-shadow 0.15s ease-in-out;
}

.stats-card:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease-in-out;
}

/* Badge Styling */
.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
    border-radius: 4px;
    font-weight: 500;
}

.status-badge {
    font-size: 0.7rem;
    padding: 0.25rem 0.5rem;
}

/* Button Styling */
.btn {
    font-weight: 500;
    letter-spacing: 0.025em;
    border-radius: 4px;
    transition: all 0.15s ease-in-out;
}

.action-btn:hover {
    transform: translateY(-1px);
}

/* ========================================
   TABLE STYLES
======================================== */

.table-header {
    background-color: #f8f9fa !important;
}

.table th {
    font-size: 0.875rem;
    border-bottom: 2px solid #dee2e6 !important;
    font-weight: 600;
}

.table td {
    font-size: 0.875rem;
    vertical-align: middle;
    border-top: 1px solid #f1f3f4;
}

.clickable-row {
    cursor: pointer;
    transition: background-color 0.15s ease-in-out;
}

.clickable-row:hover {
    background-color: #f8f9fa !important;
}

.contact-link:hover {
    color: #0d6efd !important;
    text-decoration: underline !important;
}

/* User Avatar */
.user-avatar {
    width: 24px;
    height: 24px;
}

.user-avatar i {
    font-size: 10px;
}

/* ========================================
   EMPTY STATE STYLES
======================================== */

.empty-state {
    padding: 2rem 0;
}

.empty-icon {
    width: 80px;
    height: 80px;
}

/* ========================================
   RESPONSIVE DESIGN
======================================== */

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }
    
    .table th,
    .table td {
        padding: 0.75rem 0.5rem !important;
    }
    
    .badge.bg-primary {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .stats-card .card-body {
        padding: 1rem !important;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
}

@media (max-width: 576px) {
    .h3 {
        font-size: 1.25rem;
    }
    
    .card-header h6 {
        font-size: 0.875rem;
    }
    
    .btn {
        text-align: center;
        font-size: 0.825rem;
    }
    
    .table th {
        font-size: 0.8rem;
    }
    
    .table td {
        font-size: 0.8rem;
    }
}

/* ========================================
   PRINT STYLES
======================================== */

@media print {
    .btn,
    .card-footer,
    .alert,
    .dropdown {
        display: none !important;
    }
    
    .card {
        border: 1px solid #dee2e6 !important;
        box-shadow: none !important;
        break-inside: avoid;
        margin-bottom: 1rem;
    }
    
    .container-fluid {
        padding: 0 !important;
    }
    
    .card-header {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
    
    .table th,
    .table td {
        border: 1px solid #dee2e6 !important;
        font-size: 0.8rem !important;
    }
    
    .clickable-row {
        cursor: default !important;
    }
    
    .badge {
        border: 1px solid #ccc !important;
    }
    
    /* Show all responsive columns in print */
    .d-lg-table-cell,
    .d-xl-table-cell,
    .d-md-table-cell {
        display: table-cell !important;
    }
    
    .d-none {
        display: none !important;
    }
}

/* ========================================
   ACCESSIBILITY & FOCUS STYLES
======================================== */

.btn:focus,
.clickable-row:focus {
    outline: 2px solid #0d6efd;
    outline-offset: 2px;
}

/* Screen reader only content */
.sr-only {
    position: absolute;
    width: 1px;
    height: 1px;
    padding: 0;
    margin: -1px;
    overflow: hidden;
    clip: rect(0, 0, 0, 0);
    white-space: nowrap;
    border: 0;
}

/* ========================================
   ANIMATION & TRANSITIONS
======================================== */

.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Loading states */
.loading {
    opacity: 0.6;
    pointer-events: none;
}

.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #f3f3f3;
    border-top: 2px solid #0d6efd;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
// ========================================
// UTILITY FUNCTIONS
// ========================================

function confirmArchive() {
    return confirm('Are you sure you want to archive this contact?\n\nThis action will move the contact to the archived list. This action can be undone if needed.');
}

function exportToCSV() {
    const type = '{{ $type }}';
    const contacts = @json($contacts);

    if (!contacts || contacts.length === 0) {
        alert('No contacts available to export.');
        return;
    }
    }
    
    // CSV headers
    const headers = [
        'Reference ID',
        'First Name',
        'Last Name',
        'Gender',
        'Email',
        'Phone',
        'Type',
        'Status',
        'Reviewed By',
        'Received Date',
        'Received Time'
    ];
    
    // Convert contacts to CSV format
    const csvData = contacts.map(contact => [
        `#${contact.id.toString().padStart(4, '0')}`,
        contact.first_name || '',
        contact.last_name || '',
        contact.gender || '',
        contact.email || '',
        contact.phone || '',
        type,
        contact.status === 'seen' ? 'Reviewed' : 'Pending Review',
        contact.seen_by_user ? contact.seen_by_user.username : 'Not Assigned',
        new Date(contact.created_at).toLocaleDateString(),
        new Date(contact.created_at).toLocaleTimeString()
    ]);
    
    // Create CSV content
    const csvContent = [headers, ...csvData]
        .map(row => row.map(field => `"${field}"`).join(','))
        .join('\n');
    
    // Download CSV
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    const url = URL.createObjectURL(blob);
    link.setAttribute('href', url);
    link.setAttribute('download', `${type}_contacts_${new Date().toISOString().split('T')[0]}.csv`);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function showBulkReviewModal() {
    // Create modal dynamically
    const modal = document.createElement('div');
    modal.className = 'modal fade';
    modal.id = 'bulkReviewModal';
    modal.setAttribute('tabindex', '-1');
    modal.innerHTML = `
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-check-double me-2"></i>Bulk Review Contacts
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" onclick="processBulkReview()">
                        <i class="fas fa-check me-1"></i>Mark All as Reviewed
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(modal);
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();
    
    // Clean up modal after hiding
    modal.addEventListener('hidden.bs.modal', function() {
        document.body.removeChild(modal);
    });
}

function processBulkReview() {
    // In a real implementation, this would send an AJAX request
    alert('Bulk review functionality would send an AJAX request to mark all pending contacts as reviewed.');
    
    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('bulkReviewModal'));
    modal.hide();
}

// ========================================
// INITIALIZATION
// ========================================

document.addEventListener('DOMContentLoaded', function() {
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (typeof bootstrap !== 'undefined' && bootstrap.Alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    });

    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.style.animationDelay = `${index * 0.1}s`;
        card.classList.add('fade-in');
    });

    // Enhanced table row interactions
    const tableRows = document.querySelectorAll('.clickable-row');
    tableRows.forEach(row => {
        // Keyboard navigation
        row.setAttribute('tabindex', '0');
        row.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
        
        // Loading state for row clicks
        row.addEventListener('click', function() {
            this.classList.add('loading');
        });
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
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

    // Add tooltip functionality for action buttons
    const actionButtons = document.querySelectorAll('[title]');
    actionButtons.forEach(button => {
        if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
            new bootstrap.Tooltip(button);
        }
    });

    // Enhance filter buttons with active state management
    const filterButtons = document.querySelectorAll('.card-body .btn');
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Add loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading...';
            this.disabled = true;
            
            // Restore after a delay (in real app, this would be handled by page navigation)
            setTimeout(() => {
                this.innerHTML = originalText;
                this.disabled = false;
            }, 1000);
        });
    });
});

// ========================================
// UTILITY HELPERS
// ========================================

// Format numbers with commas
function formatNumber(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// Debounce function for search/filter inputs
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Show toast notifications
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
        ${message}
        <button type="button" class="btn-close" onclick="this.parentElement.remove()"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 5000);
}
</script>
@endsection