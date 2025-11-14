{{-- resources/views/admin/publications/show.blade.php --}}
@extends('adminpages.layouts.app')

@section('title', 'View Publication')

@section('content')
    <div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Publication Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.publications.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Publications
                        </a>
                        <a href="{{ route('admin.publications.edit', $publication) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ $publication->download_url }}" target="_blank" class="btn btn-success">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            {{-- Basic Information --}}
                            <h4>{{ $publication->title }}</h4>
                            
                            @if($publication->description)
                                <p class="text-muted">{{ $publication->description }}</p>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Category:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $publication->category->name }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Status:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $publication->is_active ? 'success' : 'secondary' }}
                                        {{ $publication->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>

                            @if($publication->publication_date)
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Publication Date:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $publication->publication_date->format('F j, Y') }}
                                    </div>
                                </div>
                            @endif

                            @if($publication->version)
                                <div class="row mb-3">
                                    <div class="col-sm-3">
                                        <strong>Version:</strong>
                                    </div>
                                    <div class="col-sm-9">
                                        {{ $publication->version }}
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Created:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $publication->created_at }}
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Last Updated:</strong>
                                </div>
                                <div class="col-sm-9">
                                    {{ $publication->updated_at }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            {{-- File Information Card --}}
                            <div class="card bg-light">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">
                                        <i class="fas fa-file"></i> File Information
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($publication->hasFile())
                                        <div class="mb-3 text-center">
                                            <div class="file-icon mb-2">
                                                @switch(strtolower($publication->file_type))
                                                    @case('pdf')
                                                        <i class="fas fa-file-pdf fa-4x text-danger"></i>
                                                        @break
                                                    @case('doc')
                                                    @case('docx')
                                                        <i class="fas fa-file-word fa-4x text-primary"></i>
                                                        @break
                                                    @case('xls')
                                                    @case('xlsx')
                                                        <i class="fas fa-file-excel fa-4x text-success"></i>
                                                        @break
                                                    @default
                                                        <i class="fas fa-file fa-4x text-secondary"></i>
                                                @endswitch
                                            </div>
                                            <h6>{{ $publication->file_name }}</h6>
                                        </div>

                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Type:</strong></td>
                                                <td>{{ strtoupper($publication->file_type) }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Size:</strong></td>
                                                <td>{{ $publication->formatted_file_size }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Downloads:</strong></td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        {{ $publication->download_count ?? 0 }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>

                                        <div class="text-center">
                                            <a href="{{ $publication->download_url }}" 
                                               target="_blank" 
                                               class="btn btn-success btn-block">
                                                <i class="fas fa-download"></i> Download File
                                            </a>
                                        </div>
                                    @else
                                        <div class="text-center text-muted">
                                            <i class="fas fa-exclamation-triangle fa-3x mb-2"></i>
                                            <p>No file available</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="{{ route('admin.publications.edit', $publication) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Publication
                            </a>
                        </div>
                        <div class="col-md-6 text-right">
                            <form action="{{ route('admin.publications.destroy', $publication) }}" 
                                  method="POST" 
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Publication
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Category Information --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Category Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Name:</strong> {{ $publication->category->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $publication->category->is_active ? 'success' : 'secondary' }}">
                                {{ $publication->category->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    @if($publication->category->description)
                        <div class="row mt-2">
                            <div class="col-12">
                                <strong>Description:</strong><br>
                                {{ $publication->category->description }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
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