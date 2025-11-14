<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userstable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementController extends Controller
{
    /**
     * Display a listing of admin.users.
     */
    public function index()
    {
        $users = Userstable::all();
        return view('adminpages.usermanagement.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('adminpages.usermanagement.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'user_management');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $imagePath = null;
        if ($request->hasFile('profile_image')) {
            $imagePath = $request->file('profile_image')->store('uploads/profile_images', 'public');
        }

        $temporaryPassword = Str::random(10);

        try {
            Userstable::create([
                'username'  => $validatedData['username'],
                'email'     => $validatedData['email'],
                'profile_image' => $imagePath,
                'password'  => $temporaryPassword,
                'telephone' => $validatedData['telephone'],
                'role'      => $validatedData['role'],
                'status'    => 'active',
                'must_change_password' => true,
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully. Temporary password: ' . $temporaryPassword);
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if ($e->getCode() == 23000) {
                $errorMessage = 'A user with this username or email already exists. Please choose different values.';
                
                // More specific error messages based on the constraint
                if (strpos($e->getMessage(), 'username') !== false) {
                    $errorMessage = 'This username is already taken. Please choose a different one.';
                } elseif (strpos($e->getMessage(), 'email') !== false) {
                    $errorMessage = 'This email address is already taken. Please use a different email.';
                }
                
                return redirect()->back()
                    ->withErrors(['database' => $errorMessage])
                    ->withInput();
            }
            
            // Re-throw if it's not a constraint violation
            throw $e;
        }
    }

    /**
     * Display the specified user.
     */
    public function show(Userstable $user)
    {
        // Show warning if user is blocked
        if ($user->status === 'blocked') {
            session()->flash('warning', 'This user is currently blocked and cannot make any changes to the system.');
        }
        
        return view('adminpages.usermanagement.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Userstable $user)
    {
        // Prevent editing blocked users
        if ($user->status === 'blocked') {
            return redirect()->route('admin.users.index')->with('error', 'Blocked users cannot be edited. Unblock them first.');
        }
        
        return view('adminpages.usermanagement.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, Userstable $user)
    {
        // Prevent updating blocked users
        if ($user->status === 'blocked') {
            return redirect()->route('admin.users.index')->with('error', 'Blocked users cannot be updated. Unblock them first.');
        }

        try {
            $validatedData = \App\Services\AdminValidationService::validate($request, 'user_management_update');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }

        $data = [
            'username'  => $validatedData['username'],
            'email'     => $validatedData['email'],
            'telephone' => $validatedData['telephone'],
            'role'      => $validatedData['role'],
        ];

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('uploads/profile_images', 'public');
        }

        try {
            $user->update($data);

            if ($request->filled('password')) {
                $user->update(['password' => $request->password]); // hashed by mutator
            }

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle database constraint violations
            if ($e->getCode() == 23000) {
                $errorMessage = 'A user with this username or email already exists. Please choose different values.';
                
                // More specific error messages based on the constraint
                if (strpos($e->getMessage(), 'username') !== false) {
                    $errorMessage = 'This username is already taken. Please choose a different one.';
                } elseif (strpos($e->getMessage(), 'email') !== false) {
                    $errorMessage = 'This email address is already taken. Please use a different email.';
                }
                
                return redirect()->back()
                    ->withErrors(['database' => $errorMessage])
                    ->withInput();
            }
            
            // Re-throw if it's not a constraint violation
            throw $e;
        }
    }

    /**
     * Toggle user status instead of deleting.
     */
    public function destroy(Userstable $user)
    {
        // Prevent admin.users from blocking themselves
        if (auth()->user()->id == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot block your own account.');
        }

        // Toggle the status: active -> blocked, blocked -> active
        $user->status = $user->status === 'active' ? 'blocked' : 'active';
        $user->save();

        $action = $user->status === 'active' ? 'unblocked' : 'blocked';
        return redirect()->route('admin.users.index')->with('success', "User successfully {$action}.");
    }

    /**
     * Reset user's password.
     */
    public function resetPassword(Request $request, Userstable $user)
    {
        // Prevent admins from resetting their own password
        if (auth()->user()->id == $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'You cannot reset your own password. Use the profile management to change your password.');
        }

        // Prevent resetting password for blocked admin.users
        if ($user->status === 'blocked') {
            return redirect()->route('admin.users.index')->with('error', 'Cannot reset password for blocked admin.users. Unblock them first.');
        }

        // Generate a temporary password and force change on next login
        $temporaryPassword = Str::random(10);
        $user->password = $temporaryPassword; // hashed by mutator
        $user->must_change_password = true;
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Temporary password generated: ' . $temporaryPassword);
    }
    
    /**
     * Show reset password form.
     */
    public function showResetPasswordForm(Userstable $user)
    {
        return view('adminpages.usermanagement.reset-password', compact('user'));
    }

}
