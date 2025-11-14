@extends('adminpages.layouts.app')
@php($pageTitle = 'Government News Details')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">News Article Details</h3>
                        <div class="btn-group">
                            <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit Article
                            </a>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to News
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Article Title -->
                    <div class="mb-4">
                        <h2 class="text-dark mb-3">{{ $news->title }}</h2>
                        
                        <!-- Article Meta Information -->
                        <div class="row text-muted mb-4">
                            <div class="col-md-4">
                                <strong>Category:</strong><br>
                                <span class="badge bg-secondary">{{ ucfirst($news->category) }}</span>
                            </div>
                            <div class="col-md-4">
                                <strong>Date Expire:</strong><br>
                                {{ $news->date_expire->format('F j, Y g:i A') }}
                            </div>
                            <div class="col-md-4">
                                <strong>Posted By:</strong><br>
                                {{ $news->user->username ?? 'System Admin' }}
                            </div>
                        </div>

                        @if($news->is_published ?? true)
                            <span class="badge bg-success mb-3">Published</span>
                        @else
                            <span class="badge bg-warning mb-3">Draft</span>
                        @endif
                    </div>

                    <!-- Article Image -->
                    @if($news->front_image && file_exists(public_path('images/storage/'.$news->front_image)))
                        <div class="mb-4">
                            <div class="text-center">
                                <img src="{{ asset('images/storage/'.$news->front_image) }}" 
                                     alt="{{ $news->title }}" 
                                     class="img-fluid rounded shadow-sm"
                                     style="max-height: 400px; width: auto;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                <div class="no-image-large" style="display: none; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; padding: 3rem; text-align: center; color: #6c757d;">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <h5>No Image Available</h5>
                                    <p class="mb-0">The image for this article could not be loaded.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <div class="text-center">
                                <div class="no-image-large" style="background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; padding: 3rem; text-align: center; color: #6c757d;">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <h5>No Image Available</h5>
                                    <p class="mb-0">This article does not have an associated image.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Article Content -->
                    <div class="article-content mb-4">
                        <div class="border-start border-3 border-secondary ps-3">
                            <div class="content-text">
{!! $news->content !!}
                            </div>
                        </div>
                    </div>

                    <!-- Article Stats -->
                    <div class="row mt-4 pt-4 border-top">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> Created: {{ $news->created_at->format('M d, Y g:i A') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-end">
                            <small class="text-muted">
                                <i class="fas fa-edit"></i> Last Updated: {{ $news->updated_at->format('M d, Y g:i A') }}
                            </small>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4 pt-3 border-top">
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-dark">
                                <i class="fas fa-list"></i> Back to News List
                            </a>
                            <a href="{{ route('admin.news.edit', $news->id) }}" class="btn btn-outline-warning">
                                <i class="fas fa-edit"></i> Edit This Article
                            </a>
                            <button type="button" class="btn btn-outline-danger delete-btn" data-id="{{ $news->id }}" data-title="{{ $news->title }}">
                                <i class="fas fa-trash"></i> Delete Article
                            </button>
                        </div>
                    </div>
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

<!-- Hidden delete form -->
<form id="delete-form-{{ $news->id }}" action="{{ route('admin.news.destroy', $news->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
function confirmDelete(id, title) {
    document.getElementById('articleTitle').textContent = title;
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

.article-content {
    font-size: 1.1rem;
    line-height: 1.7;
}

.content-text {
    text-align: justify;
    color: #333;
}

.badge {
    font-size: 0.75em;
}

.border-start {
    border-left: 3px solid #6c757d !important;
}

.img-fluid {
    border: 1px solid #dee2e6;
}

.shadow-sm {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
}

.btn-group .btn {
    margin: 0 2px;
}

.gap-2 {
    gap: 0.5rem !important;
}

.alert {
    border: 1px solid transparent;
    border-radius: 0.375rem;
}
</style>

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

<!-- Hidden delete form -->
<form id="delete-form-{{ $news->id }}" 
      action="{{ route('admin.news.destroy', $news->id) }}" 
      method="POST" 
      style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
@endsection