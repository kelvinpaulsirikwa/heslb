@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row">
        <div class="col-12">
            <!-- Header Section -->
            <div class="border-bottom pb-3 mb-4">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center">
                    <div class="mb-3 mb-lg-0">
                        <h2 class="h3 text-dark fw-semibold mb-1">Feedback Management</h2>
                        <p class="text-muted mb-0 small">New Feedback (Awaiting Review)</p>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('adminpages.feedback.seen') }}" 
                           class="btn btn-outline-dark btn-sm px-3">
                            <i class="fas fa-check-circle me-1"></i>
                            Reviewed Feedback
                        </a>
                        <a href="{{ route('adminpages.feedback.deleted') }}" 
                           class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-archive me-1"></i>
                            Archived Feedback
                        </a>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h6 class="card-title mb-0 text-dark fw-medium">
                        <i class="fas fa-filter me-2"></i>Filter Options
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-4 col-md-6">
                            <label for="contact_type" class="form-label text-dark fw-medium small">Description Type</label>
                            <select class="form-select form-select-sm border-secondary" 
                                    id="contact_type" 
                                    name="contact_type" 
                                    onchange="filterByType(this.value)">
                                <option value="">All Types</option>
                                <option value="suggestions" {{ request('type') == 'suggestions' ? 'selected' : '' }}>
                                    Suggestions
                                </option>
                                <option value="inquiries" {{ request('type') == 'inquiries' ? 'selected' : '' }}>
                                    Inquiries
                                </option>
                                <option value="success_stories" {{ request('type') == 'success_stories' ? 'selected' : '' }}>
                                    Success Stories
                                </option>
                                <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>
                                    Complaint
                                </option>
                                <option value="other" {{ request('type') == 'other' ? 'selected' : '' }}>
                                    Other
                                </option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-6 d-flex align-items-end">
                            <a href="{{ route('adminpages.feedback.index') }}" 
                               class="btn btn-outline-secondary btn-sm px-3">
                                <i class="fas fa-times me-1"></i>Clear Filters
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Feedbacks Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0 py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 text-dark fw-medium">
                            <i class="fas fa-users me-2"></i>Feedback Submissions
                        </h6>
                        <span class="badge bg-secondary">{{ $contacts->count() }} Records</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="table-light border-bottom">
                                <tr>
                                    <th class="px-4 py-3 text-dark fw-semibold small">ID</th>
                                    <th class="px-4 py-3 text-dark fw-semibold small"> Details</th>
                                    <th class="px-4 py-3 text-dark fw-semibold small d-none d-md-table-cell">Email Address</th>
                                    <th class="px-4 py-3 text-dark fw-semibold small d-none d-lg-table-cell">Phone Number</th>
                                    <th class="px-4 py-3 text-dark fw-semibold small">Status</th>
                                    <th class="px-4 py-3 text-dark fw-semibold small text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($contacts as $contact)
                                    <tr class="border-bottom">
                                        <td class="px-4 py-3">
                                            <span class="badge bg-light text-dark fw-medium">#{{ str_pad($contact->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div>
                                                <div class="fw-semibold text-dark mb-1">
                                                    {{ $contact->first_name }} {{ $contact->last_name }}
                                                </div>
                                                <div class="d-md-none">
                                                    <small class="text-muted d-block">{{ $contact->email }}</small>
                                                    <small class="text-muted d-block">{{ $contact->phone }}</small>
                                                </div>
                                                @if(isset($contact->contact_type))
                                                    <div class="mt-1">
                                                        <span class="badge bg-light text-dark small">
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
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 d-none d-md-table-cell">
                                            <span class="text-dark small">{{ $contact->email }}</span>
                                        </td>
                                        <td class="px-4 py-3 d-none d-lg-table-cell">
                                            <span class="text-dark small">{{ $contact->phone }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge bg-warning text-dark fw-medium">
                                                <i class="fas fa-clock me-1"></i>{{ ucfirst($contact->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('adminpages.feedback.show', $contact->id) }}" 
                                                   class="btn btn-outline-dark btn-sm"
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="d-none d-sm-inline ms-1">View</span>
                                                </a>
                                                <form action="{{ route('adminpages.feedback.destroy', $contact->id) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-outline-secondary btn-sm" 
                                                            title="Archive Contact"
                                                            onclick="return confirm('Are you sure you want to archive this contact? This action can be undone.')">
                                                        <i class="fas fa-archive"></i>
                                                        <span class="d-none d-sm-inline ms-1">Archive</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-5 text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <div class="mb-3">
                                                    <i class="fas fa-inbox fa-3x text-muted opacity-50"></i>
                                                </div>
                                                <h5 class="text-dark fw-medium mb-2">No New Contacts</h5>
                                                <p class="text-muted mb-0 small">
                                                    There are no pending contact submissions at this time.<br>
                                                    New submissions will appear here for review.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Pagination if needed -->
                @if(method_exists($contacts, 'links'))
                    <div class="card-footer bg-light border-0 py-3">
                        <div class="d-flex justify-content-center">
                            {{ $contacts->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function filterByType(type) {
        // Show loading state
        const selectElement = document.getElementById('contact_type');
        selectElement.disabled = true;
        
        if (type === '') {
            window.location.href = "{{ route('adminpages.feedback.index') }}";
        } else {
            window.location.href = "{{ route('feedback.byType', '') }}/" + type;
        }
    }

    // Add some polish with JavaScript
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to table rows
        const tableRows = document.querySelectorAll('tbody tr:not(.empty-state)');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = '#f8f9fa';
            });
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
            });
        });

        // Auto-dismiss alerts after 5 seconds
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>

<style>
    /* Additional styling for government theme */
    .table th {
        font-size: 0.875rem;
        letter-spacing: 0.025em;
        text-transform: uppercase;
    }
    
    .btn {
        font-weight: 500;
        letter-spacing: 0.025em;
    }
    
    .card {
        border-radius: 8px;
    }
    
    .badge {
        font-size: 0.75rem;
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
    }
    
    .table-hover tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    
    .btn-group .btn {
        border-radius: 4px !important;
    }
    
    .btn-group .btn:not(:last-child) {
        margin-right: 0.25rem;
    }
    
    /* Responsive improvements */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 1rem !important;
            padding-right: 1rem !important;
        }
        
        .btn-group {
            flex-direction: column;
            width: 100%;
        }
        
        .btn-group .btn {
            margin-bottom: 0.25rem;
            margin-right: 0;
        }
    }
    
    @media (max-width: 768px) {
        .card-header h6 {
            font-size: 0.875rem;
        }
        
        .table td, .table th {
            padding: 0.75rem 0.5rem;
        }
    }
</style>
@endsection