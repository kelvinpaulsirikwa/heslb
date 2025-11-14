@extends('adminpages.layouts.app')

@section('title', 'Partner Details')

@section('content')
<div class="container-fluid  bg-white px-4 py-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Partner Details</h3>
                    <div>
                        <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('admin.partners.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($partner->image_path)
                                <div class="text-center mb-4">
                                    <img src="{{ asset('images/storage/partner_image/' . $partner->image_path) }}" 
                                         alt="{{ $partner->name }}" 
                                         class="img-fluid rounded shadow"
                                         style="max-width: 100%; height: auto;">
                                </div>
                            @else
                                <div class="text-center mb-4">
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <span class="text-muted">
                                            <i class="fas fa-image fa-3x"></i><br>
                                            No Image Available
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="30%">ID:</th>
                                    <td>{{ $partner->id }}</td>
                                </tr>
                                <tr>
                                    <th>Partner Name:</th>
                                    <td><strong>{{ $partner->name }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Acronym Name:</th>
                                    <td>{{ $partner->acronym_name ?? 'Not specified' }}</td>
                                </tr>
                                <tr>
                                    <th>Website Link:</th>
                                    <td>
                                        @if($partner->link)
                                            <a href="{{ $partner->link }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> Visit Website
                                            </a>
                                        @else
                                            Not specified
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Posted By:</th>
                                    <td>
                                        <span class="badge bg-info">{{ $partner->user->username ?? 'Unknown User' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td>{{ $partner->created_at->format('F j, Y g:i A') }}</td>
                                </tr>
                                <tr>
                                    <th>Updated At:</th>
                                    <td>{{ $partner->updated_at->format('F j, Y g:i A') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="d-flex justify-content-between">
                        <div>
                            <form action="{{ route('admin.partners.destroy', $partner) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this partner? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash"></i> Delete Partner
                                </button>
                            </form>
                        </div>
                        
                        <div>
                            <a href="{{ route('admin.partners.edit', $partner) }}" class="btn btn-warning">
                                <i class="fas fa-edit"></i> Edit Partner
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection