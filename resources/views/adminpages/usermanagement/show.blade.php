@extends('adminpages.layouts.app')

@section('content')
<div class="min-vh-100" style="background-color: #ffffff;">
    <div class="container-fluid py-4 px-4">
        <div class="row">
            <div class="col-12">
                <!-- Header Section -->
                <div class="bg-white shadow-sm border rounded-3 p-4 mb-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <div>
                            <h2 class="mb-1 text-dark fw-bold text-break word-wrap-break-word">{{ $user->email }}</h2>
                            <p class="text-muted mb-0 small">View detailed user information and account status</p>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to List
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User Profile Card -->
                <div class="bg-white shadow-sm border rounded-3 overflow-hidden">
                    <div class="row g-0">
                        <!-- Left Column: Profile Image and Info -->
                        <div class="col-lg-3 col-md-4">
                            <div class="p-4">
                                <!-- Big Round Profile Image -->
                                <div class="text-center mb-4">
                                    @if($user->profile_image)
                                        <img src="{{ asset('images/storage/' . $user->profile_image) }}" 
                                             alt="{{ $user->username }}" 
                                             class="rounded-circle border shadow-sm mx-auto d-block user-profile-img-show"
                                             style="width: 250px; height: 250px; object-fit: cover; max-width: 100%;"
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto text-dark fw-bold user-profile-placeholder-show" 
                                             style="width: 250px; height: 250px; max-width: 100%; font-size: 6rem; display: none !important; color: #000;">
                                            {{ strtoupper(substr($user->email, 0, 1)) }}
                                        </div>
                                    @else
                                        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto text-dark fw-bold" 
                                             style="width: 250px; height: 250px; max-width: 100%; font-size: 6rem; color: #000;">
                                            {{ strtoupper(substr($user->email, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="mt-3 mb-3">
                                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-2">
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

                                <!-- Contact Information -->
                                <div class="border rounded-3 p-3 mb-3">
                                    <h6 class="mb-3 text-dark fw-semibold">
                                        <i class="fas fa-address-card text-info me-2"></i>Contact Information
                                    </h6>
                                    
                                    <div class="mb-3">
                                        <label class="small text-muted fw-medium mb-1">Email Address</label>
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-envelope text-muted me-3 mt-1 flex-shrink-0"></i>
                                            <span class="text-dark text-break word-wrap-break-word flex-grow-1">{{ $user->username }}</span>
                                        </div>
                                    </div>

                                    <div class="mb-0">
                                        <label class="small text-muted fw-medium mb-1">Telephone</label>
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-phone text-muted me-3 mt-1 flex-shrink-0"></i>
                                            <span class="text-dark text-break word-wrap-break-word flex-grow-1">{{ $user->telephone }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Account Information -->
                                <div class="border rounded-3 p-3">
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

                                    <div class="mb-0">
                                        <label class="small text-muted fw-medium mb-1">Role</label>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-tag text-muted me-3"></i>
                                            <span class="badge bg-{{ $roleColor }} bg-opacity-10 text-{{ $roleColor }} border border-{{ $roleColor }} border-opacity-25 px-3 py-2">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column: Quick Actions and Activity Summary -->
                        <div class="col-lg-9 col-md-8 border-start">
                            <div class="p-4">
                                <!-- Quick Actions Card -->
                                <div class="mb-4">
                                    <h6 class="mb-3 text-dark fw-semibold">
                                        <i class="fas fa-tools text-primary me-2"></i>Quick Actions
                                    </h6>
                                    <div class="row g-2">
                                        @if(auth()->user()->hasPermission('edit_users'))
                                        <div class="col-md-6">
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary w-100">
                                                <i class="fas fa-edit me-2"></i>Edit User Profile
                                            </a>
                                        </div>
                                        @endif
                                        @if(auth()->user()->id != $user->id)
                                        <div class="col-md-6">
                                            <a href="{{ route('admin.users.reset-password.form', $user) }}" class="btn btn-warning w-100">
                                                <i class="fas fa-key me-2"></i>Reset Password
                                            </a>
                                        </div>
                                        @endif
                                        <div class="col-md-6">
                                            <button class="btn btn-outline-{{ $user->status === 'active' ? 'danger' : 'success' }} w-100" 
                                                    data-bs-toggle="modal" data-bs-target="#statusModal">
                                                <i class="fas fa-{{ $user->status === 'active' ? 'ban' : 'check' }} me-2"></i>
                                                {{ $user->status === 'active' ? 'Block User' : 'Unblock User' }}
                                            </button>
                                        </div>
                                        @if(!$hasUploadedData && auth()->user()->id != $user->id)
                                        <div class="col-md-6">
                                            <button class="btn btn-danger w-100" 
                                                    data-bs-toggle="modal" data-bs-target="#deleteModal">
                                                <i class="fas fa-trash me-2"></i>Delete User
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- User Activity Summary -->
                                <div>
                                    <h6 class="mb-3 text-dark fw-semibold">
                                        <i class="fas fa-chart-bar text-info me-2"></i>User Activity Summary
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('admin.news.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-primary mb-2">
                                                        <i class="fas fa-newspaper fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['news'] ?? 0 }}</div>
                                                    <div class="text-muted small">News Posts</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('admin.publications.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-success mb-2">
                                                        <i class="fas fa-file-alt fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['publications'] ?? 0 }}</div>
                                                    <div class="text-muted small">Publications</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('shortcut-links.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-warning mb-2">
                                                        <i class="fas fa-link fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['links'] ?? 0 }}</div>
                                                    <div class="text-muted small">Shortcut Links</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('videopodcasts.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-danger mb-2">
                                                        <i class="fas fa-video fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['video_podcasts'] ?? 0 }}</div>
                                                    <div class="text-muted small">Video Podcasts</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('admin.window_applications.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-info mb-2">
                                                        <i class="fas fa-clipboard-list fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['applications'] ?? 0 }}</div>
                                                    <div class="text-muted small">Applications</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('admin.taasisevents.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-secondary mb-2">
                                                        <i class="fas fa-photo-video fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['photo_galleries'] ?? 0 }}</div>
                                                    <div class="text-muted small">Photo Galleries</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50">
                                                <div class="text-primary mb-2">
                                                    <i class="fas fa-images fa-2x"></i>
                                                </div>
                                                <div class="fw-bold text-dark fs-4">{{ $stats['photo_gallery_images'] ?? 0 }}</div>
                                                <div class="text-muted small">Gallery Images</div>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('admin.partners.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-info mb-2">
                                                        <i class="fas fa-handshake fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['partners'] ?? 0 }}</div>
                                                    <div class="text-muted small">Partners</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('faq.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-success mb-2">
                                                        <i class="fas fa-question-circle fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['faqs'] ?? 0 }}</div>
                                                    <div class="text-muted small">FAQs</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <a href="{{ route('admin.scholarships.index', ['user_id' => $user->id]) }}" class="text-decoration-none">
                                                <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50 hover-card">
                                                    <div class="text-warning mb-2">
                                                        <i class="fas fa-graduation-cap fa-2x"></i>
                                                    </div>
                                                    <div class="fw-bold text-dark fs-4">{{ $stats['scholarships'] ?? 0 }}</div>
                                                    <div class="text-muted small">Scholarships</div>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="col-6 col-md-4 col-lg-4">
                                            <div class="border rounded-3 p-3 text-center h-100 bg-light bg-opacity-50">
                                                <div class="text-dark mb-2">
                                                    <i class="fas fa-layer-group fa-2x"></i>
                                                </div>
                                                <div class="fw-bold text-dark fs-4">{{ ($stats['news'] ?? 0) + ($stats['publications'] ?? 0) + ($stats['links'] ?? 0) + ($stats['video_podcasts'] ?? 0) + ($stats['applications'] ?? 0) + ($stats['photo_galleries'] ?? 0) + ($stats['partners'] ?? 0) + ($stats['faqs'] ?? 0) + ($stats['scholarships'] ?? 0) }}</div>
                                                <div class="text-muted small">Total Items</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assigned Permissions Section -->
                @if(strtolower($user->role) === 'user' || strtolower($user->role) === 'admin')
                <div class="bg-white shadow-sm border rounded-3 p-4 mt-4">
                    <h6 class="mb-3 text-dark fw-semibold">
                        <i class="fas fa-shield-alt text-primary me-2"></i>
                        @if(strtolower($user->role) === 'admin')
                            Permissions (Administrator - All Permissions)
                        @else
                            Assigned Permissions
                        @endif
                    </h6>
                    
                    @if(strtolower($user->role) === 'admin')
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Administrator Role:</strong> This user has all permissions automatically. No specific permissions need to be assigned.
                        </div>
                    @else
                        @if(isset($permissionsByCategory) && $permissionsByCategory->count() > 0)
                            <div class="permission-list" style="max-height: 400px; overflow-y: auto;">
                                @foreach($permissionsByCategory as $category => $categoryPermissions)
                                <div class="mb-3">
                                    <h6 class="text-muted fw-semibold mb-2 border-bottom pb-2">
                                        <i class="fas fa-folder me-2"></i>{{ ucfirst(str_replace('_', ' ', $category)) }}
                                    </h6>
                                    <div class="ps-3">
                                        @foreach($categoryPermissions as $permission)
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <div>
                                                <strong class="text-dark">{{ $permission->display_name }}</strong>
                                                @if($permission->description)
                                                    <br><small class="text-muted">{{ $permission->description }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>No Permissions Assigned:</strong> This user has no specific permissions assigned. They will only have access to basic features.
                            </div>
                        @endif
                    @endif
                </div>
                @endif

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

                <!-- Delete User Modal -->
                @if(!$hasUploadedData && auth()->user()->id != $user->id)
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-danger text-white border-0">
                                <h5 class="modal-title fw-semibold" id="deleteModalLabel">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Confirm Delete User
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body py-4">
                                <p class="mb-0 text-dark">
                                    Are you sure you want to permanently delete user <strong>{{ $user->username }}</strong>? 
                                    This action cannot be undone and will remove the user from the system completely.
                                </p>
                            </div>
                            <div class="modal-footer border-0 bg-light">
                                <form action="{{ route('admin.users.delete', $user) }}" method="POST" class="d-flex gap-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-danger px-4">
                                        <i class="fas fa-trash me-1"></i>
                                        Delete User
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
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

/* Ensure only one profile element shows at a time on show page */
.user-profile-img-show,
.user-profile-placeholder-show {
    display: none;
}

.user-profile-img-show:not([style*="display: none"]) {
    display: block !important;
}

.user-profile-placeholder-show:not([style*="display: none"]) {
    display: flex !important;
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
@media (max-width: 991px) {
    .border-start {
        border-left: none !important;
        border-top: 1px solid #e9ecef !important;
    }
}

@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem;
    }
    
    .btn-lg {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
    }
    
    .rounded-circle {
        width: 180px !important;
        height: 180px !important;
    }
    
    .rounded-circle i {
        font-size: 4.5rem !important;
    }
    
    .rounded-circle.fw-bold {
        font-size: 4.5rem !important;
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

/* Text wrapping to prevent overlap */
.word-wrap-break-word {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    max-width: 100%;
}

/* Prevent header text overlap */
h2.text-break {
    word-wrap: break-word;
    word-break: break-word;
    overflow-wrap: break-word;
    max-width: 100%;
    margin-bottom: 0.5rem;
}

/* Ensure profile image container doesn't overlap */
.text-center.mb-4 {
    margin-bottom: 1.5rem !important;
}

/* Ensure circular placeholder text is visible */
.rounded-circle.fw-bold {
    position: relative;
    overflow: visible;
}

/* Hover effect for clickable statistics cards */
.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    background-color: #f8f9fa !important;
    border-color: #0d6efd !important;
}

.hover-card:hover .text-primary,
.hover-card:hover .text-success,
.hover-card:hover .text-warning,
.hover-card:hover .text-danger,
.hover-card:hover .text-info,
.hover-card:hover .text-secondary {
    transform: scale(1.1);
    transition: transform 0.3s ease;
}
</style>
@endsection