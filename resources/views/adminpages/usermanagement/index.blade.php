@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #ffffff;">
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold">User Management</h2>
                            <p class="text-muted mb-0 small">Manage system users and their permissions</p>
                        </div>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success px-4 py-2">
                            <i class="fas fa-user-plus me-2"></i>Add New User
                        </a>
                    </div>
                </div>

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Users Table Card -->
                <div class="bg-white shadow-sm border rounded-3 overflow-hidden">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="width: 60px;">ID</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 150px;">User</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 200px;">Email</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 130px;">Telephone</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="width: 100px;">Role</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="width: 100px;">Status</th>
                                    <th class="fw-semibold text-dark py-3 px-4 border-0" style="min-width: 300px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-bottom">
                                    <td class="py-3 px-4 align-middle">
                                        <span class="badge bg-light text-dark border">{{ $user->id }}</span>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex align-items-center">
                                            @if($user->profile_image)
                                                <img src="{{ asset('images/storage/' . $user->profile_image) }}" 
                                                     alt="{{ $user->username }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 32px; height: 32px; object-fit: cover;"
                                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            @endif
                                            @if(!$user->profile_image)
                                                <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px;">
                                                    <i class="fas fa-user text-primary small"></i>
                                                </div>
                                            @endif
                                            <span class="fw-medium text-dark">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="text-dark">
                                            <i class="fas fa-envelope text-muted me-2 small"></i>{{ $user->email }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="text-dark">
                                            <i class="fas fa-phone text-muted me-2 small"></i>{{ $user->telephone }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        @php
                                            $roleColors = [
                                                'admin' => 'danger',
                                                'staff' => 'primary',
                                                'user' => 'secondary'
                                            ];
                                            $roleColor = $roleColors[strtolower($user->role)] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border border-{{ $roleColor }} border-opacity-25">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        @if($user->status === 'active')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                                                <i class="fas fa-check-circle me-1 small"></i>Active
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">
                                                <i class="fas fa-exclamation-circle me-1 small"></i>{{ ucfirst($user->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline-info btn-sm px-2">
                                                <i class="fas fa-eye me-1"></i>View
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-outline-primary btn-sm px-2">
                                                <i class="fas fa-edit me-1"></i>Edit
                                            </a>
                                            @if(auth()->user()->id != $user->id)
                                            <a href="{{ route('admin.users.reset-password.form', $user) }}" class="btn btn-outline-warning btn-sm px-2">
                                                <i class="fas fa-key me-1"></i>Reset
                                            </a>
                                            @endif
                                            
                                            <!-- Block/Unblock Button with Modal -->
                                            <button class="btn btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }} btn-sm px-2" 
                                                    data-bs-toggle="modal" data-bs-target="#statusModal{{ $user->id }}">
                                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }} me-1"></i>
                                                {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                                            </button>

                                            <!-- Status Change Modal -->
                                            <div class="modal fade" id="statusModal{{ $user->id }}" tabindex="-1" aria-labelledby="statusModalLabel{{ $user->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content border-0 shadow">
                                                        <div class="modal-header bg-light border-0">
                                                            <h5 class="modal-title fw-semibold text-dark" id="statusModalLabel{{ $user->id }}">
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
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State (if no users) -->
                    @if($users->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                            <h4 class="text-muted mt-3">No Users Found</h4>
                            <p class="text-muted mb-4">Start by creating the first system user.</p>
                            <a href="{{ route('users.create') }}" class="btn btn-success px-4">
                                <i class="fas fa-user-plus me-2"></i>Create First User
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Pagination (if applicable) -->
                @if(method_exists($users, 'links'))
                    <div class="d-flex justify-content-center mt-4">
                        <div class="bg-white border rounded-3 px-3 py-2 shadow-sm">
                            {{ $users->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Custom styles for government user management */
.table th {
    font-size: 0.875rem;
    letter-spacing: 0.5px;
}

.table td {
    vertical-align: middle;
    font-size: 0.875rem;
}

.btn-sm {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}

.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.4rem 0.8rem;
    border-radius: 0.375rem;
}

/* User avatar styling */
.rounded-circle {
    flex-shrink: 0;
}

/* Responsive design */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    
    .btn-sm {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
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

/* Card hover effects */
.bg-white {
    transition: all 0.2s ease;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

/* Button improvements */
.btn {
    border-radius: 0.375rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-outline-primary:hover,
.btn-outline-info:hover,
.btn-outline-warning:hover,
.btn-outline-danger:hover,
.btn-outline-success:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Status indicators */
.badge i {
    font-size: 0.7em;
}

/* NIDA formatting */
.font-monospace {
    font-family: 'Courier New', monospace;
    font-weight: 500;
}

/* Action buttons container */
.d-flex.flex-wrap {
    gap: 0.25rem !important;
}

/* Telephone and email styling */
.fas.fa-envelope,
.fas.fa-phone {
    opacity: 0.6;
}
</style>
@endsection