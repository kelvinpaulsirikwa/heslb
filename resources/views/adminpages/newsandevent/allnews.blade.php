@extends('adminpages.layouts.app')
@php($pageTitle = 'Government News Management')

@section('content')
<div class="container-fluid bg-white p-4 mt-2 ">
    <div class="row">
        <div class="col-12">
            <div class="card">
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="text-dark">Heslb News Administration</h4>
                        <a href="{{ route('admin.news.create') }}" class="btn btn-outline-dark">
                            <i class="fas fa-plus"></i> Add New Article
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">Title</th>
                                    <th style="width: 15%">Category</th>
                                    <th style="width: 12%">Date Expire</th>
                                    <th style="width: 12%">Posted By</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 8%">Image</th>
                                    <th style="width: 18%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($news as $index => $item)
                                <tr>
                                    <td>{{ $news->firstItem() + $index }}</td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 250px;" title="{{ $item->title }}">
                                            {{ $item->title }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->category }}</span>
                                    </td>
                                    <td>{{ $item->date_expire->format('M d, Y') }}</td>
                                    <td>{{ $item->user->username ?? 'System Admin' }}</td>
                                    <td>
                                        @if($item->is_published ?? true)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->front_image && file_exists(public_path('images/storage/'.$item->front_image)))
                                            <img src="{{ asset('images/storage/'.$item->front_image) }}" 
                                                 alt="News Image" 
                                                 class="img-thumbnail" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="no-image-placeholder" style="display: none; width: 50px; height: 50px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; align-items: center; justify-content: center; flex-direction: column;">
                                                <i class="fas fa-image text-muted" style="font-size: 12px;"></i>
                                                <small class="text-muted" style="font-size: 8px;">No Image</small>
                                            </div>
                                        @else
                                            <div class="no-image-placeholder" style="width: 50px; height: 50px; background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 4px; display: flex; align-items: center; justify-content: center; flex-direction: column;">
                                                <i class="fas fa-image text-muted" style="font-size: 12px;"></i>
                                                <small class="text-muted" style="font-size: 8px;">No Image</small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <a href="{{ route('admin.news.show', $item->id) }}" 
                                               class="btn btn-outline-info" 
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.news.edit', $item->id) }}" 
                                               class="btn btn-outline-warning" 
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger delete-btn" 
                                                    title="Delete"
                                                    data-id="{{ $item->id }}"
                                                    data-title="{{ $item->title }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                        <!-- Hidden delete form -->
                                        <form id="delete-form-{{ $item->id }}" 
                                              action="{{ route('admin.news.destroy', $item->id) }}" 
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
                                            <i class="fas fa-newspaper fa-3x mb-3"></i>
                                            <p>No news articles found.</p>
                                            <a href="{{ route('admin.news.create') }}" class="btn btn-outline-dark">
                                                Add First Article
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($news->hasPages())
                        <div class="pagination-wrapper mt-4">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                                <div class="pagination-info text-muted">
                                    <small>
                                        Showing {{ $news->firstItem() }} to {{ $news->lastItem() }} of {{ $news->total() }} results
                                    </small>
                                </div>
                                <div class="pagination-controls">
                                    {{ $news->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
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
                <p>Are you sure you want to delete this news article?</p>
                <p class="text-muted" id="articleTitle"></p>
                <div class="alert alert-warning">
                    <small><i class="fas fa-exclamation-triangle"></i> This action cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete Article</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle delete button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-btn')) {
            const button = e.target.closest('.delete-btn');
            const id = button.getAttribute('data-id');
            const title = button.getAttribute('data-title');
            
            document.getElementById('articleTitle').textContent = title;
            document.getElementById('confirmDeleteBtn').onclick = function() {
                document.getElementById('delete-form-' + id).submit();
            };
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    });
});
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

.img-thumbnail {
    border: 1px solid #dee2e6;
}

.badge {
    font-size: 0.75em;
}

.text-truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.alert {
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

/* Admin Pagination Styling - Override any conflicting styles */
.pagination-wrapper {
    background: #fff;
    padding: 1rem;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.pagination-controls {
    display: flex;
    justify-content: center;
    align-items: center;
}

.pagination-controls .pagination {
    margin: 0 !important;
    padding: 0 !important;
    display: flex !important;
    list-style: none !important;
    gap: 0.25rem;
}

.pagination-controls .pagination .page-item {
    margin: 0 !important;
}

.pagination-controls .pagination .page-link {
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    min-width: 40px !important;
    height: 40px !important;
    padding: 0.5rem 0.75rem !important;
    margin: 0 !important;
    color: #0d6efd !important;
    text-decoration: none !important;
    background-color: #fff !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 0.375rem !important;
    transition: all 0.15s ease-in-out !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
}

.pagination-controls .pagination .page-link:hover {
    z-index: 2 !important;
    color: #0a58ca !important;
    background-color: #e9ecef !important;
    border-color: #dee2e6 !important;
    text-decoration: none !important;
}

.pagination-controls .pagination .page-item.active .page-link {
    z-index: 3 !important;
    color: #fff !important;
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
}

.pagination-controls .pagination .page-item.disabled .page-link {
    color: #6c757d !important;
    pointer-events: none !important;
    background-color: #fff !important;
    border-color: #dee2e6 !important;
    opacity: 0.5 !important;
}

.pagination-info {
    font-size: 0.875rem;
    color: #6c757d;
}

/* Responsive pagination */
@media (max-width: 768px) {
    .pagination-wrapper .d-flex {
        flex-direction: column !important;
        gap: 1rem !important;
    }
    
    .pagination-info {
        text-align: center;
    }
    
    .pagination-controls .pagination .page-link {
        min-width: 35px !important;
        height: 35px !important;
        padding: 0.375rem 0.5rem !important;
        font-size: 0.8rem !important;
    }
}

/* No Image Placeholder Styling */
.no-image-placeholder {
    transition: all 0.2s ease;
}

.no-image-placeholder:hover {
    background-color: #e9ecef !important;
    border-color: #adb5bd !important;
}
</style>
@endsection