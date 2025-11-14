@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white px-4 py-5">

  <div class="row mb-2">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="fas fa-link me-3 text-secondary"></i>
                            Add Image's  {{ $event->name_of_event }}
                        </h1>
                        <p class="mb-0 text-muted fs-6">add image and description to taasisi</p>
                    </div>
                    <a href="{{ route('admin.taasisevents.edit', $event->id) }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="pt-2">
        
        <div class="alert alert-info">
            <strong>Event:</strong> {{ $event->name_of_event }}
        </div>

        <x-admin-validation-errors />

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Upload Images</h5>
            </div>
            <div class="card-body">
                <form id="galleryImageForm" action="{{ route('admin.taasisevents.images.store', $event->id) }}" method="POST" enctype="multipart/form-data" data-admin-validation="galleryImages">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="images" class="form-label">
                            <strong>Select Images</strong> <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="file" 
                                   name="images[]" 
                                   id="images"
                                   class="form-control @error('images') is-invalid @enderror" 
                                   multiple 
                                   accept="image/*"
                                   required>
                        </div>
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> You can select multiple images at once. Supported formats: JPG, JPEG, PNG, GIF. Maximum 100MB per image.
                        </small>
                        @error('images')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="descriptions" class="form-label">
                            <strong>Description</strong> <span class="text-muted">(Optional)</span>
                        </label>
                        <textarea name="descriptions[]" 
                                  id="descriptions"
                                  class="ckeditor form-control @error('descriptions') is-invalid @enderror" 
                                  rows="6"
                                  placeholder="Enter description for the images (will apply to all selected images)">{{ old('descriptions.0') }}</textarea>
                        @error('descriptions')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> This description will be applied to all uploaded images. You can use rich text formatting.
                        </small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Upload Images</button>
                        <a href="{{ route('admin.taasisevents.edit', $event->id) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Section (Optional Enhancement) -->
        <div class="card mt-4" id="previewSection" style="display: none;">
            <div class="card-header">
                <h6 class="mb-0">Preview Selected Images</h6>
            </div>
            <div class="card-body">
                <div id="imagePreview" class="row"></div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
<script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for description field
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('descriptions', {
            height: 200,
            toolbar: [
                { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike'] },
                { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'] },
                { name: 'links', items: ['Link', 'Unlink'] },
                { name: 'insert', items: ['Table', 'HorizontalRule'] },
                { name: 'styles', items: ['Format'] },
                { name: 'colors', items: ['TextColor', 'BGColor'] },
                { name: 'tools', items: ['Maximize'] },
                { name: 'document', items: ['Source'] }
            ],
            removePlugins: 'elementspath,image,forms,iframe',
            resize_enabled: false,
            extraPlugins: 'justify',
            justifyClasses: ['text-left', 'text-center', 'text-right', 'text-justify'],
            format_tags: 'p;h3;h4;h5;h6;pre;address;div'
        });
    }

    // Image preview functionality
    const imageInput = document.getElementById('images');
    if (imageInput) {
        imageInput.addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            const previewSection = document.getElementById('previewSection');
            const files = e.target.files;
            
            // Clear previous previews
            preview.innerHTML = '';
            
            if (files.length > 0) {
                previewSection.style.display = 'block';
                
                // Validate file count and size
                if (files.length > 10) {
                    alert('Maximum 10 images allowed at once.');
                    this.value = '';
                    previewSection.style.display = 'none';
                    return;
                }
                
                Array.from(files).forEach((file, index) => {
                    if (file.type.startsWith('image/')) {
                        // Check file size (100MB limit)
                        if (file.size > 5 * 1024 * 1024) {
                            alert(`Image "${file.name}" is larger than 100MB. Please choose a smaller image.`);
                            this.value = '';
                            previewSection.style.display = 'none';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const col = document.createElement('div');
                            col.className = 'col-lg-2 col-md-3 col-sm-4 col-6 mb-3';
                            col.innerHTML = `
                                <div class="card">
                                    <img src="${e.target.result}" class="card-img-top" style="height: 120px; object-fit: cover;" alt="Preview">
                                    <div class="card-body p-2">
                                        <small class="text-muted">${file.name}</small>
                                        <br><small class="text-info">${(file.size / 1024 / 1024).toFixed(2)} MB</small>
                                    </div>
                                </div>
                            `;
                            preview.appendChild(col);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        alert(`File "${file.name}" is not a valid image format.`);
                        this.value = '';
                        previewSection.style.display = 'none';
                        return;
                    }
                });
            } else {
                previewSection.style.display = 'none';
            }
        });
    }
    
    // Admin validation is automatically initialized by the data-admin-validation attribute
    // Additional image upload validation can be added here if needed
    
    // Handle form submission with CKEditor
    const form = document.getElementById('galleryImageForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Update CKEditor instances before form submission
            if (typeof CKEDITOR !== 'undefined') {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            }
        });
    }
});
</script>
@endpush
@endsection