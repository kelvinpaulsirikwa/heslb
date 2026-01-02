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
                        @if(auth()->user()->hasPermission('create_users'))
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success px-4 py-2">
                            <i class="fas fa-user-plus me-2"></i>Add New User
                        </a>
                        @endif
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
                            <thead class="bg-secondary" style="position: relative; z-index: 10;">
                                <tr>
                                    <th class="fw-bold text-white py-3 px-4 border-0" style="min-width: 150px; font-size: 1rem; white-space: nowrap;">User</th>
                                    <th class="fw-bold text-white py-3 px-4 border-0" style="min-width: 200px; font-size: 1rem; white-space: nowrap;">Email</th>
                                    <th class="fw-bold text-white py-3 px-4 border-0" style="min-width: 130px; font-size: 1rem; white-space: nowrap;">Telephone</th>
                                    <th class="fw-bold text-white py-3 px-4 border-0" style="width: 100px; font-size: 1rem; white-space: nowrap;">Role</th>
                                    <th class="fw-bold text-white py-3 px-4 border-0" style="width: 100px; font-size: 1rem; white-space: nowrap;">Status</th>
                                    <th class="fw-bold text-white py-3 px-4 border-0" style="min-width: 150px; font-size: 1rem; white-space: nowrap;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr class="border-bottom">
                                    <td class="py-3 px-4 align-middle">
                                        <div class="d-flex align-items-center">
                                            @php
                                                $profileImageSrc = $user->profile_image 
                                                    ? asset('images/storage/' . $user->profile_image) 
                                                    : asset('images/static_files/nodp.png');
                                            @endphp
                                            <img src="{{ $profileImageSrc }}" 
                                                 alt="{{ $user->username }}" 
                                                 class="rounded-circle me-2 border" 
                                                 style="width: 40px; height: 40px; object-fit: cover; display: block;"
                                                 onerror="this.onerror=null; this.src='{{ asset('images/static_files/nodp.png') }}';">
                                            <span class="fw-medium text-dark">{{ $user->username }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="text-dark text-break word-wrap-break-word" style="max-width: 200px; line-height: 1.4;">
                                            {{ $user->email }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        <div class="text-dark">
                                            {{ $user->telephone }}
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 align-middle">
                                        @php
                                            $roleColors = [
                                                'admin' => 'danger',
                                                'staff' => 'primary',
                                                'user' => 'dark'
                                            ];
                                            $roleColor = $roleColors[strtolower($user->role)] ?? 'dark';
                                        @endphp
                                        @if(strtolower($user->role) === 'admin')
                                            <span class="badge bg-danger text-white border border-danger px-3 py-2 fw-semibold">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        @elseif(strtolower($user->role) === 'user')
                                            <span class="badge bg-dark text-white border border-dark px-3 py-2 fw-semibold">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        @else
                                            <span class="badge bg-{{ $roleColor }} text-white border border-{{ $roleColor }} px-3 py-2 fw-semibold">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        @endif
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
                                        <div class="d-grid gap-1" style="grid-template-columns: repeat(2, 1fr);">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm px-2 text-center">
                                                View
                                            </a>
                                            @if(auth()->user()->hasPermission('edit_users'))
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary btn-sm px-2 text-center">
                                                Edit
                                            </a>
                                            @endif
                                            @if(auth()->user()->id != $user->id && auth()->user()->hasPermission('reset_user_password'))
                                            <a href="{{ route('admin.users.reset-password.form', $user) }}" class="btn btn-warning btn-sm px-2 text-center">
                                                Reset
                                            </a>
                                            @endif
                                            
                                            <!-- Block/Unblock Button with Modal -->
                                            <button class="btn btn-{{ $user->status === 'active' ? 'danger' : 'success' }} btn-sm px-2 text-center" 
                                                    data-bs-toggle="modal" data-bs-target="#statusModal{{ $user->id }}">
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

/* Ensure only one profile image displays */
td .rounded-circle {
    display: block !important;
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
.d-grid {
    gap: 0.25rem !important;
    min-width: 200px;
}

.d-grid .btn {
    white-space: nowrap;
    width: 100%;
}

/* Telephone and email styling */
.fas.fa-envelope,
.fas.fa-phone {
    opacity: 0.6;
}

/* Text wrapping for email */
.word-wrap-break-word {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    line-height: 1.4;
    max-height: 2.8em;
    overflow: hidden;
}

/* Make header more visible */
.bg-secondary {
    background-color: #6c757d !important;
}

thead {
    position: sticky;
    top: 0;
    z-index: 100;
}

thead th {
    font-weight: 700 !important;
    letter-spacing: 0.5px;
    background-color: #6c757d !important;
    color: #ffffff !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.2);
}

/* Ensure header text is visible */
thead.bg-secondary th {
    color: #ffffff !important;
    background-color: #6c757d !important;
}

/* Make role badge more visible */
.badge.bg-dark {
    background-color: #495057 !important;
    color: #ffffff !important;
    border-color: #343a40 !important;
}

.badge.bg-danger {
    background-color: #dc3545 !important;
    color: #ffffff !important;
    border-color: #dc3545 !important;
}
</style>
@endsection