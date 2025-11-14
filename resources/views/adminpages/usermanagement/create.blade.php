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
@endpush
@endsection
