@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Board Member</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.board-of-directors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.board-of-directors.update', $boardMember->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $boardMember->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="image">Image</label>
                            <div class="row">
                                <div class="col-md-6">
                                    @if($boardMember->image)
                                        <div class="mb-3">
                                            <label>Current Image:</label>
                                            <div>
                                                <img src="{{ asset('images/storage/' . $boardMember->image) }}" 
                                                     alt="{{ $boardMember->name }}" 
                                                     class="img-thumbnail" 
                                                     style="max-width: 200px; max-height: 200px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="custom-file">
                                        <input type="file" 
                                               class="custom-file-input @error('image') is-invalid @enderror" 
                                               id="image" 
                                               name="image" 
                                               accept="image/*"
                                               required>
                                        <label class="custom-file-label" for="image">Choose new image...</label>
                                    </div>
                                    <small class="form-text text-muted">
                                        Supported formats: JPEG, PNG, JPG, GIF. Max size: 5MB
                                    </small>
                                    @error('image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="ckeditor form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="8" 
                                      placeholder="Enter board member description...">{{ old('description', $boardMember->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Board Member
                            </button>
                            <a href="{{ route('admin.board-of-directors.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Update file input label when file is selected
document.getElementById('image').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Choose new image...';
    e.target.nextElementSibling.textContent = fileName;
});
</script>
@endsection
