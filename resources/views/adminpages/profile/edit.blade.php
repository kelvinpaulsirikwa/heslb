@extends('adminpages.layouts.app')

@section('content')
<div class="container-fluid bg-white px-4 py-5">
    <div class="pt-2">
        <div class="col-12">
            <div class="bg-white rounded-3 p-4 shadow-sm border border-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h2 mb-2 fw-bold text-dark">
                            <i class="fas fa-user me-3 text-secondary"></i>
                            Edit Profile
                        </h1>
                        <p class="mb-0 text-muted fs-6">Update your username, phone number, and password</p>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <x-admin-validation-errors />

        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0">Profile Details</h5>
            </div>
            <div class="card-body">
                <form id="profileUpdateForm" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" data-admin-validation="profileUpdate">
                    @csrf
                    @method('PUT')

                    <!-- Username -->
                    <x-admin-form-field
                        type="text"
                        name="username"
                        label="Username"
                        :value="old('username', $user->username)"
                        placeholder="Enter your username"
                        required
                    />

                    <!-- Profile Image -->
                    <x-admin-form-field
                        type="file"
                        name="profile_image"
                        label="Profile Image"
                        accept="image/*"
                    />
                    @if($user->profile_image)
                        <div class="mt-2">
                            <img src="{{ asset('images/storage/'.$user->profile_image) }}" 
                                 alt="Profile" 
                                 style="height: 100px; width: 100px; object-fit: cover; border-radius: 8px;"
                                 onerror="handleImageError(this)">
                        </div>
                    @endif

                    <!-- Phone Number -->
                    <x-admin-form-field
                        type="text"
                        name="telephone"
                        label="Phone Number (Optional)"
                        :value="old('telephone', $user->telephone)"
                        placeholder="Enter your phone number"
                    />

                    <hr>
                    <h5 class="mb-3">Change Password (Optional)</h5>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> To change your password, all three password fields below must be filled out.
                    </div>

                    <!-- Old Password -->
                    <x-admin-form-field
                        type="password"
                        name="old_password"
                        label="Current Password"
                        placeholder="Enter current password"
                    />

                    <!-- New Password -->
                    <x-admin-form-field
                        type="password"
                        name="password"
                        label="New Password"
                        placeholder="Enter new password"
                    />

                    <!-- Confirm New Password -->
                    <x-admin-form-field
                        type="password"
                        name="password_confirmation"
                        label="Confirm New Password"
                        placeholder="Confirm new password"
                    />

                    <!-- Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success">Update Profile</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
