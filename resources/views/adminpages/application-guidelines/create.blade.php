@extends('adminpages.layouts.app')

@section('title', 'Create Application Guideline')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Create New Application Guideline</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.application-guidelines.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.application-guidelines.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="academic_year" class="form-label">Academic Year <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('academic_year') is-invalid @enderror" 
                                           id="academic_year" name="academic_year" value="{{ old('academic_year') }}" 
                                           placeholder="e.g., 2024/2025" required>
                                    @error('academic_year')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="4" 
                                              placeholder="Optional description of the guideline">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="publication_id" class="form-label">Select Guideline Publication <span class="text-danger">*</span></label>
                                    <select class="form-control @error('publication_id') is-invalid @enderror" 
                                            id="publication_id" name="publication_id" required>
                                        <option value="">Choose a publication...</option>
                                        
                                        @if($directGuidelines->count() > 0)
                                            <optgroup label="🎯 Direct Guidelines (Recommended)">
                                                @foreach($directGuidelines as $publication)
                                                    <option value="{{ $publication->id }}" {{ old('publication_id') == $publication->id ? 'selected' : '' }}>
                                                        {{ $publication->title }}
                                                        @if($publication->file_size)
                                                            ({{ $publication->formatted_file_size }})
                                                        @endif
                                                        - Direct Guideline
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                        
                                        @if($regularPublications->count() > 0)
                                            <optgroup label="📚 All Guideline Publications">
                                                @foreach($regularPublications as $publication)
                                                    <option value="{{ $publication->id }}" {{ old('publication_id') == $publication->id ? 'selected' : '' }}>
                                                        {{ $publication->title }}
                                                        @if($publication->file_size)
                                                            ({{ $publication->formatted_file_size }})
                                                        @endif
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    </select>
                                    <div class="form-text">
                                        <strong>Direct Guidelines</strong> are specifically marked for application guidelines. 
                                        <br>Select from existing guideline publications to avoid duplicates.
                                    </div>
                                    @error('publication_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">Settings</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="alert alert-success">
                                            <small>
                                                <i class="fas fa-info-circle"></i>
                                                <strong>Auto Current:</strong> New guidelines are automatically set as current. 
                                                Multiple guidelines can be current simultaneously.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.application-guidelines.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Create Guideline
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
