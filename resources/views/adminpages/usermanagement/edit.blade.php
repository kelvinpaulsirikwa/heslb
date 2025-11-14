@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #ffffff;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">Edit User</h2>
                            <p class="text-muted mb-0 small">Update user information and permissions</p>
                        </div>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="fas fa-arrow-left me-2"></i>Back to List
                        </a>
                    </div>
                </div>

                <!-- Error Messages -->
                @if($errors->any())
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

                <!-- Form Card -->
                <div class="bg-white shadow-sm border rounded-3 p-4">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                                <i class="fas fa-user me-2 text-primary"></i>Basic Information
                            </h5>
                            
                            <div class="row g-3">
                                <!-- Username -->
                                <div class="col-md-6">
                                    <label for="username" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-user-circle me-2 text-primary"></i>Username
                                    </label>
                                    <input type="text" id="username" name="username" class="form-control form-control-lg" 
                                           value="{{ old('username', $user->username) }}" placeholder="Enter username..." required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-envelope me-2 text-info"></i>Email Address
                                    </label>
                                    <input type="email" id="email" name="email" class="form-control form-control-lg" 
                                           value="{{ old('email', $user->email) }}" placeholder="Enter email address..." required>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                                <i class="fas fa-address-card me-2 text-success"></i>Contact Information
                            </h5>
                            
                            <div class="row g-3">
                                <!-- Profile Image -->
                                <div class="col-12">
                                    <label for="profile_image" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-image me-2 text-secondary"></i>Profile Image
                                    </label>
                                    <input type="file" id="profile_image" name="profile_image" accept="image/*" class="form-control">
                                    @if($user->profile_image)
                                        <div class="mt-2">
                                            <img src="{{ asset('images/storage/'.$user->profile_image) }}" 
                                                 alt="Profile" 
                                                 class="rounded border shadow-sm"
                                                 style="height: 100px; width: 100px; object-fit: cover;"
                                                 onerror="handleImageError(this)">
                                            <div class="mt-1">
                                                <small class="text-muted">Current profile image</small>
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <div class="rounded border bg-light d-flex align-items-center justify-content-center" 
                                                 style="height: 100px; width: 100px;">
                                                <i class="fas fa-user text-muted" style="font-size: 2rem;"></i>
                                            </div>
                                            <div class="mt-1">
                                                <small class="text-muted">No profile image uploaded</small>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <!-- Telephone -->
                                <div class="col-md-6">
                                    <label for="telephone" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-phone me-2 text-success"></i>Telephone
                                    </label>
                                    <input type="text" id="telephone" name="telephone" class="form-control form-control-lg" 
                                           value="{{ old('telephone', $user->telephone) }}" placeholder="Enter telephone number...">
                                </div>


                            </div>
                        </div>

                        <!-- Security Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                                <i class="fas fa-shield-alt me-2 text-danger"></i>Security & Permissions
                            </h5>
                            
                            <div class="row g-3">
                               

                                <!-- Role -->
                                <div class="col-md-6">
                                    <label for="role" class="form-label fw-semibold text-dark">
                                        <i class="fas fa-user-tag me-2 text-secondary"></i>User Role
                                    </label>
                                    <select id="role" name="role" class="form-select form-select-lg" required>
                                        <option value="">Select Role</option>
                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex flex-column flex-md-row gap-3 pt-3 border-top">
                            <button type="submit" class="btn btn-primary btn-lg px-5">
                                <i class="fas fa-save me-2"></i>Update User
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info btn-lg px-5">
                                <i class="fas fa-eye me-2"></i>View Profile
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg px-5">
                                <i class="fas fa-times me-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for government edit user */
.form-control, .form-select {
    border-radius: 0.375rem;
    border: 1px solid #e0e0e0;
    padding: 0.75rem 1rem;
    transition: all 0.2s ease;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.1);
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

/* Section headers */
.border-bottom {
    border-color: #e9ecef !important;
}

h5.fw-semibold {
    font-size: 1.1rem;
}

/* NIDA input styling */
.font-monospace {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.5px;
}

/* Form sections */
.mb-4:last-of-type {
    margin-bottom: 1.5rem !important;
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
    
    .form-label {
        font-size: 0.9rem;
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

/* Input focus improvements */
.form-control:focus,
.form-select:focus {
    border-width: 2px;
}

/* Icon alignment */
.form-label i {
    opacity: 0.8;
}
</style>
@endsection