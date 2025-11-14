@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add New Executive Director</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.executive-directors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.executive-directors.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="full_name">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" 
                                           name="full_name" 
                                           value="{{ old('full_name') }}" 
                                           required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="start_year">Start Year <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control @error('start_year') is-invalid @enderror" 
                                           id="start_year" 
                                           name="start_year" 
                                           value="{{ old('start_year') }}" 
                                           min="1900" 
                                           max="{{ date('Y') + 10 }}" 
                                           required>
                                    @error('start_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="end_year">End Year</label>
                                    <input type="number" 
                                           class="form-control @error('end_year') is-invalid @enderror" 
                                           id="end_year" 
                                           name="end_year" 
                                           value="{{ old('end_year') }}" 
                                           min="1900" 
                                           max="{{ date('Y') + 10 }}">
                                    <small class="form-text text-muted">Must be greater than start year. Leave empty if currently active</small>
                                    @error('end_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="">Select Status</option>
                                        <option value="Active" {{ old('status') === 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Former" {{ old('status') === 'Former' ? 'selected' : '' }}>Former</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="imagepath">Image</label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('imagepath') is-invalid @enderror" 
                                       id="imagepath" 
                                       name="imagepath" 
                                       accept="image/*">
                                <label class="custom-file-label" for="imagepath">Choose image...</label>
                            </div>
                            <small class="form-text text-muted">
                                Supported formats: JPEG, PNG, JPG, GIF. Max size: 5MB
                            </small>
                            @error('imagepath')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="term_description">Term Description</label>
                            <textarea class="ckeditor form-control @error('term_description') is-invalid @enderror" 
                                      id="term_description" 
                                      name="term_description" 
                                      rows="8" 
                                      placeholder="Enter term description...">{{ old('term_description') }}</textarea>
                            @error('term_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Executive Director
                            </button>
                            <a href="{{ route('admin.executive-directors.index') }}" class="btn btn-secondary">
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
document.getElementById('imagepath').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Choose image...';
    e.target.nextElementSibling.textContent = fileName;
});

// Update end_year min value when start_year changes
document.getElementById('start_year').addEventListener('input', function() {
    const startYear = parseInt(this.value);
    const endYearInput = document.getElementById('end_year');
    
    if (startYear && !isNaN(startYear)) {
        endYearInput.setAttribute('min', startYear + 1);
        const currentEndYear = parseInt(endYearInput.value);
        
        // Clear end_year if it's less than or equal to start_year
        if (currentEndYear && currentEndYear <= startYear) {
            endYearInput.value = '';
        }
    } else {
        endYearInput.setAttribute('min', 1900);
    }
});

// Validate end_year on input
document.getElementById('end_year').addEventListener('input', function() {
    const startYear = parseInt(document.getElementById('start_year').value);
    const endYear = parseInt(this.value);
    
    if (startYear && endYear && !isNaN(startYear) && !isNaN(endYear)) {
        if (endYear <= startYear) {
            this.setCustomValidity('End year must be greater than start year');
            this.classList.add('is-invalid');
        } else {
            this.setCustomValidity('');
            this.classList.remove('is-invalid');
        }
    } else {
        this.setCustomValidity('');
        this.classList.remove('is-invalid');
    }
});
</script>
@endsection

