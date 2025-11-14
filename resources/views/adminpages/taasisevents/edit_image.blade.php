@extends('adminpages.layouts.app')

@section('content')
<div class="container">
    <div class="pt-2">
        <h2 class="mb-4">Edit Image Description</h2>

        <!-- Event Info Alert (Optional if you want to show event) -->
        @if(isset($event))
        <div class="alert alert-info">
            <strong>Event:</strong> {{ $event->name_of_event }}
        </div>
        @endif

        <!-- Validation Errors -->
        <x-admin-validation-errors />

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Image Preview & Description</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 text-center">
                    <img src="{{ asset('images/storage/' . $image->image_link) }}" class="img-fluid mb-3" style="max-height: 300px; object-fit: contain;" alt="Event Image">
                </div>

                <form id="editImageForm" action="{{ route('admin.taasisevents.images.update', $image->id) }}" method="POST" data-admin-validation="galleryImageEdit">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <strong>Description</strong>
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  class="ckeditor form-control @error('description') is-invalid @enderror" 
                                  rows="6" 
                                  placeholder="Enter image description">{{ old('description', $image->description) }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> You can update the description for this image using rich text formatting.
                        </small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
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
        CKEDITOR.replace('description', {
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
    
    // Handle form submission with CKEditor
    const form = document.getElementById('editImageForm');
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
    
    // Admin validation is automatically initialized by the data-admin-validation attribute
});
</script>
@endpush
@endsection
