<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Userstable;

class ProfileManagementController extends Controller
{
    /**
     * Show the edit profile form.
     */
    public function editProfile()
    {
        /** @var Userstable $user */
        $user = Auth::user(); // logged-in user

        return view('adminpages.profile.edit', compact('user'));
    }

    /**
     * Update the profile information.
     */
    public function updateProfile(Request $request)
    {
        /** @var Userstable $user */
        $user = Auth::user();

        // Custom password validation logic
        $passwordFields = [
            'old_password' => $request->input('old_password'),
            'password' => $request->input('password'),
            'password_confirmation' => $request->input('password_confirmation')
        ];
        
        $filledPasswordFields = array_filter($passwordFields, function($value) {
            return !empty(trim($value));
        });
        
        // If any password field is filled, all must be filled
        if (!empty($filledPasswordFields) && count($filledPasswordFields) < 3) {
            $errors = [];
            if (empty(trim($passwordFields['old_password']))) {
                $errors['old_password'] = 'Current password is required when changing password. All three password fields must be filled.';
            }
            if (empty(trim($passwordFields['password']))) {
                $errors['password'] = 'New password is required when changing password. All three password fields must be filled.';
            }
            if (empty(trim($passwordFields['password_confirmation']))) {
                $errors['password_confirmation'] = 'Password confirmation is required when changing password. All three password fields must be filled.';
            }
            
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'profile_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        // Update fields
        $user->username  = $validatedData['username'];
        $user->telephone = $validatedData['telephone'];

        // Only update password if provided
        if (!empty($validatedData['password'])) {
            // Verify old password
            if (!Hash::check($validatedData['old_password'], $user->password)) {
                return redirect()->back()
                    ->withErrors(['old_password' => 'The current password is incorrect.'])
                    ->withInput();
            }
            $user->password = Hash::make($validatedData['password']);
        }

        // Profile image upload (optional)
        if ($request->hasFile('profile_image')) {
            $user->profile_image = $request->file('profile_image')->store('uploads/profile_images', 'public');
        }

        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
