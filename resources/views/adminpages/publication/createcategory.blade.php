{{-- resources/views/admin/publications/categories/create.blade.php --}}
@extends('adminpages.layouts.app')

@section('title', 'Create New Category')

@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Category</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.publications.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Categories
                        </a>
                    </div>
                </div>

                <x-admin-validation-errors />

                <form id="categoriesForm" action="{{ route('admin.publications.categories.store') }}" method="POST" data-admin-validation="categories">
                    @csrf
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
                        <x-admin-form-field
                            type="text"
                            name="name"
                            label="Category Name"
                            :value="old('name')"
                            placeholder="Enter category name"
                            required
                            help="The slug will be automatically generated from the name"
                        />

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <strong>Description</strong>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Enter category description...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Optional description to explain what this category contains</small>
                        </div>

                        {{-- Display Order --}}
                        <div class="mb-3">
                            <label for="display_order" class="form-label">
                                <strong>Display Order</strong>
                            </label>
                            <input type="number" 
                                   class="form-control @error('display_order') is-invalid @enderror" 
                                   id="display_order" 
                                   name="display_order" 
                                   value="{{ old('display_order', 0) }}" 
                                   min="0" 
                                   step="1">
                            @error('display_order')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-sort-numeric-up"></i> Lower numbers will appear first. Default is 0.
                            </small>
                        </div>

                        {{-- Status --}}
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active Status</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-toggle-on"></i> When active, the category will be available for publications.
                            </small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Category
                        </button>
                        <a href="{{ route('admin.publications.categories.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            {{-- Helper Card --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle"></i> Category Guidelines
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li><i class="fas fa-check text-success"></i> Use descriptive names that clearly identify the content type</li>
                        <li><i class="fas fa-check text-success"></i> Keep names concise but meaningful</li>
                        <li><i class="fas fa-check text-success"></i> Use proper capitalization (e.g., "Annual Reports" not "annual reports")</li>
                        <li><i class="fas fa-check text-success"></i> Set appropriate display order to organize categories logically</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate slug preview (optional enhancement)
    const nameInput = document.getElementById('name');
    if (nameInput) {
        nameInput.addEventListener('input', function() {
            const name = this.value;
            const slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '-') // Replace spaces with hyphens
                .replace(/-+/g, '-') // Replace multiple hyphens with single hyphen
                .trim();
            
            // Show slug preview
            let previewElement = document.getElementById('slug-preview');
            if (!previewElement) {
                previewElement = document.createElement('small');
                previewElement.id = 'slug-preview';
                previewElement.className = 'form-text text-info mt-1';
                this.parentNode.appendChild(previewElement);
            }
            
            if (slug) {
                previewElement.innerHTML = '<i class="fas fa-link"></i> <strong>Slug preview:</strong> ' + slug;
            } else {
                previewElement.innerHTML = '';
            }
        });
    }
    
    // Admin validation is automatically initialized by the data-admin-validation attribute
    // Additional category-specific validation can be added here if needed
});
</script>
@endpush
@endsection