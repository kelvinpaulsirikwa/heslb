{{-- resources/views/admin/publications/create.blade.php --}}
@extends('adminpages.layouts.app')

@section('title', 'Create New Publication')

@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Publication</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Publications
                        </a>
                    </div>
                </div>

                <x-admin-validation-errors />

                <form id="publicationsForm" action="{{ route('admin.publications.store') }}" method="POST" enctype="multipart/form-data" data-admin-validation="publications">
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

                        {{-- Title --}}
                        <x-admin-form-field
                            type="text"
                            name="title"
                            label="Publication Title"
                            :value="old('title')"
                            placeholder="Enter publication title"
                            required
                            help="Give your publication a descriptive title"
                        />

                        {{-- Category --}}
                        <div class="mb-3">
                            <label for="category_id" class="form-label">
                                <strong>Category</strong> <span class="text-danger">*</span>
                            </label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Choose the appropriate category for this publication</small>
                        </div>

                        {{-- File Upload --}}
                        <div class="mb-3">
                            <label for="file" class="form-label">
                                <strong>Publication File</strong> <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="file" 
                                       class="form-control @error('file') is-invalid @enderror" 
                                       id="file" 
                                       name="file" 
                                       accept=".pdf,.doc,.docx,.xls,.xlsx"
                                       required>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Allowed file types: PDF, DOC, DOCX, XLS, XLSX. Maximum size: 100MB
                            </small>
                            @error('file')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <strong>Description</strong>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter publication description...">{{ old('description') }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Optional description to help users understand the content</small>
                        </div>

                        {{-- Direct Guideline Option (only for Guidelines category) --}}
                        <div class="mb-3" id="direct-guideline-section" style="display: none;">
                            <div class="form-check form-switch">
                                <input type="checkbox" 
                                       class="form-check-input" 
                                       id="is_direct_guideline" 
                                       name="is_direct_guideline" 
                                       value="1" 
                                       {{ old('is_direct_guideline') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_direct_guideline">
                                    <strong>🎯 Set as Direct Guideline</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> This will mark this publication as a direct guideline for application guidelines. Only available for Guidelines category.
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
                                <i class="fas fa-info-circle"></i> When active, the publication will be visible to users.
                            </small>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Publication
                        </button>
                        <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize file input display
    const fileInput = document.getElementById('file');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            const fileName = this.files[0]?.name || 'No file chosen';
            // Show file name in a small helper text
            let fileNameDisplay = document.querySelector('#file-name-display');
            if (!fileNameDisplay) {
                fileNameDisplay = document.createElement('small');
                fileNameDisplay.id = 'file-name-display';
                fileNameDisplay.className = 'form-text text-info mt-1';
                fileInput.parentNode.parentNode.appendChild(fileNameDisplay);
            }
            fileNameDisplay.innerHTML = '<i class="fas fa-file"></i> ' + fileName;
        });
    }
    
    // Handle category change to show/hide direct guideline option
    const categorySelect = document.getElementById('category_id');
    const directGuidelineSection = document.getElementById('direct-guideline-section');
    const directGuidelineCheckbox = document.getElementById('is_direct_guideline');
    
    function toggleDirectGuidelineOption() {
        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const isGuidelinesCategory = selectedOption.text.toLowerCase().includes('guideline');
        
        if (isGuidelinesCategory) {
            directGuidelineSection.style.display = 'block';
        } else {
            directGuidelineSection.style.display = 'none';
            directGuidelineCheckbox.checked = false; // Uncheck if not guidelines category
        }
    }
    
    if (categorySelect) {
        categorySelect.addEventListener('change', toggleDirectGuidelineOption);
        // Check on page load
        toggleDirectGuidelineOption();
    }
    
    // Admin validation is automatically initialized by the data-admin-validation attribute
    // Additional publication-specific validation can be added here if needed
});
</script>
@endpush
@endsection