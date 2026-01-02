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
                <div class="bg-white shadow-sm border rounded-3 overflow-hidden">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-0">
                            <!-- Left Column: Profile Image and Info -->
                            <div class="col-lg-3 col-md-4">
                                <div class="p-4">
                                    <!-- Big Round Profile Image -->
                                    <div class="text-center mb-4">
                                        @if($user->profile_image)
                                            <img src="{{ asset('images/storage/' . $user->profile_image) }}" 
                                                 alt="{{ $user->username }}" 
                                                 class="rounded-circle border shadow-sm mx-auto d-block user-profile-img-edit"
                                                 style="width: 250px; height: 250px; object-fit: cover; max-width: 100%;"
                                                 id="profileImagePreview"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto text-dark fw-bold user-profile-placeholder-edit" 
                                                 style="width: 250px; height: 250px; max-width: 100%; font-size: 6rem; display: none !important; color: #000;"
                                                 id="profileImagePlaceholder">
                                                {{ strtoupper(substr($user->email, 0, 1)) }}
                                            </div>
                                        @else
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center mx-auto text-dark fw-bold" 
                                                 style="width: 250px; height: 250px; max-width: 100%; font-size: 6rem; color: #000;"
                                                 id="profileImagePlaceholder">
                                                {{ strtoupper(substr($user->email, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div class="mt-3 mb-3">
                                            <label for="profile_image" class="form-label fw-semibold text-dark d-block">
                                                <i class="fas fa-image me-2 text-secondary"></i>Change Profile Image
                                            </label>
                                            <input type="file" id="profile_image" name="profile_image" accept="image/*" class="form-control form-control-sm">
                                        </div>
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
                                        </div>
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="border rounded-3 p-3 mb-3">
                                        <h6 class="mb-3 text-dark fw-semibold">
                                            <i class="fas fa-address-card text-info me-2"></i>Contact Information
                                        </h6>
                                        
                                        <div class="mb-3">
                                            <label for="email" class="small text-muted fw-medium mb-1">Email Address</label>
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-envelope text-muted me-3 mt-2 flex-shrink-0"></i>
                                                <input type="email" id="email" name="email" class="form-control text-break word-wrap-break-word flex-grow-1" 
                                                       value="{{ old('email', $user->email) }}" required>
                                            </div>
                                        </div>

                                        <div class="mb-0">
                                            <label for="telephone" class="small text-muted fw-medium mb-1">Telephone</label>
                                            <div class="d-flex align-items-start">
                                                <i class="fas fa-phone text-muted me-3 mt-2 flex-shrink-0"></i>
                                                <input type="text" id="telephone" name="telephone" class="form-control text-break word-wrap-break-word flex-grow-1" 
                                                       value="{{ old('telephone', $user->telephone) }}">
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
                                            <label for="role" class="small text-muted fw-medium mb-1">Role</label>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-tag text-muted me-3"></i>
                                                <select id="role" name="role" class="form-select flex-grow-1" required>
                                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column: Form Fields -->
                            <div class="col-lg-9 col-md-8 border-start">
                                <div class="p-4">
                                    <!-- Basic Information Section -->
                                    <div class="mb-4">
                                        <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                                            <i class="fas fa-user me-2 text-primary"></i>Basic Information
                                        </h5>
                                        
                                        <div class="row g-3">
                                            <!-- Username -->
                                            <div class="col-md-12">
                                                <label for="username" class="form-label fw-semibold text-dark">
                                                    <i class="fas fa-user-circle me-2 text-primary"></i>Username
                                                </label>
                                                <input type="text" id="username" name="username" class="form-control form-control-lg" 
                                                       value="{{ old('username', $user->username) }}" placeholder="Enter username..." required>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Security Section -->
                                    <div class="mb-4">
                                        <h5 class="mb-3 text-dark fw-semibold border-bottom pb-2">
                                            <i class="fas fa-shield-alt me-2 text-danger"></i>Security & Permissions
                                        </h5>
                                        
                                        <div class="alert alert-info mb-3">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <strong>Note:</strong> Administrators have all permissions automatically. Users can have specific permissions assigned.
                                        </div>
                                        
                                        <!-- User Permissions Section (only for user role) -->
                                        <div id="permissionsSection" style="display: {{ strtolower($user->role) === 'user' ? 'block' : 'none' }};">
                                            <div class="bg-light border rounded-3 p-4">
                                                <div class="d-flex justify-content-between align-items-center mb-3">
                                                    <h6 class="text-dark fw-semibold mb-0">
                                                        <i class="fas fa-shield-alt me-2 text-primary"></i>User-Specific Permissions
                                                    </h6>
                                                    <div class="d-flex gap-2">
                                                        <span class="badge bg-success" id="currentCount">
                                                            <span id="currentCountNumber">{{ count($userPermissionIds ?? []) }}</span> Current
                                                        </span>
                                                        <span class="badge bg-primary" id="selectedCount">
                                                            <span id="selectedCountNumber">{{ count($userPermissionIds ?? []) }}</span> Selected
                                                        </span>
                                                    </div>
                                                </div>
                                                <p class="text-muted small mb-3">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Permissions marked with <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1"><small>Current</small></span> are already assigned to this user. Select or deselect permissions as needed.
                                                </p>
                                                
                                                <div class="permission-list" style="max-height: 400px; overflow-y: auto;">
                                                    @foreach($permissions ?? [] as $category => $categoryPermissions)
                                                    <div class="mb-3">
                                                        <h6 class="text-muted fw-semibold mb-2 border-bottom pb-2">
                                                            <i class="fas fa-folder me-2"></i>{{ ucfirst(str_replace('_', ' ', $category)) }}
                                                        </h6>
                                                        <div class="ps-3">
                                                            @foreach($categoryPermissions as $permission)
                                                            @php
                                                                $isChecked = in_array($permission->id, $userPermissionIds ?? []);
                                                                $isOriginal = $isChecked; // Mark original permissions
                                                            @endphp
                                                            <div class="form-check mb-2 permission-item {{ $isChecked ? 'permission-selected' : '' }} {{ $isOriginal ? 'permission-original' : '' }}" 
                                                                 data-original="{{ $isOriginal ? 'true' : 'false' }}">
                                                                <input class="form-check-input permission-checkbox" 
                                                                       type="checkbox" 
                                                                       name="permissions[]" 
                                                                       value="{{ $permission->id }}" 
                                                                       id="permission_edit_{{ $permission->id }}"
                                                                       {{ $isChecked ? 'checked' : '' }}
                                                                       data-original-state="{{ $isOriginal ? 'true' : 'false' }}">
                                                                <label class="form-check-label" for="permission_edit_{{ $permission->id }}">
                                                                    @if($isChecked)
                                                                        <i class="fas fa-check-circle text-success me-2"></i>
                                                                    @else
                                                                        <i class="far fa-circle text-muted me-2"></i>
                                                                    @endif
                                                                    <strong>{{ $permission->display_name }}</strong>
                                                                    @if($isOriginal)
                                                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 ms-2">
                                                                            <small><i class="fas fa-check me-1"></i>Current</small>
                                                                        </span>
                                                                    @endif
                                                                    @if($permission->description)
                                                                        <br><small class="text-muted">{{ $permission->description }}</small>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
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
                                </div>
                            </div>
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
    
    .rounded-circle {
        width: 180px !important;
        height: 180px !important;
    }
    
    .rounded-circle.fw-bold {
        font-size: 4.5rem !important;
    }
}

/* Ensure only one profile element shows at a time on edit page */
.user-profile-img-edit,
.user-profile-placeholder-edit {
    display: none;
}

.user-profile-img-edit:not([style*="display: none"]) {
    display: block !important;
}

.user-profile-placeholder-edit:not([style*="display: none"]) {
    display: flex !important;
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

.permission-list::-webkit-scrollbar {
    width: 8px;
}

.permission-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.permission-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.permission-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

/* Selected permission styling */
.permission-selected {
    background-color: #e7f3ff;
    border-left: 3px solid #0d6efd;
    padding: 0.5rem;
    border-radius: 0.25rem;
    margin-bottom: 0.5rem !important;
}

/* Original/Current permission styling */
.permission-original {
    background-color: #d1e7dd;
    border-left: 3px solid #198754;
}

.permission-original.permission-selected {
    background-color: #d1e7dd;
    border-left: 3px solid #198754;
}

.permission-item {
    transition: all 0.2s ease;
    padding: 0.25rem;
    border-radius: 0.25rem;
}

.permission-item:hover {
    background-color: #f8f9fa;
}

.permission-original:hover {
    background-color: #c3e6d0;
}

.permission-item .form-check-label {
    cursor: pointer;
}

.permission-item .form-check-label i {
    font-size: 0.9rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.getElementById('role');
    const permissionsSection = document.getElementById('permissionsSection');
    const profileImageInput = document.getElementById('profile_image');
    const profileImagePreview = document.getElementById('profileImagePreview');
    const profileImagePlaceholder = document.getElementById('profileImagePlaceholder');
    
    // Role change handler
    function togglePermissionsSection() {
        if (roleSelect.value === 'user') {
            permissionsSection.style.display = 'block';
        } else {
            permissionsSection.style.display = 'none';
            // Uncheck all permission checkboxes when role is not "user"
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
            // Update styling and count
            updateSelectedCount();
        }
    }
    
    // Listen for role changes
    if (roleSelect) {
        roleSelect.addEventListener('change', togglePermissionsSection);
    }
    
    // Update selected count and styling when checkboxes change
    function updateSelectedCount() {
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        let selectedCount = 0;
        let currentCount = 0;
        
        checkboxes.forEach(checkbox => {
            const permissionItem = checkbox.closest('.permission-item');
            const label = permissionItem.querySelector('label');
            const icon = label.querySelector('i');
            const originalState = checkbox.getAttribute('data-original-state') === 'true';
            const currentBadge = label.querySelector('.badge');
            
            // Count original permissions (always)
            if (originalState) {
                currentCount++;
            }
            
            if (checkbox.checked) {
                selectedCount++;
                permissionItem.classList.add('permission-selected');
                if (icon) {
                    icon.className = 'fas fa-check-circle text-success me-2';
                }
                
                // Show current badge only if it was originally checked
                if (originalState && !currentBadge) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 ms-2';
                    badge.innerHTML = '<small><i class="fas fa-check me-1"></i>Current</small>';
                    label.querySelector('strong').after(badge);
                }
            } else {
                permissionItem.classList.remove('permission-selected');
                if (icon) {
                    icon.className = 'far fa-circle text-muted me-2';
                }
                
                // Keep current badge visible if it was originally checked (even if now unchecked)
                // This helps user see what they're removing
                if (originalState && !currentBadge) {
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 ms-2';
                    badge.innerHTML = '<small><i class="fas fa-check me-1"></i>Current</small>';
                    label.querySelector('strong').after(badge);
                } else if (!originalState && currentBadge) {
                    // Remove badge if it wasn't original
                    currentBadge.remove();
                }
            }
        });
        
        const countElement = document.getElementById('selectedCountNumber');
        if (countElement) {
            countElement.textContent = selectedCount;
        }
        
        const currentCountElement = document.getElementById('currentCountNumber');
        if (currentCountElement) {
            currentCountElement.textContent = currentCount;
        }
    }
    
    // Listen for checkbox changes
    document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
    
    // Initial count update
    updateSelectedCount();
    
    // Profile image preview handler
    if (profileImageInput) {
        profileImageInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (profileImagePreview) {
                        profileImagePreview.src = e.target.result;
                        profileImagePreview.style.display = 'block';
                        if (profileImagePlaceholder) {
                            profileImagePlaceholder.style.display = 'none !important';
                        }
                    } else {
                        // Create new image element if it doesn't exist
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'rounded-circle border shadow-sm mx-auto d-block user-profile-img-edit';
                        img.style.cssText = 'width: 250px; height: 250px; object-fit: cover; max-width: 100%;';
                        img.id = 'profileImagePreview';
                        profileImageInput.parentElement.insertBefore(img, profileImageInput);
                        if (profileImagePlaceholder) {
                            profileImagePlaceholder.style.display = 'none !important';
                        }
                    }
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Ensure only one shows on page load
    if (profileImagePreview && profileImagePlaceholder) {
        if (profileImagePreview.complete && profileImagePreview.naturalHeight !== 0) {
            // Image loaded successfully
            profileImagePlaceholder.style.display = 'none !important';
        } else {
            // Image failed to load or doesn't exist
            profileImagePreview.style.display = 'none';
            profileImagePlaceholder.style.display = 'flex !important';
        }
    }
});
</script>
@endsection