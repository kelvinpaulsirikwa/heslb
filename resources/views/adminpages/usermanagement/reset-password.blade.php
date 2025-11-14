@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #ffffff;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">Reset Password</h2>
                            <p class="text-muted mb-0 small">Reset password for user: <span class="fw-medium text-dark">{{ $user->username }}</span></p>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                            <div>
                                <strong>Please correct the following errors:</strong>
                                <ul class="mb-0 mt-2 ps-3">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- User Info Card -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-15 d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="fas fa-user text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 text-dark fw-semibold">{{ $user->username }}</h5>
                            <p class="mb-0 text-muted small">
                                <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Password Reset Form Card -->
                <div class="bg-white shadow-sm border rounded-3 p-4">
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-danger bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-key text-danger" style="font-size: 2rem;"></i>
                        </div>
                        <h4 class="text-dark fw-semibold mb-2">Generate Temporary Password</h4>
                        <p class="text-muted small mb-0">A temporary password will be generated and the user will be forced to change it on next login.</p>
                    </div>

                    <form action="{{ route('admin.users.reset-password', $user) }}" method="POST">
                        @csrf
                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-md-row gap-3 pt-3 border-top">
                            <button type="submit" class="btn btn-danger btn-lg px-5">
                                <i class="fas fa-key me-2"></i>Generate Temporary Password
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info btn-lg px-5">
                                <i class="fas fa-user me-2"></i>View User
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                </div>

                <!-- Security Notice -->
                <div class="bg-light border rounded-3 p-3 mt-4">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-info-circle text-info me-2 mt-1"></i>
                        <div class="small text-muted">
                            <strong>Security Notice:</strong> The user will be notified of this password change and should update their password on next login for security purposes.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Password Toggle Script -->
<script>
    function togglePassword(fieldId) {
        const passwordField = document.getElementById(fieldId);
        const eyeIcon = document.getElementById(fieldId + 'Eye');
        
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.className = 'fas fa-eye-slash text-muted';
        } else {
            passwordField.type = 'password';
            eyeIcon.className = 'fas fa-eye text-muted';
        }
    }
</script>

<style>
/* Custom styles for government password reset */
.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #e0e0e0;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
}

.form-label {
    margin-bottom: 0.75rem;
    font-size: 0.95rem;
}

.form-label i {
    width: 20px;
}

.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
    letter-spacing: 0.3px;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 1rem;
}

.alert {
    border-radius: 0.5rem;
}

/* Password toggle button */
.btn-link {
    border: none;
    background: none;
    padding: 0;
    text-decoration: none;
}

.btn-link:hover {
    transform: none;
    box-shadow: none;
}

/* User avatar in header */
.rounded-circle {
    flex-shrink: 0;
}

/* Security notice styling */
.bg-light {
    background-color: #f8f9fa !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .form-control, .form-select {
        font-size: 0.9rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .rounded-circle {
        width: 50px !important;
        height: 50px !important;
    }
    
    .rounded-circle i {
        font-size: 1.2rem !important;
    }
}

/* Card improvements */
.bg-white {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

/* Form improvements */
.border-top {
    border-color: #e9ecef !important;
}

/* Position relative for password toggle */
.position-relative {
    position: relative;
}
</style>
@endsection