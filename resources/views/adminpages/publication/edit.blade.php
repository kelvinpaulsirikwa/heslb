{{-- resources/views/admin/publications/edit.blade.php --}}
@extends('adminpages.layouts.app')

@section('title', 'Edit Publication')

@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Publication</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Publications
                        </a>
                        <a href="{{ route('admin.publications.show', $publication) }}" class="btn btn-info">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.publications.update', $publication) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                        <div class="form-group">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('title') is-invalid @enderror" 
                                   id="title" 
                                   name="title" 
                                   value="{{ old('title', $publication->title) }}" 
                                   required>
                            @error('title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Category --}}
                        <div class="form-group">
                            <label for="category_id">Category <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $publication->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Current File Info --}}
                        @if($publication->hasFile())
                            <div class="alert alert-info">
                                <h6><i class="fas fa-file"></i> Current File:</h6>
                                <strong>{{ $publication->file_name }}</strong><br>
                                <small>
                                    Type: {{ $publication->file_type }} | 
                                    Size: {{ $publication->formatted_file_size }} | 
                                    Downloads: {{ $publication->download_count ?? 0 }}
                                </small><br>
                                <a href="{{ $publication->download_url }}" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="fas fa-download"></i> Download Current File
                                </a>
                            </div>
                        @endif

                        {{-- File Upload --}}
                        <div class="form-group">
                            <label for="file">Replace File (Optional)</label>
                            <div class="custom-file">
                                <input type="file" 
                                       class="custom-file-input @error('file') is-invalid @enderror" 
                                       id="file" 
                                       name="file" 
                                       accept=".pdf,.doc,.docx,.xls,.xlsx">
                                <label class="custom-file-label" for="file">Choose new file</label>
                            </div>
                            <small class="form-text text-muted">
                                Leave empty to keep current file. Allowed types: PDF, DOC, DOCX, XLS, XLSX. Maximum size: 100MB
                            </small>
                            @error('file')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Enter publication description...">{{ old('description', $publication->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                    

                        {{-- Direct Guideline Option (only for Guidelines category) --}}
                        <div class="form-group" id="direct-guideline-section" style="display: none;">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_direct_guideline" 
                                       name="is_direct_guideline" 
                                       value="1" 
                                       {{ old('is_direct_guideline', $publication->is_direct_guideline) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_direct_guideline">
                                    <strong>🎯 Set as Direct Guideline</strong>
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                This will mark this publication as a direct guideline for application guidelines. Only available for Guidelines category.
                                @if($publication->is_direct_guideline)
                                    <br><span class="badge bg-success">Currently set as direct guideline</span>
                                @endif
                            </small>
                        </div>

                        {{-- Status --}}
                        <div class="form-group">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" 
                                       class="custom-control-input" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1" 
                                       {{ old('is_active', $publication->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">Active</label>
                            </div>
                            <small class="form-text text-muted">
                                When active, the publication will be visible to users.
                            </small>
                        </div>

                        {{-- Additional Info --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Created At</label>
                                    <input type="text" class="form-control" value="{{ $publication->created_at}}" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Updated</label>
                                    <input type="text" class="form-control" value="{{ $publication->updated_at}}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Publication
                        </button>
                        <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                        <div class="float-right">
                            <form action="{{ route('admin.publications.destroy', $publication) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Publication
                                </button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Update file input label when file is selected
    $('#file').on('change', function() {
        const fileName = $(this)[0].files[0]?.name || 'Choose new file';
        $(this).next('.custom-file-label').text(fileName);
    });

    // Handle category change to show/hide direct guideline option
    function toggleDirectGuidelineOption() {
        const selectedOption = $('#category_id option:selected');
        const isGuidelinesCategory = selectedOption.text().toLowerCase().includes('guideline');
        
        if (isGuidelinesCategory) {
            $('#direct-guideline-section').show();
        } else {
            $('#direct-guideline-section').hide();
            $('#is_direct_guideline').prop('checked', false); // Uncheck if not guidelines category
        }
    }
    
    $('#category_id').on('change', toggleDirectGuidelineOption);
    // Check on page load
    toggleDirectGuidelineOption();

    // Form validation
    $('form:not(.delete-form)').on('submit', function(e) {
        const fileInput = $('#file')[0];
        const file = fileInput.files[0];
        
        if (file) {
            // Check file size (100MB = 100 * 1024 * 1024 bytes)
            const maxSize = 100 * 1024 * 1024;
            if (file.size > maxSize) {
                e.preventDefault();
                alert('File size must be less than 100MB.');
                return false;
            }
            
            // Check file type
            const allowedTypes = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
            const fileExtension = file.name.split('.').pop().toLowerCase();
            if (!allowedTypes.includes(fileExtension)) {
                e.preventDefault();
                alert('Please select a valid file type (PDF, DOC, DOCX, XLS, XLSX).');
                return false;
            }
        }
    });

    // Delete confirmation
    $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        if (confirm('Are you sure you want to delete this publication? This action cannot be undone and will also delete the associated file.')) {
            this.submit();
        }
    });
});
</script>
@endpush
@endsection