@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid p-4 bg-white mt-2">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-user-plus me-2 text-primary"></i>
                        Add New User
                    </h2>
                    <p class="text-muted mb-0">Create a new user account with appropriate permissions</p>
                </div>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Back to Users List
                </a>
            </div>

            <!-- Validation Errors -->
            <x-admin-validation-errors :errors="$errors" />

            <!-- Create Form Card -->
            <div class="bg-white shadow-sm border rounded-3 p-4">
                <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" id="userManagementForm" data-admin-validation="userManagement">
                    @csrf
                    
                    <!-- Basic Information Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-gray-700 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Basic Information
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-admin-form-field 
                                name="username" 
                                label="Username" 
                                type="text" 
                                placeholder="Enter username"
                                required="true"
                                help="Username must be unique and cannot exceed 255 characters"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-admin-form-field 
                                name="email" 
                                label="Email Address" 
                                type="email" 
                                placeholder="Enter email address"
                                required="true"
                                help="Email must be unique and valid format"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-admin-form-field 
                                name="telephone" 
                                label="Telephone Number" 
                                type="text" 
                                placeholder="Enter telephone number"
                                help="Optional: Maximum 20 characters"
                            />
                        </div>
                        <div class="col-md-6">
                            <x-admin-form-field 
                                name="role" 
                                label="User Role" 
                                type="select" 
                                required="true"
                                :options="[
                                    'user' => 'User',
                                    'admin' => 'Administrator'
                                ]"
                                help="Select the appropriate role for this user"
                            />
                        </div>
                    </div>

                    <!-- Inline script to toggle permissions immediately -->
                    <script>
                    (function() {
                        var attempts = 0;
                        var maxAttempts = 10;
                        
                        function checkRoleAndToggle() {
                            attempts++;
                            var roleSelect = document.querySelector('#field_role') || 
                                            document.querySelector('select[name="role"]') ||
                                            document.querySelector('select.form-select[name="role"]');
                            var permissionsSection = document.getElementById('permissionsSection');
                            
                            if (roleSelect && permissionsSection) {
                                console.log('Found elements! Role value:', roleSelect.value);
                                
                                function showHidePermissions() {
                                    if (roleSelect.value === 'user') {
                                        console.log('Showing permissions section');
                                        permissionsSection.style.display = 'block';
                                        permissionsSection.style.visibility = 'visible';
                                    } else {
                                        console.log('Hiding permissions section');
                                        permissionsSection.style.display = 'none';
                                        permissionsSection.style.visibility = 'hidden';
                                    }
                                }
                                
                                // Check immediately
                                showHidePermissions();
                                
                                // Listen for changes
                                roleSelect.addEventListener('change', showHidePermissions);
                                roleSelect.addEventListener('input', showHidePermissions);
                                
                                // Also check on click (for dropdowns)
                                roleSelect.addEventListener('click', function() {
                                    setTimeout(showHidePermissions, 200);
                                });
                                
                                // Check periodically in case value changes programmatically
                                setInterval(showHidePermissions, 500);
                                
                            } else if (attempts < maxAttempts) {
                                console.log('Elements not found, retrying... Attempt', attempts);
                                setTimeout(checkRoleAndToggle, 200);
                            } else {
                                console.error('Could not find role select or permissions section after', maxAttempts, 'attempts');
                            }
                        }
                        
                        // Start checking immediately
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', checkRoleAndToggle);
                        } else {
                            checkRoleAndToggle();
                        }
                    })();
                    </script>

                    <!-- User-Specific Permissions Section (only for user role) -->
                    <div class="row mt-4" id="permissionsSection" style="display: none;">
                        <div class="col-12">
                            <div class="bg-light border rounded-3 p-4">
                                <h5 class="text-gray-700 mb-3">
                                    <i class="fas fa-shield-alt me-2 text-primary"></i>User-Specific Permissions
                                </h5>
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Select specific permissions for this user. Administrators automatically have all permissions.
                                </p>
                                
                                <div class="permission-list" style="max-height: 400px; overflow-y: auto;">
                                    @if(isset($permissions) && $permissions->count() > 0)
                                        @foreach($permissions as $category => $categoryPermissions)
                                        <div class="mb-3">
                                            <h6 class="text-muted fw-semibold mb-2 border-bottom pb-2">
                                                <i class="fas fa-folder me-2"></i>{{ ucfirst(str_replace('_', ' ', $category)) }}
                                            </h6>
                                            <div class="ps-3">
                                                @foreach($categoryPermissions as $permission)
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" 
                                                           type="checkbox" 
                                                           name="permissions[]" 
                                                           value="{{ $permission->id }}" 
                                                           id="permission_{{ $permission->id }}">
                                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                                        <strong>{{ $permission->display_name }}</strong>
                                                        @if($permission->description)
                                                            <br><small class="text-muted">{{ $permission->description }}</small>
                                                        @endif
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endforeach
                                    @else
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            No permissions found. Please run the PermissionSeeder.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Image Section -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-gray-700 mb-3">
                                <i class="fas fa-image me-2"></i>Profile Image
                            </h5>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <x-admin-form-field 
                                name="profile_image" 
                                label="Profile Image" 
                                type="file" 
                                accept="image/*"
                                help="Optional: JPEG, PNG, JPG, or GIF format. Maximum 100MB"
                            />
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-light">
                                    <i class="fas fa-times me-2"></i>
                                    Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Create User
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/admin-validation.js') }}"></script>
<script>
// Wait for everything to load
window.addEventListener('load', function() {
    setTimeout(function() {
        initPermissionToggle();
    }, 100);
});

