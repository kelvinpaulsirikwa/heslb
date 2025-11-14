@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white px-4 py-5">
     <div class="row mb-5">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="fas fa-link me-3 text-secondary"></i>
                            Event/Gallery za Taasisi
                        </h1>
                        <p class="mb-0 text-muted fs-6">Create a photo event za Taasisi</p>
                    </div>
                    <a href="{{ route('admin.taasisevents.create') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-plus-circle me-2"></i>
                        <span class="fw-semibold">Add New Event</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="pt-1">
  @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
       

        <!-- Events Table -->
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Events Overview</h5>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Mobile/Tablet View -->
                <div class="d-lg-none">
                    @foreach($events as $event)
                    <div class="border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-1">#{{ $event->id }}</h6>
                            <span class="badge bg-light text-dark">{{ $event->images->count() }} images</span>
                        </div>
                        <h6 class="mb-2">{{ $event->name_of_event }}</h6>
                        <p class="text-muted small mb-2">{{ Str::limit($event->description, 100) }}</p>
                        <div class="mb-3">
                            <small class="text-muted">
                                Posted by: {{ $event->posted_by ? 'Admin User' : 'N/A' }}
                            </small>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.taasisevents.show', $event->id) }}" class="btn btn-outline-primary btn-sm flex-fill">
                                View
                            </a>
                            <a href="{{ route('admin.taasisevents.edit', $event->id) }}" class="btn btn-outline-warning btn-sm flex-fill">
                                Edit
                            </a>
                            <form action="{{ route('admin.taasisevents.destroy', $event->id) }}" method="POST" class="flex-fill">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Are you sure you want to delete this event?')">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Desktop View -->
                <div class="d-none d-lg-block">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name of Event</th>
                                    <th>Description</th>
                                    <th>Posted By</th>
                                    <th>Images Count</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                <tr>
                                    <td>{{ $event->id }}</td>
                                    <td>{{ $event->name_of_event }}</td>
                                    <td>{{ $event->description }}</td>
                                    <td>{{ $event->posted_by ? 'Admin User' : 'N/A' }}</td>
                                    <td>{{ $event->images->count() }}</td>
                                    <td>
                                        <a href="{{ route('admin.taasisevents.show', $event->id) }}" class="btn btn-info btn-sm">View</a>
                                        <a href="{{ route('admin.taasisevents.edit', $event->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <form action="{{ route('admin.taasisevents.destroy', $event->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($events->isEmpty())
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-calendar-times text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="text-muted mb-2">No Events Found</h5>
                    <p class="text-muted mb-3">There are currently no events in the system.</p>
                    <a href="{{ route('admin.taasisevents.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Your First Event
                    </a>
                </div>
                @endif
            </div>
        </div>

        <!-- Pagination -->
       
    </div>
</div>

<!-- Custom Styles -->
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
        transition: background-color 0.15s ease-in-out;
    }
    
    .btn-group .btn {
        border-radius: 0.375rem !important;
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }
    
    .alert {
        border: none;
        border-left: 4px solid;
    }
    
    .alert-success {
        border-left-color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .alert-danger {
        border-left-color: #dc3545;
        background-color: rgba(220, 53, 69, 0.1);
    }
    
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card-header .row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .card-header .col-auto {
            width: 100%;
        }
        
        .input-group {
            max-width: 100% !important;
        }
    }
    
    /* Government color scheme */
    :root {
        --gov-primary: #1e3a8a;
        --gov-secondary: #374151;
        --gov-accent: #059669;
    }
    
    .btn-primary {
        background-color: var(--gov-primary);
        border-color: var(--gov-primary);
    }
    
    .btn-primary:hover {
        background-color: #1e40af;
        border-color: #1e40af;
    }
    
    .text-primary {
        color: var(--gov-primary) !important;
    }
    
    .bg-primary {
        background-color: var(--gov-primary) !important;
    }
</style>

<!-- Search Functionality -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const eventItems = document.querySelectorAll('.event-item');
    
    if (searchInput && eventItems.length > 0) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            eventItems.forEach(function(item) {
                const text = item.textContent.toLowerCase();
                const shouldShow = searchTerm === '' || text.includes(searchTerm);
                
                if (shouldShow) {
                    item.style.display = '';
                    item.classList.remove('d-none');
                } else {
                    item.style.display = 'none';
                    item.classList.add('d-none');
                }
            });
            
            // Show/hide empty state for table
            const visibleRows = document.querySelectorAll('.event-item:not(.d-none)');
            const tbody = document.querySelector('tbody');
            const emptyState = document.querySelector('.text-center.py-5');
            
            if (visibleRows.length === 0 && searchTerm !== '' && tbody) {
                if (!document.getElementById('noResults')) {
                    const noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'noResults';
                    noResultsRow.innerHTML = `
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-search text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No events match your search criteria.</p>
                        </td>
                    `;
                    tbody.appendChild(noResultsRow);
                }
            } else {
                const noResultsRow = document.getElementById('noResults');
                if (noResultsRow) {
                    noResultsRow.remove();
                }
            }
        });
    }
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection