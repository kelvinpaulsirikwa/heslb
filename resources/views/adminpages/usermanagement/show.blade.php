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
                            <h2 class="mb-1 text-dark fw-bold">User Profile</h2>
                            <p class="text-muted mb-0 small">View detailed user information and account status</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-edit me-2"></i>Edit User
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Card -->
                <div class="bg-white shadow-sm border rounded-3 overflow-hidden">
                    <!-- User Header Section -->
                    <div class="p-4 border-bottom bg-light bg-opacity-50">
                        <div class="d-flex align-items-center">
                            @if($user->profile_image)
                                <img src="{{ asset('images/storage/' . $user->profile_image) }}" 
                                     alt="{{ $user->username }}" 
                                     class="rounded-circle me-4 border shadow-sm"
                                     style="width: 80px; height: 80px; object-fit: cover;"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            @endif
                            @if(!$user->profile_image)
                                <div class="rounded-circle bg-primary bg-opacity-15 d-flex align-items-center justify-content-center me-4" 
                                     style="width: 80px; height: 80px;">
                                    <i class="fas fa-user text-primary" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h3 class="mb-2 text-dark fw-bold">{{ $user->username }}</h3>
                                <div class="d-flex flex-wrap align-items-center gap-3">
                                    @php
                                        $roleColors = [
                                            'admin' => 'danger',
                                            'staff' => 'primary',
                                            'user' => 'secondary'
                                        ];
                                        $roleColor = $roleColors[strtolower($user->role)] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border border-{{ $roleColor }} border-opacity-25 px-3 py-2">
                                        <i class="fas fa-user-tag me-1"></i>{{ ucfirst($user->role) }}
                                    </span>
                                    @if($user->status === 'active')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-3 py-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>{{ ucfirst($user->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Details Section -->
                    <div class="p-4">
                        <div class="row g-4">
                            <!-- Contact Information -->
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <h6 class="mb-3 text-dark fw-semibold">
                                        <i class="fas fa-address-card text-info me-2"></i>Contact Information
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label class="small text-muted fw-medium mb-1">Email Address</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-envelope text-muted me-3"></i>
                                            <span class="text-dark">{{ $user->email }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="small text-muted fw-medium mb-1">Telephone</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-phone text-muted me-3"></i>
                                            <span class="text-dark">{{ $user->telephone }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Information -->
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <h6 class="mb-3 text-dark fw-semibold">
                                        <i class="fas fa-cog text-secondary me-2"></i>Account Information
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label class="small text-muted fw-medium mb-1">User ID</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-hashtag text-muted me-3"></i>
                                            <span class="badge bg-light text-dark border px-3 py-2">{{ $user->id }}</span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mt-4">
                    <h6 class="mb-3 text-dark fw-semibold">
                        <i class="fas fa-tools text-primary me-2"></i>Quick Actions
                    </h6>
                    <div class="d-flex flex-column flex-md-row gap-3">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-edit me-2"></i>Edit User Profile
                        </a>
                        @if(auth()->user()->id != $user->id)
                        <a href="{{ route('admin.users.reset-password.form', $user) }}" class="btn btn-warning btn-lg px-4">
                            <i class="fas fa-key me-2"></i>Reset Password
                        </a>
                        @endif
                        <button class="btn btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }} btn-lg px-4" 
                                data-bs-toggle="modal" data-bs-target="#statusModal">
                            <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }} me-2"></i>
                            {{ $user->status === 'active' ? 'Block User' : 'Unblock User' }}
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                            <i class="fas fa-list me-2"></i>View All Users
                        </a>
                    </div>
                </div>

                <!-- Status Change Modal -->
                <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-light border-0">
                                <h5 class="modal-title fw-semibold text-dark" id="statusModalLabel">
                                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                                    Confirm {{ $user->status === 'active' ? 'Block' : 'Unblock' }} User
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-4">
                                <p class="mb-0 text-dark">
                                    Are you sure you want to {{ $user->status === 'active' ? 'block' : 'unblock' }} 
                                    user <strong>{{ $user->username }}</strong>? This will 
                                    {{ $user->status === 'active' ? 'prevent them from accessing the system' : 'restore their access to the system' }}.
                                </p>
                            </div>
                            <div class="modal-footer border-0 bg-light">
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-{{ $user->status === 'active' ? 'danger' : 'success' }} px-4">
                                        <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }} me-1"></i>
                                        {{ $user->status === 'active' ? 'Block' : 'Unblock' }} User
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for government user view */
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

.badge {
    font-size: 0.8rem;
    font-weight: 500;
    border-radius: 0.375rem;
}

/* Card improvements */
.bg-white {
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.border {
    border-color: #e9ecef !important;
}

/* User avatar styling */
.rounded-circle {
    flex-shrink: 0;
}

/* Typography improvements */
.fw-bold {
    font-weight: 600;
}

.fw-semibold {
    font-weight: 500;
}

/* Icon styling */
.fas {
    flex-shrink: 0;
}

/* NIDA number styling */
.font-monospace {
    font-family: 'Courier New', monospace;
    font-weight: 500;
    font-size: 0.9rem;
}

/* Responsive design */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .rounded-circle {
        width: 60px !important;
        height: 60px !important;
    }
    
    .rounded-circle i {
        font-size: 1.5rem !important;
    }
}

/* Modal improvements */
.modal-content {
    border-radius: 0.5rem;
}

.modal-header {
    border-radius: 0.5rem 0.5rem 0 0;
}

.modal-footer {
    border-radius: 0 0 0.5rem 0.5rem;
}

/* Information cards */
.border.rounded-3 {
    background-color: #fafafa;
    border-color: #e9ecef !important;
}

/* Label styling */
label.small {
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Status and role badges */
.badge i {
    font-size: 0.8em;
}
</style>
@endsection