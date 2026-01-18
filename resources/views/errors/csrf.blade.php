@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Security Error (419)
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        <strong>Page Expired</strong>
                    </div>
                    
                    <p class="mb-3">
                        The security token for this form has expired. This is a security measure to protect against unauthorized access.
                    </p>
                    
                    <div class="mb-3">
                        <h6>What happened?</h6>
                        <ul class="small">
                            <li>You left the page open for too long</li>
                            <li>Your browser cookies were cleared</li>
                            <li>You're using multiple tabs</li>
                        </ul>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <a href="{{ request()->header('Referer', url('/')) }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left"></i> Go Back
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Home
                        </a>
                        <button onclick="location.reload()" class="btn btn-outline-primary">
                            <i class="fas fa-sync"></i> Refresh Page
                        </button>
                    </div>
                    
                    @if(config('app.debug'))
                        <hr>
                        <div class="small text-muted">
                            <strong>Debug Info:</strong> {{ $message ?? 'CSRF token validation failed' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
