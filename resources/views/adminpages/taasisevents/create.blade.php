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
                            Create New Event
                        </h1>
                        <p class="mb-0 text-muted fs-6">Create Name Event and Description</p>
                    </div>
                   
                </div>
            </div>
        </div>

        <x-admin-validation-errors />

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Event Details</h5>
            </div>
            <div class="card-body">
                <form id="photoGalleryForm" action="{{ route('admin.taasisevents.store') }}" method="POST" data-admin-validation="photoGallery">
                    @csrf
                    <x-admin-form-field
                        type="text"
                        name="name_of_event"
                        label="Event Name"
                        :value="old('name_of_event')"
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
                                  required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Provide a detailed description of the event
                        </small>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Create Event</button>
                        <a href="{{ route('admin.taasisevents.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('resources/js/admin-validation.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Admin validation is automatically initialized by the data-admin-validation attribute
    // Additional event-specific validation can be added here if needed
});
</script>
@endpush
@endsection