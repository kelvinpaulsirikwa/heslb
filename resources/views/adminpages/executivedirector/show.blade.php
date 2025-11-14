@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Executive Director Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.executive-directors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($executiveDirector->imagepath)
                                    <img src="{{ asset('images/storage/' . $executiveDirector->imagepath) }}" 
                                         alt="{{ $executiveDirector->full_name }}" 
                                         class="img-fluid rounded shadow" 
                                         style="max-width: 300px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" 
                                         style="width: 300px; height: 300px; margin: 0 auto;">
                                        <i class="fas fa-user-tie fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Full Name:</th>
                                    <td>{{ $executiveDirector->full_name }}</td>
                                </tr>
                                <tr>
                                    <th>Start Year:</th>
                                    <td>{{ $executiveDirector->start_year }}</td>
                                </tr>
                                <tr>
                                    <th>End Year:</th>
                                    <td>{{ $executiveDirector->end_year ?? 'Present' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="badge badge-{{ $executiveDirector->status === 'Active' ? 'success' : 'secondary' }}">
                                            {{ $executiveDirector->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Posted By:</th>
                                    <td>{{ $executiveDirector->posted_by }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $executiveDirector->created_at->format('F d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $executiveDirector->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Term Description:</h5>
                            <div class="card">
                                <div class="card-body">
                                    @if($executiveDirector->term_description)
                                        {!! $executiveDirector->term_description !!}
                                    @else
                                        <span class="text-muted">No term description provided</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.executive-directors.edit', $executiveDirector->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Executive Director
                        </a>
                        <form action="{{ route('admin.executive-directors.destroy', $executiveDirector->id) }}" 
                              method="POST" 
                              style="display: inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this executive director?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Executive Director
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

