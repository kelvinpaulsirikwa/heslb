@extends('adminpages.layouts.app')

@section('title', 'View Application Guideline')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Application Guideline Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.application-guidelines.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-4">
                                <h4 class="text-primary">{{ $guideline->title }}</h4>
                                @if($guideline->is_current)
                                    <span class="badge bg-success">Current Guideline</span>
                                @endif
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Academic Year:</strong></div>
                                <div class="col-sm-9">{{ $guideline->academic_year }}</div>
                            </div>

                            @if($guideline->description)
                                <div class="row mb-3">
                                    <div class="col-sm-3"><strong>Description:</strong></div>
                                    <div class="col-sm-9">{{ $guideline->description }}</div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Status:</strong></div>
                                <div class="col-sm-9">
                                    @if($guideline->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </div>
                            </div>

                            @if($guideline->file_original_name)
                                <div class="row mb-3">
                                    <div class="col-sm-3"><strong>File:</strong></div>
                                    <div class="col-sm-9">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="fas fa-file-alt text-primary"></i>
                                            <span>{{ $guideline->file_original_name }}</span>
                                            <span class="text-muted">({{ $guideline->formatted_file_size }})</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Created By:</strong></div>
                                <div class="col-sm-9">{{ $guideline->creator->name ?? 'Unknown' }}</div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Created At:</strong></div>
                                <div class="col-sm-9">{{ $guideline->created_at->format('M d, Y H:i A') }}</div>
                            </div>

                            @if($guideline->updated_by && $guideline->updater)
                                <div class="row mb-3">
                                    <div class="col-sm-3"><strong>Last Updated By:</strong></div>
                                    <div class="col-sm-9">{{ $guideline->updater->name }}</div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-3"><strong>Last Updated:</strong></div>
                                <div class="col-sm-9">{{ $guideline->updated_at->format('M d, Y H:i A') }}</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('admin.application-guidelines.edit', $guideline->id) }}" 
                                           class="btn btn-warning">
                                            <i class="fas fa-edit"></i> Edit Guideline
                                        </a>

                                        @if($guideline->file_path)
                                            <a href="{{ route('admin.application-guidelines.download', $guideline->id) }}" 
                                               class="btn btn-success">
                                                <i class="fas fa-download"></i> Download File
                                            </a>
                                        @endif

                                        @if(!$guideline->is_current)
                                            <form action="{{ route('admin.application-guidelines.set-current', $guideline->id) }}" 
                                                  method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-primary w-100" 
                                                        onclick="return confirm('Are you sure you want to set this as the current guideline?')">
                                                    <i class="fas fa-star"></i> Set as Current
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.application-guidelines.destroy', $guideline->id) }}" 
                                              method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this guideline: {{ addslashes($guideline->title) }}? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger w-100">
                                                <i class="fas fa-trash"></i> Delete Guideline
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @if($guideline->file_path)
                                <div class="card mt-3">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">File Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="small mb-1"><strong>Original Name:</strong></p>
                                        <p class="small text-muted mb-2">{{ $guideline->file_original_name }}</p>
                                        
                                        <p class="small mb-1"><strong>File Size:</strong></p>
                                        <p class="small text-muted mb-2">{{ $guideline->formatted_file_size }}</p>
                                        
                                        <p class="small mb-1"><strong>File Type:</strong></p>
                                        <p class="small text-muted mb-2">{{ $guideline->file_type }}</p>
                                        
                                        <p class="small mb-1"><strong>Storage Path:</strong></p>
                                        <p class="small text-muted">{{ $guideline->file_path }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
