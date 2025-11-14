@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white p-4 mt-2 ">
    


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Add News Article</h3>
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

                    <form id="newsPublishForm" action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" data-admin-validation="newsPublish">
                        @csrf

                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-8">
                                <!-- News Title -->
                                <x-admin-form-field
                                    type="text"
                                    name="title"
                                    label="News Title"
                                    :value="old('title')"
                                    placeholder="Enter news article title"
                                    required
                                />

                                <!-- News Content -->
                                <div class="mb-3">
                                    <label for="content" class="form-label">
                                        <strong>News Content</strong>
                                    </label>
                                    <textarea name="content" 
                                              id="content" 
                                              class="ckeditor form-control @error('content') is-invalid @enderror" 
                                              rows="12" 
                                              placeholder="Enter the full news article content here...">{{ old('content') }}</textarea>
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
                                                    <option value="{{ $cat }}" {{ old('category') == $cat ? 'selected' : '' }}>
                                                        {{ ucfirst($cat) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Date Expire -->
                                        <x-admin-form-field
                                            type="datetime-local"
                                            name="date_expire"
                                            label="Expire Date"
                                            :value="old('date_expire')"
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
                                                       {{ old('is_published', true) ? 'checked' : '' }}>
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
                                        <x-admin-form-field
                                            type="file"
                                            name="front_image"
                                            label="Upload Image"
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

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="border-top pt-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-outline-success">
                                            <i class="fas fa-save"></i> Save Article
                                        </button>
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

@push('scripts')
<script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize CKEditor for content field
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('content', {
            height: 300,
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
    
    // Handle form submission with CKEditor
    const form = document.getElementById('newsPublishForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Update CKEditor instances before form submission
            if (typeof CKEDITOR !== 'undefined') {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            }
        });
        
        // Also sync CKEditor content on any input/change events to ensure validation sees the content
        if (typeof CKEDITOR !== 'undefined') {
            const contentEditor = CKEDITOR.instances.content;
            if (contentEditor) {
                contentEditor.on('change', function() {
                    // Sync content to textarea immediately when content changes
                    contentEditor.updateElement();
                });
                
                contentEditor.on('blur', function() {
                    // Also sync on blur to ensure content is available for validation
                    contentEditor.updateElement();
                });
                
                // Override the textarea's value getter to return CKEditor content
                const textarea = document.getElementById('content');
                if (textarea) {
                    const originalValue = textarea.value;
                    Object.defineProperty(textarea, 'value', {
                        get: function() {
                            return contentEditor.getData();
                        },
                        set: function(val) {
                            originalValue = val;
                        }
                    });
                }
            }
        }
    }
    
    // Character counter for content (will work after CKEditor is initialized)
    setTimeout(function() {
        const contentEditor = CKEDITOR.instances.content;
        if (contentEditor) {
            contentEditor.on('change', function() {
                const length = contentEditor.getData().length;
                let counter = document.getElementById('content-counter');
                if (!counter) {
                    counter = document.createElement('small');
                    counter.id = 'content-counter';
                    counter.className = 'text-muted float-end';
                    document.querySelector('label[for="content"]').appendChild(counter);
                }
                counter.textContent = `${length} characters`;
            });
        }
    }, 1000);
});
</script>
@endpush

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
