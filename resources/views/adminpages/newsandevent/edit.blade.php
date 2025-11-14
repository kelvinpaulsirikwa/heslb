@extends('adminpages.layouts.app')
@php($pageTitle = 'Edit Government News')

@section('content')
<div class="container-fluid bg-white p-4 mt-2">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Edit News Article</h3>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to News List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif



                    <x-admin-validation-errors />

                    <form id="newsPublishForm" action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" data-admin-validation="newsPublish">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- News Title -->
                                <x-admin-form-field
                                    type="text"
                                    name="title"
                                    label="News Title"
                                    :value="old('title', $news->title)"
                                    placeholder="Enter news article title"
                                    required
                                />

                                <!-- News Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">
                                        <strong>News Content</strong> <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="content" 
                                              id="content" 
                                              class="ckeditor form-control @error('content') is-invalid @enderror" 
                                              rows="12" 
                                              required
                                              placeholder="Enter the full news article content here...">{{ old('content', $news->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-muted">
                                        <small>
                                            <i class="fas fa-info-circle"></i> 
                                            Write your complete news article content. Line breaks will be preserved.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-4">
                                <!-- Article Settings Card -->
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Article Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- News Category -->
                                        <div class="mb-3">
                                            <label for="category" class="form-label">
                                                <strong>Category</strong> <span class="text-danger">*</span>
                                            </label>
                                            <select name="category" 
                                                    id="category" 
                                                    class="form-select @error('category') is-invalid @enderror" 
                                                    required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $cat)
                                                    <option value="{{ $cat }}" {{ old('category', $news->category) == $cat ? 'selected' : '' }}>
                                                        {{ ucfirst($cat) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Date Posted -->
                                        <x-admin-form-field
                                            type="datetime-local"
                                            name="date_expire"
                                            label="Expire Date"
                                            :value="old('date_expire', $news->date_expire->format('Y-m-d\TH:i'))"
                                            required
                                        />

                                        <!-- Publication Status -->
                                        <div class="mb-3">
                                            <label class="form-label"><strong>Status</strong></label>
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                       type="checkbox" 
                                                       name="is_published" 
                                                       id="is_published" 
                                                       value="1" 
                                                       {{ old('is_published', $news->is_published ?? true) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_published">
                                                    Published
                                                </label>
                                            </div>
                                            <small class="text-muted">Uncheck to save as draft</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Upload Card -->
                                <div class="card bg-light mt-3">
                                    <div class="card-header">
                                        <h6 class="card-title mb-0">Article Image</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- Current Image Preview -->
                                        @if($news->front_image && file_exists(public_path('images/storage/'.$news->front_image)))
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Current Image:</strong></label>
                                                <div class="text-center">
                                                    <img src="{{ asset('images/storage/'.$news->front_image) }}" 
                                                         alt="Current Image" 
                                                         class="img-thumbnail mb-2" 
                                                         style="max-width: 100%; max-height: 150px;"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                                    <div class="no-image-edit" style="display: none; background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; padding: 2rem; text-align: center; color: #6c757d;">
                                                        <i class="fas fa-image fa-2x mb-2"></i>
                                                        <p class="mb-0">Current image could not be loaded</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @elseif($news->front_image)
                                            <div class="mb-3">
                                                <label class="form-label"><strong>Current Image:</strong></label>
                                                <div class="text-center">
                                                    <div class="no-image-edit" style="background: #f8f9fa; border: 2px dashed #dee2e6; border-radius: 8px; padding: 2rem; text-align: center; color: #6c757d;">
                                                        <i class="fas fa-image fa-2x mb-2"></i>
                                                        <p class="mb-0">Image file not found in storage</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- New Image Upload -->
                                        <div class="mb-0">
                                            <x-admin-form-field
                                                type="file"
                                                name="front_image"
                                                :label="$news->front_image ? 'Replace Image' : 'Upload Image'"
                                                accept="image/*"
                                            />
                                            <div class="form-text text-muted">
                                                <small>
                                                    <i class="fas fa-info-circle"></i> 
                                                    Supported: JPG, PNG, GIF (Max: 100MB)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="border-top pt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-outline-success">
                                            <i class="fas fa-save"></i> Update Article
                                        </button>
                                        <a href="{{ route('admin.news.show', $news->id) }}" class="btn btn-outline-info">
                                            <i class="fas fa-eye"></i> Preview
                                        </a>
                                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> 
                                            Fields marked with <span class="text-danger">*</span> are required.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('front_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Create preview if it doesn't exist
            let preview = document.getElementById('image-preview');
            if (!preview) {
                preview = document.createElement('div');
                preview.id = 'image-preview';
                preview.className = 'mt-2 text-center';
                e.target.parentNode.appendChild(preview);
            }
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview" class="img-thumbnail" style="max-width: 100%; max-height: 150px;">
                <div class="mt-1"><small class="text-muted">New image preview</small></div>
            `;
        };
        reader.readAsDataURL(file);
    }
});



// Character counter for content
document.getElementById('content').addEventListener('input', function() {
    const length = this.value.length;
    let counter = document.getElementById('content-counter');
    if (!counter) {
        counter = document.createElement('small');
        counter.id = 'content-counter';
        counter.className = 'text-muted float-end';
        this.parentNode.appendChild(counter);
    }
    counter.textContent = `${length} characters`;
});

</script>

<style>
.card {
    border: 1px solid #dee2e6;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}

.form-label strong {
    color: #495057;
}

.form-control:focus,
.form-select:focus {
    border-color: #6c757d;
    box-shadow: 0 0 0 0.2rem rgba(108, 117, 125, 0.25);
}

.btn-group .btn {
    margin: 0 2px;
}

.gap-2 {
    gap: 0.5rem !important;
}

.img-thumbnail {
    border: 1px solid #dee2e6;
}

.alert {
    border: 1px solid transparent;
    border-radius: 0.375rem;
}

.invalid-feedback {
    display: block;
}

.text-danger {
    color: #dc3545 !important;
}

.bg-light {
    background-color: #f8f9fa !important;
}

/* Frontend validation styling */
.is-invalid {
    border-color: #dc3545 !important;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

.is-valid {
    border-color: #198754 !important;
    box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25) !important;
}

.invalid-feedback {
    display: block !important;
    color: #dc3545;
    font-size: 0.875em;
    margin-top: 0.25rem;
}

.valid-feedback {
    display: block !important;
    color: #198754;
    font-size: 0.875em;
    margin-top: 0.25rem;
}
</style>
@endsection