function initPermissionToggle() {
    // Try multiple selectors to find the role select
    let roleSelect = document.querySelector('#field_role');
    if (!roleSelect) {
        roleSelect = document.querySelector('select[name="role"]');
    }
    if (!roleSelect) {
        // Try finding by class
        roleSelect = document.querySelector('.form-select[name="role"]');
    }
    
    const permissionsSection = document.getElementById('permissionsSection');
    
    if (!roleSelect) {
        console.error('Role select field not found. Trying again...');
        setTimeout(initPermissionToggle, 500);
        return;
    }
    
    if (!permissionsSection) {
        console.error('Permissions section not found');
        return;
    }
    
    console.log('Role select found:', roleSelect);
    console.log('Permissions section found:', permissionsSection);
    
    function togglePermissionsSection() {
        const selectedValue = roleSelect.value;
        console.log('Role changed to:', selectedValue);
        
        if (selectedValue === 'user') {
            console.log('SHOWING permissions section');
            permissionsSection.style.display = 'block';
            permissionsSection.style.visibility = 'visible';
            permissionsSection.style.opacity = '1';
            permissionsSection.style.height = 'auto';
            permissionsSection.classList.add('fade-in');
        } else {
            console.log('HIDING permissions section');
            permissionsSection.style.display = 'none';
            permissionsSection.style.visibility = 'hidden';
            permissionsSection.style.opacity = '0';
            permissionsSection.style.height = '0';
            // Uncheck all permission checkboxes when role is not "user"
            document.querySelectorAll('input[name="permissions[]"]').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    }
    
    // Check immediately
    togglePermissionsSection();
    
    // Listen for all possible events
    roleSelect.addEventListener('change', function(e) {
        console.log('Change event fired:', e.target.value);
        togglePermissionsSection();
    });
    
    roleSelect.addEventListener('input', function(e) {
        console.log('Input event fired:', e.target.value);
        togglePermissionsSection();
    });
    
    // Also watch for clicks (in case of programmatic changes)
    roleSelect.addEventListener('click', function() {
        setTimeout(togglePermissionsSection, 50);
    });
    
    // Use MutationObserver as fallback
    const observer = new MutationObserver(function(mutations) {
        togglePermissionsSection();
    });
    
    observer.observe(roleSelect, {
        attributes: true,
        attributeFilter: ['value', 'selectedIndex']
    });
}
</script>
<style>
#permissionsSection {
    transition: all 0.3s ease;
    overflow: hidden;
}

#permissionsSection.fade-in {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush
@endsection
