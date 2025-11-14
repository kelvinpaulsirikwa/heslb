@extends('adminpages.layouts.app')

@section('title', 'Edit Partner')

@section('content')
<div class="container-fluid  bg-white px-4 py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Edit Partner: {{ $partner->name }}</h3>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body">
                    <form action="{{ route('admin.partners.update', $partner) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Partner Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $partner->name) }}" 
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
                                           value="{{ old('acronym_name', $partner->acronym_name) }}">
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
                                   value="{{ old('link', $partner->link) }}"
                                   placeholder="https://example.com">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($partner->image_path)
                            <div class="mb-3">
                                <label class="form-label">Current Image:</label>
                                <div>
                                    <img src="{{ asset('images/storage/partner_image/' . $partner->image_path) }}" 
                                         alt="{{ $partner->name }}" 
                                         class="img-thumbnail" 
                                         style="max-width: 200px;">
                                </div>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="image" class="form-label">
                                {{ $partner->image_path ? 'Change Partner Logo/Image' : 'Partner Logo/Image' }}
                            </label>
                            <input type="file" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   accept="image/*">
                            <div class="form-text">Supported formats: JPEG, PNG, JPG, GIF. Max size: 100MB</div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div id="image-preview" class="d-none">
                                <label class="form-label">New Image Preview:</label>
                                <div>
                                    <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Partner
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