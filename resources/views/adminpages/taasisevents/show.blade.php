@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white px-4 py-5">



    <!-- Event Info Card -->
       <div class="row mb-5">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="fas fa-link me-3 text-secondary"></i>
                            Event Details
                        </h1>
                        <p class="mb-0 text-muted fs-6">View event details</p>
                    </div>
                    <a href="{{ route('admin.taasisevents.index') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Event Information</h5>
        </div>
        <div class="card-body">
            <p><strong>Name:</strong> {{ $event->name_of_event }}</p>
            <p><strong>Description:</strong> {{ $event->description }}</p>
            <p><strong>Posted By:</strong> {{ $event->posted_by ? 'Admin User' : 'N/A' }}</p>
        </div>
    </div>

    <!-- Event Images Card -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Event Images</h5>
        </div>
        <div class="card-body">
            @if($event->images->count() > 0)
                <div class="row">
                    @foreach($event->images as $image)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset('images/storage/' . $image->image_link) }}" 
                                     class="card-img-top" 
                                     style="height: 200px; object-fit: cover;" 
                                     alt="Event Image">
                                <div class="card-body p-2">
                                    <small class="text-muted d-block">
                                        {{ $image->description ?? 'No description' }}
                                    </small>
                                </div>
                                <div class="card-footer text-center p-2">
                                    <a href="{{ route('admin.taasisevents.images.edit', $image->id) }}" 
                                       class="btn btn-sm btn-primary">Edit</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted">No images uploaded for this event.</p>
            @endif
        </div>
    </div>

    <!-- Back Button -->
   
</div>
@endsection
