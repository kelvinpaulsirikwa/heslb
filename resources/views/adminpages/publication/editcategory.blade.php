{{-- resources/views/admin/publications/categories/edit.blade.php --}}
@extends('adminpages.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Category</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.publications.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Categories
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.publications.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        {{-- Error Messages --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Name --}}
                        <div class="form-group">
                            <label for="name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Current slug: <strong>{{ $category->slug }}</strong>
                            </small>
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Enter category description...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Display Order --}}
                        <div class="form-group">
                            <label for="display_order">Display Order</label>
                            <input type="number" 
                                   class="form-control @error('display_order') is-invalid @enderror" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="{{ old('display_order', $category->display_order) }}" 
                                   min="0" 
                                   step="1">
                            @error('display_order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                Lower numbers will appear first.
                            </small>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                            <small class="form-text text-muted">
                                When active, the category will be available for publications.
                            </small>
                        </div>

                        {{-- Additional Information --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Publications Count</label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $category->publications()->count() }}" 
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Created At</label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ $category->created_at}}" 
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Category
                        </button>
                        <a href="{{ route('admin.publications.categories.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <div class="float-right">
                            @if($category->publications()->count() == 0)
                                <form action="{{ route('admin.publications.categories.destroy', $category) }}" 
                                      method="POST" 
                                      class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fas fa-trash"></i> Delete Category
                                    </button>
                                </form>
                            @else
                                <button type="button" 
                                        class="btn btn-secondary" 
                                        title="Cannot delete category with publications"
                                        disabled>
                                    <i class="fas fa-trash"></i> Delete Category
                                </button>
                                <small class="text-muted d-block">
                                    Cannot delete category with {{ $category->publications()->count() }} publication(s)
                                </small>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Category Statistics --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar"></i> Category Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <div class="border-right">
                                <h4 class="text-primary">{{ $category->publications()->count() }}</h4>
                                <p class="text-muted">Total Publications</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="border-right">
                                <h4 class="text-success">{{ $category->publications()->where('is_active', true)->count() }}</h4>
                                <p class="text-muted">Active Publications</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <h4 class="text-info">{{ $category->publications()->sum('download_count') ?: 0 }}</h4>
                            <p class="text-muted">Total Downloads</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Publications in this Category --}}
            @if($category->publications()->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-alt"></i> Publications in this Category
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Downloads</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($category->publications()->latest()->limit(10)->get() as $publication)
                                        <tr>
                                            <td>
                                                <strong>{{ Str::limit($publication->title, 30) }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $publication->is_active ? 'success' : 'secondary' }}">
                                                    {{ $publication->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ $publication->download_count ?: 0 }}</td>
                                            <td>{{ $publication->created_at }}</td>
                                            <td>
                                                <a href="{{ route('admin.publications.show', $publication) }}" 
                                                   class="btn btn-sm btn-outline-primary">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($category->publications()->count() > 10)
                            <p class="text-muted text-center mt-2">
                                Showing 10 of {{ $category->publications()->count() }} publications.
                                <a href="{{ route('admin.publications.search', ['category_id' => $category->id]) }}">
                                    View all publications in this category
                                </a>
                            </p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate slug preview when name changes
    $('#name').on('input', function() {
        const name = $(this).val();
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
        
        const currentSlug = '{{ $category->slug }}';
        let previewElement = $('#slug-preview');
        if (previewElement.length === 0) {
            previewElement = $('<small class="form-text text-muted" id="slug-preview"></small>');
            $(this).siblings('.form-text').after(previewElement);
        }
        
        if (slug && slug !== currentSlug) {
            previewElement.html('<strong>New slug will be:</strong> ' + slug);
        } else {
            previewElement.html('');
        }
    });

    // Delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
            this.submit();
        }
    });

    // Form validation
    $('form:not(.delete-form)').on('submit', function(e) {
        const name = $('#name').val().trim();
        if (name.length < 2) {
            e.preventDefault();
            alert('Category name must be at least 2 characters long.');
            return false;
        }
    });
});
</script>
@endpush
@endsection