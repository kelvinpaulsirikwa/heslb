@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white px-4 py-5">
     <div class="pt-2">
         <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="fas fa-link me-3 text-secondary"></i>
                            Edit Details
                        </h1>
                        <p class="mb-0 text-muted fs-6">Edit event details</p>
                    </div>
                    <a href="{{ route('admin.taasisevents.index') }}" class="btn btn-dark btn-lg shadow-sm border-0">
                        <i class="fas fa-arrow-left me-2"></i>
                        <span class="fw-semibold">Back</span>
                    </a>
                </div>
            </div>
        </div>
<br>
        <x-admin-validation-errors />

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Edit Event Form -->

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Event Details</h5>
            </div>
            <div class="card-body">
                <form id="photoGalleryEditForm" action="{{ route('admin.taasisevents.update', $event->id) }}" method="POST" data-admin-validation="photoGallery">
                    @csrf
                    @method('PUT')
                    <x-admin-form-field
                        type="text"
                        name="name_of_event"
                        label="Event Name"
                        :value="old('name_of_event', $event->name_of_event)"
                        placeholder="Enter event name"
                        required
                        help="Give your event a descriptive name"
                    />
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">
                            <strong>Description</strong> <span class="text-danger">*</span>
                        </label>
                        <textarea name="description" 
                                  id="description"
                                  class="form-control @error('description') is-invalid @enderror" 
                                  rows="4"
                                  placeholder="Enter event description"
                                  required>{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Provide a detailed description of the event
                        </small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Update Event</button>
                        <a href="{{ route('admin.taasisevents.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Event Images Section -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Event Images</h5>
                <a href="{{ route('admin.taasisevents.images.add', $event->id) }}" class="btn btn-primary btn-sm">Add Image</a>
            </div>
            <div class="card-body">
                @if($event->images->count() > 0)
                    <div class="row">
                        @foreach($event->images as $image)
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-3">
                            <div class="card h-100">
                                <img src="{{ asset('images/storage/' . $image->image_link) }}" 
                                     class="card-img-top" 
                                     alt="Event Image"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <p class="card-text small text-muted flex-grow-1">
                                        {{ $image->description ?? 'No description' }}
                                    </p>
                                    <div class="d-flex gap-2 mt-auto">
                                        <a href="{{ route('admin.taasisevents.images.edit', $image->id) }}" 
                                           class="btn btn-warning btn-sm flex-fill">Edit</a>
                                        <form action="{{ route('admin.taasisevents.images.destroy', $image->id) }}" 
                                              method="POST" 
                                              class="flex-fill">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm w-100" 
                                                    onclick="return confirm('Are you sure you want to delete this image?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <p class="text-muted mb-3">No images added yet.</p>
                        <a href="{{ route('admin.taasisevents.images.add', $event->id) }}" class="btn btn-primary">
                            Add First Image
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Admin validation is automatically initialized by the data-admin-validation attribute
    // Additional event edit validation can be added here if needed
});
</script>
@endpush
@endsection