@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Executive Directors Management</h3>
                    <a href="{{ route('admin.executive-directors.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Executive Director
                    </a>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @if($executiveDirectors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Image</th>
                                        <th>Full Name</th>
                                        <th>Start Year</th>
                                        <th>End Year</th>
                                        <th>Status</th>
                                        <th>Posted By</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($executiveDirectors as $director)
                                        <tr>
                                            <td>
                                                @if($director->imagepath)
                                                    <img src="{{ asset('images/storage/' . $director->imagepath) }}" 
                                                         alt="{{ $director->full_name }}" 
                                                         class="img-thumbnail" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-user text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $director->full_name }}</td>
                                            <td>{{ $director->start_year }}</td>
                                            <td>{{ $director->end_year ?? 'Present' }}</td>
                                            <td>
                                                <span class="badge badge-{{ $director->status === 'Active' ? 'success' : 'secondary' }}">
                                                    {{ $director->status }}
                                                </span>
                                            </td>
                                            <td>{{ $director->posted_by }}</td>
                                            <td>{{ $director->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.executive-directors.show', $director->id) }}" 
                                                       class="btn btn-sm btn-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.executive-directors.edit', $director->id) }}" 
                                                       class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.executive-directors.destroy', $director->id) }}" 
                                                          method="POST" 
                                                          style="display: inline-block;"
                                                          onsubmit="return confirm('Are you sure you want to delete this executive director?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $executiveDirectors->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-user-tie fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No Executive Directors Found</h4>
                            <p class="text-muted">Start by adding your first executive director.</p>
                            <a href="{{ route('admin.executive-directors.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Executive Director
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

