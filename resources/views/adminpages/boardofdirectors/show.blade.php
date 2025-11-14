@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Board Member Details</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.board-of-directors.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                @if($boardMember->image)
                                    <img src="{{ asset('images/storage/' . $boardMember->image) }}" 
                                         alt="{{ $boardMember->name }}" 
                                         class="img-fluid rounded shadow" 
                                         style="max-width: 300px;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded shadow" 
                                         style="width: 300px; height: 300px; margin: 0 auto;">
                                        <i class="fas fa-user fa-5x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">Name:</th>
                                    <td>{{ $boardMember->name }}</td>
                                </tr>
                                <tr>
                                    <th>Posted By:</th>
                                    <td>{{ $boardMember->posted_by }}</td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $boardMember->created_at->format('F d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $boardMember->updated_at->format('F d, Y \a\t h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td>
                                        @if($boardMember->description)
                                            {{ $boardMember->description }}
                                        @else
                                            <span class="text-muted">No description provided</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.board-of-directors.edit', $boardMember->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Board Member
                        </a>
                        <form action="{{ route('admin.board-of-directors.destroy', $boardMember->id) }}" 
                              method="POST" 
                              style="display: inline-block;"
                              onsubmit="return confirm('Are you sure you want to delete this board member?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Delete Board Member
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
