@extends('adminpages.layouts.app')

@section('title', 'Add New Partner')

@section('content')
<div class="container-fluid  bg-white px-4 py-5">
    <div class="row">
         <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class=" text-secondary"></i>
                            Add Partner
                        </h1>
                        <p class="mb-0 text-muted fs-6">Add another strategic partner </p>
                    </div>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back</span>
                    </a>
                </div>
            </div>
        </div>
        <br>
        <div class="container-fluid col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Adding Form</h3>
                 
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.partners.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Partner Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="acronym_name" class="form-label">Acronym Name</label>
                                    <input type="text" 
                                           class="form-control @error('acronym_name') is-invalid @enderror" 
                                           id="acronym_name" 
                                           name="acronym_name" 
                                           value="{{ old('acronym_name') }}">
                                    @error('acronym_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="link" class="form-label">Partner Website Link</label>
                            <input type="url" 
                                   class="form-control @error('link') is-invalid @enderror" 
                                   id="link" 
                                   name="link" 
                                   value="{{ old('link') }}"
                                   placeholder="https://example.com">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Partner Logo/Image</label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*">
                            <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 5MB</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div id="image-preview" class="d-none">
                                <label class="form-label">Image Preview:</label>
                                <div>
                                    <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Partner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    } else {
        document.getElementById('image-preview').classList.add('d-none');
    }
});
</script>
@endsection