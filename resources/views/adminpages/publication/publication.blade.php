@extends('adminpages.layouts.app')
@php($pageTitle = 'Government Publications Management')

@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Publications Management</h3>
                        <div class="btn-group">
                            <a href="{{ route('admin.publications.create') }}" class="btn btn-outline-dark">
                                <i class="fas fa-plus"></i> Add New Publication
                            </a>
                            <a href="{{ route('admin.publications.categories.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-tags"></i> Manage Categories
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body py-3">
                                    <form method="GET" action="{{ route('admin.publications.search') }}">
                                        <div class="row align-items-end">
                                            <div class="col-md-4">
                                                <label class="form-label small text-muted">Search Publications</label>
                                                <input type="text" 
                                                       name="q" 
                                                       class="form-control" 
                                                       placeholder="Enter title or description..." 
                                                       value="{{ request('q') }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small text-muted">Filter by Category</label>
                                                <select name="category_id" class="form-select">
                                                    <option value="">All Categories</option>
                                                    @foreach($categories as $category)
                                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <button type="submit" class="btn btn-outline-secondary w-100">
                                                    <i class="fas fa-search"></i> Search
                                                </button>
                                            </div>
                                           
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> 
                            <strong>Please correct the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Publications Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                   
                                    <th style="width: 30%;">Publication Details</th>
                                    <th style="width: 15%;">Category</th>
                                    <th style="width: 10%;">File Info</th> 
                                    <th style="width: 12%;">Created</th>
                                    <th style="width: 15%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($publications as $publication)
                                    <tr>
                                      
                                        <td>
                                            <div>
                                                <strong class="text-dark">{{ $publication->title }}</strong>
                                                @if($publication->description)
                                                    <br><small class="text-muted">{{ Str::limit($publication->description, 80) }}</small>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $publication->category->name }}</span>
                                            @if($publication->is_direct_guideline)
                                                <br><span class="badge bg-success mt-1">🎯 Direct Guideline</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small">
                                                <span class="badge bg-info mb-1">{{ strtoupper($publication->file_type) }}</span>
                                                <br><span class="text-muted">{{ $publication->formatted_file_size }}</span>
                                            </div>
                                        </td>
                                        
                                        <td class="small text-muted">
                                            {{ $publication->created_at }}
                                            <br>{{ $publication->created_at}}
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ $publication->download_url }}" 
                                                   class="btn btn-outline-info" 
                                                   target="_blank" 
                                                   title="Download">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                <a href="{{ route('admin.publications.show', $publication) }}" 
                                                   class="btn btn-outline-primary" 
                                                   title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.publications.edit', $publication) }}" 
                                                   class="btn btn-outline-warning" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-danger" 
                                                        title="Delete"
                                                        onclick="confirmDelete({{ $publication->id }}, '{{ $publication->title }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Hidden delete form -->
                                            <form id="delete-form-{{ $publication->id }}" 
                                                  action="{{ route('admin.publications.destroy', $publication) }}" 
                                                  method="POST" 
                                                  style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-file-alt fa-3x mb-3"></i>
                                                <p class="mb-2">No publications found.</p>
                                                @if(request()->hasAny(['q', 'category_id']))
                                                    <p class="small">Try adjusting your search criteria.</p>
                                                    <a href="{{ route('admin.publications.index') }}" class="btn btn-outline-secondary btn-sm">
                                                        Clear Filters
                                                    </a>
                                                @else
                                                    <a href="{{ route('admin.publications.create') }}" class="btn btn-outline-dark">
                                                        Add First Publication
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($publications->hasPages())
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div class="text-muted small">
                                Showing {{ $publications->firstItem() }} to {{ $publications->lastItem() }} of {{ $publications->total() }} publications
                            </div>
                            <div>
                                {{ $publications->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this publication?</p>
                <p class="text-muted" id="publicationTitle"></p>
                <div class="alert alert-warning">
                    <small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone and will remove the file permanently.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Publication</button>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Delete Confirmation Modal -->
<div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Bulk Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <span id="bulkCount"></span> selected publication(s)?</p>
                <div class="alert alert-danger">
                    <small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone and will remove all selected files permanently.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmBulkDeleteBtn">Delete All Selected</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.publication-checkbox');
    const bulkDeleteBtn = document.getElementById('bulk-delete-btn');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        toggleBulkDeleteButton();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleBulkDeleteButton);
    });

    function toggleBulkDeleteButton() {
        const checkedBoxes = document.querySelectorAll('.publication-checkbox:checked');
        bulkDeleteBtn.style.display = checkedBoxes.length > 0 ? 'block' : 'none';
        
        // Update select all checkbox state
        selectAll.checked = checkedBoxes.length === checkboxes.length && checkboxes.length > 0;
        selectAll.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < checkboxes.length;
    }

    // Bulk delete functionality
    bulkDeleteBtn.addEventListener('click', function() {
        const checkedBoxes = document.querySelectorAll('.publication-checkbox:checked');
        document.getElementById('bulkCount').textContent = checkedBoxes.length;
        new bootstrap.Modal(document.getElementById('bulkDeleteModal')).show();
    });

    document.getElementById('confirmBulkDeleteBtn').addEventListener('click', function() {
        const selectedIds = Array.from(document.querySelectorAll('.publication-checkbox:checked'))
            .map(checkbox => checkbox.value);

        if (selectedIds.length === 0) return;

        fetch('{{ route("admin.publications.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ ids: selectedIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(() => {
            alert('An error occurred while deleting publications.');
        });
    });

    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const publicationId = this.dataset.id;
            
            fetch(`/admin/publications/${publicationId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.status) {
                        this.classList.remove('btn-outline-secondary');
                        this.classList.add('btn-outline-success');
                        this.textContent = 'Active';
                    } else {
                        this.classList.remove('btn-outline-success');
                        this.classList.add('btn-outline-secondary');
                        this.textContent = 'Inactive';
                    }
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => {
                alert('An error occurred while updating status.');
            });
        });
    });

    // Toggle direct guideline functionality
    document.querySelectorAll('.toggle-direct-guideline').forEach(button => {
        button.addEventListener('click', function() {
            const publicationId = this.dataset.id;
            
            fetch(`/admin/publications/${publicationId}/toggle-direct-guideline`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance and text
                    if (data.is_direct_guideline) {
                        this.classList.remove('btn-outline-info');
                        this.classList.add('btn-outline-warning');
                        this.textContent = '🎯 Direct';
                    } else {
                        this.classList.remove('btn-outline-warning');
                        this.classList.add('btn-outline-info');
                        this.textContent = '📚 Regular';
                    }
                    
                    // Show success message
                    alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(() => {
                alert('An error occurred while updating direct guideline status.');
            });
        });
    });
});

// Delete confirmation function
function confirmDelete(id, title) {
    document.getElementById('publicationTitle').textContent = title;
    document.getElementById('confirmDeleteBtn').onclick = function() {
        document.getElementById('delete-form-' + id).submit();
    };
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>

<style>
.card {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-group-sm > .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75em;
}

.form-check-input:checked {
    background-color: #6c757d;
    border-color: #6c757d;
}

.gap-2 {
    gap: 0.5rem !important;
}

.alert {
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.btn-group .btn {
    margin: 0 1px;
}

.bg-light {
    background-color: #f8f9fa !important;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Pagination fixes */
.pagination {
    margin-bottom: 0 !important;
}

.pagination .page-link {
    position: relative;
    z-index: 1;
    border: 1px solid #dee2e6;
    color: #0d6efd;
    text-decoration: none;
    background-color: #fff;
    transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}

.pagination .page-link:hover {
    z-index: 2;
    color: #0a58ca;
    background-color: #e9ecef;
    border-color: #dee2e6;
}

.pagination .page-item.active .page-link {
    z-index: 3;
    color: #fff;
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.pagination .page-item.disabled .page-link {
    color: #6c757d;
    pointer-events: none;
    background-color: #fff;
    border-color: #dee2e6;
}

.pagination .page-link i {
    font-size: 0.875rem;
}

/* Ensure pagination doesn't interfere with other elements */
.pagination {
    position: relative;
    z-index: 10;
}

/* Fix any potential arrow overlay issues */
.pagination .page-link:before,
.pagination .page-link:after {
    display: none !important;
}
</style>
@endsection