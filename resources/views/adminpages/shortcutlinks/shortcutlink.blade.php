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
                            <i class="fas fa-link me-3 text-secondary"></i>
                            Shortcut Links Management
                        </h1>
                        <p class="mb-0 text-muted fs-6">Centralized management for all your website shortcuts and file resources</p>
                    </div>
                    <a href="{{ route('shortcut-links.create') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span class="fw-semibold">Create New Link</span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success border-0 rounded-3 shadow-sm d-flex align-items-center bg-white" role="alert">
                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <div class="flex-grow-1">
                        <strong class="text-success">Operation Successful</strong>
                        <div class="text-muted">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
    @endif

    <!-- Links Registry Section -->
    <div class="row">
        <div class="col-12">
            <div class="bg-white rounded-3 shadow-sm overflow-hidden border border-light">
                <!-- Registry Header -->
                <div class="bg-light border-bottom px-4 py-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-0 fw-semibold text-dark">
                                <i class="fas fa-database text-secondary me-2"></i>
                                Links Registry
                            </h5>
                            <small class="text-muted">Manage and monitor all system shortcuts</small>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-flex justify-content-end align-items-center gap-3">
                                <span class="badge bg-white text-dark border px-3 py-2 fs-6 shadow-sm">
                                    <i class="fas fa-chart-bar me-1 text-secondary"></i>
                                    {{ count($links) }} Records
                                </span>
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table -->
                @if(count($links) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 gov-table">
                            <thead class="bg-light">
                                <tr class="border-bottom">
                                    <th class="px-4 py-4 text-secondary text-uppercase fw-semibold border-end" style="font-size: 0.75rem; width: 8%;">
                                        <i class="fas fa-hashtag me-1"></i>ID
                                    </th>
                                    <th class="px-4 py-4 text-secondary text-uppercase fw-semibold border-end" style="font-size: 0.75rem; width: 25%;">
                                        <i class="fas fa-tag me-1"></i>Link Name
                                    </th>
                                    <th class="px-4 py-4 text-secondary text-uppercase fw-semibold border-end" style="font-size: 0.75rem; width: 35%;">
                                        <i class="fas fa-link me-1"></i>Resource
                                    </th>
                                    <th class="px-4 py-4 text-secondary text-uppercase fw-semibold border-end" style="font-size: 0.75rem; width: 15%;">
                                        <i class="fas fa-user me-1"></i>Created By
                                    </th>
                                    <th class="px-4 py-4 text-secondary text-uppercase fw-semibold text-center" style="font-size: 0.75rem; width: 17%;">
                                        <i class="fas fa-cogs me-1"></i>Management
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($links as $index => $link)
                                    <tr class="gov-row border-bottom">
                                        <td class="px-4 py-4 border-end align-middle">
                                            <span class="badge bg-light text-dark border px-3 py-2 fw-bold">
                                                #{{ str_pad($link->id, 3, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 border-end align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas {{ $link->is_file ? 'fa-file-alt' : 'fa-globe' }} text-secondary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark mb-1">{{ $link->link_name }}</div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-circle me-1" style="font-size: 6px;"></i>
                                                        {{ $link->is_file ? 'File Resource' : 'Web Link' }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 border-end align-middle">
                                            @if($link->is_file)
                                                <a href="{{ asset('images/storage/' . $link->link) }}" target="_blank" class="text-decoration-none text-dark d-flex align-items-center">
                                                    <i class="fas fa-download me-2 text-success"></i>
                                                    <div>
                                                        <span class="fw-medium">Download File</span>
                                                        <div class="small text-muted">{{ basename($link->link) }}</div>
                                                    </div>
                                                </a>
                                            @else
                                                <a href="{{ $link->link }}" target="_blank" class="text-decoration-none text-dark d-flex align-items-center">
                                                    <i class="fas fa-external-link-alt me-2 text-primary"></i>
                                                    <div>
                                                        <span class="fw-medium">{{ Str::limit($link->link, 30) }}</span>
                                                        <div class="small text-muted">External Website</div>
                                                    </div>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 border-end align-middle">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold text-dark small">{{ $link->user->username ?? 'System' }}</div>
                                                    <small class="text-muted">Administrator</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center align-middle">
                                            <div class="btn-group shadow-sm" role="group">
                                                <a href="{{ route('shortcut-links.show', $link->id) }}" class="btn btn-outline-secondary btn-sm border-end-0" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('shortcut-links.edit', $link->id) }}" class="btn btn-outline-secondary btn-sm border-end-0" title="Edit Link">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('shortcut-links.destroy', $link->id) }}" method="POST" style="display:inline;" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are you sure you want to delete this link? This action cannot be undone.')" 
                                                            class="btn btn-outline-danger btn-sm" 
                                                            title="Delete Link">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-folder-open text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted fw-semibold mb-3">No Shortcut Links Found</h5>
                        <p class="text-muted mb-4">Start by creating your first shortcut link to organize your government resources.</p>
                        <a href="{{ route('shortcut-links.create') }}" class="btn btn-dark px-4 py-2">
                            <i class="fas fa-plus-circle me-2"></i>
                            Create First Link
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Government Portal Styling */
body {
    background-color: #f8f9fa;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.container-fluid {
    background-color: #f8f9fa;
}

.gov-table {
    background-color: white;
}

.gov-row {
    transition: all 0.2s ease;
    background-color: white;
}

.gov-row:hover {
    background-color: #f8f9fa !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

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
}

.btn-outline-secondary:hover {
    background-color: #6c757d;
    border-color: #6c757d;
    color: white;
}

.btn-outline-primary {
    color: #0d6efd;
    border-color: #0d6efd;
}

.btn-outline-primary:hover {
    background-color: #0d6efd;
    border-color: #0d6efd;
    color: white;
}

.btn-outline-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.btn-outline-danger:hover {
    background-color: #dc3545;
    border-color: #dc3545;
    color: white;
}

.alert {
    border: 1px solid #d1e7dd;
    border-left: 4px solid #198754;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.rounded-3 {
    border-radius: 0.75rem !important;
}

.border-light {
    border-color: #e9ecef !important;
}

.text-secondary {
    color: #6c757d !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Professional Government Aesthetics */
.table th {
    background-color: #f8f9fa;
    border-color: #e9ecef;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.table td {
    border-color: #e9ecef;
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.3px;
}

.btn-group .btn {
    border-radius: 0;
}

.btn-group .btn:first-child {
    border-top-left-radius: 6px;
    border-bottom-left-radius: 6px;
}

.btn-group .btn:last-child {
    border-top-right-radius: 6px;
    border-bottom-right-radius: 6px;
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
    
    .btn-group {
        flex-direction: column;
        width: 100%;
    }
    
    .btn-group .btn {
        border-radius: 6px !important;
        margin-bottom: 0.25rem;
        width: 100%;
    }
    
    .btn-group .btn:last-child {
        margin-bottom: 0;
    }
    
    .gov-row:hover {
        transform: none;
        box-shadow: none;
    }
    
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .d-flex.justify-content-end {
        justify-content: center !important;
        flex-wrap: wrap;
    }
    
    .badge {
        margin-bottom: 0.5rem;
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
    
    .container-fluid {
        padding-left: 1rem !important;
        padding-right: 1rem !important;
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

/* Clean Table Borders */
.table {
    border-collapse: separate;
    border-spacing: 0;
}

.table thead th {
    position: sticky;
    top: 0;
    z-index: 10;
}

/* Print Styles */
@media print {
    .btn, .alert {
        display: none !important;
    }
    
    .table {
        border: 1px solid #000 !important;
    }
    
    .table th, .table td {
        border: 1px solid #000 !important;
        padding: 8px !important;
    }
}
</style>
@endsection