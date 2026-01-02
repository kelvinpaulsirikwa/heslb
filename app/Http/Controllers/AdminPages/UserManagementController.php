<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userstable;
use App\Models\Permission;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Services\AuditLogService;

class UserManagementController extends Controller
{
    /**
     * Constructor to ensure only admins can access user management.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || strtolower(Auth::user()->role) !== 'admin') {
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'Access denied. Admin privileges required.',
                        'status' => 'forbidden'
                    ], 403);
                }
                return redirect()->route('dashboard')->with('error', 'Access denied. You do not have permission to access this page.');
            }
            return $next($request);
        });
    }

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
        // Server-side permission check
        if (!Auth::user()->hasPermission('create_users')) {
            abort(403, 'You do not have permission to create users.');
        }
        
        // Get all permissions grouped by category for user role
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('category')
            ->orderBy('display_name')
            ->get()
            ->groupBy('category');
        
        // Debug: Check if permissions exist
        if ($permissions->isEmpty()) {
            \Log::warning('No permissions found in database');
        }
        
        return view('adminpages.usermanagement.create', compact('permissions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Server-side permission check
        if (!Auth::user()->hasPermission('create_users')) {
            abort(403, 'You do not have permission to create users.');
        }
        
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
            $user = Userstable::create([
                'username'  => $validatedData['username'],
                'email'     => $validatedData['email'],
                'profile_image' => $imagePath,
                'password'  => $temporaryPassword,
                'telephone' => $validatedData['telephone'],
                'role'      => $validatedData['role'],
                'status'    => 'active',
                'must_change_password' => true,
            ]);

            // If role is "user", assign individual permissions
            if (strtolower($validatedData['role']) === 'user' && $request->has('permissions')) {
                $permissionIds = $request->input('permissions', []);
                foreach ($permissionIds as $permissionId) {
                    UserPermission::create([
                        'user_id' => $user->id,
                        'permission_id' => $permissionId
                    ]);
                }
            }

            // Audit log
            $newValues = ['username' => $user->username, 'email' => $user->email, 'role' => $user->role];
            if (strtolower($validatedData['role']) === 'user' && $request->has('permissions')) {
                $newValues['permissions'] = $request->input('permissions', []);
            }
            
            AuditLogService::log(
                'create',
                'User',
                $user->id,
                null,
                $newValues
            );

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
        
        // Get user statistics
        $stats = [
            'news' => \App\Models\News::where('posted_by', $user->id)->count(),
            'publications' => \App\Models\Publication::where('posted_by', $user->id)->count(),
            'links' => \App\Models\Link::where('posted_by', $user->id)->count(),
            'video_podcasts' => \App\Models\Videopodcast::where('posted_by', $user->id)->count(),
            'applications' => \App\Models\WindowApplication::where('user_id', $user->id)->count(),
            'photo_galleries' => \App\Models\Taasisevent::where('posted_by', $user->id)->count(),
            'photo_gallery_images' => \App\Models\TaasiseventImage::where('posted_by', $user->id)->count(),
            'partners' => \App\Models\Partner::where('posted_by', $user->id)->count(),
            'faqs' => \App\Models\FAQ::where('posted_by', $user->id)->count(),
            'board_of_directors' => \App\Models\BoardOfDirector::where('posted_by', $user->id)->count(),
            'executive_directors' => \App\Models\ExecutiveDirector::where('posted_by', $user->id)->count(),
            'scholarships' => \App\Models\Scholarship::where('posted_by', $user->id)->count(),
        ];
        
        // Check if user has uploaded any data
        $hasUploadedData = array_sum($stats) > 0;
        
        return view('adminpages.usermanagement.show', compact('user', 'stats', 'hasUploadedData'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Userstable $user)
    {
        // Server-side permission check
        if (!Auth::user()->hasPermission('edit_users')) {
            abort(403, 'You do not have permission to edit users.');
        }
        
        // Prevent editing blocked users
        if ($user->status === 'blocked') {
            return redirect()->route('admin.users.index')->with('error', 'Blocked users cannot be edited. Unblock them first.');
        }
        
        // Get all permissions grouped by category
        $permissions = Permission::where('guard_name', 'web')
            ->orderBy('category')
            ->orderBy('display_name')
            ->get()
            ->groupBy('category');
        
        // Get user's current permissions (only for user role)
        $userPermissionIds = [];
        if (strtolower($user->role) === 'user') {
            $userPermissionIds = UserPermission::where('user_id', $user->id)
                ->pluck('permission_id')
                ->toArray();
        }
        
        return view('adminpages.usermanagement.edit', compact('user', 'permissions', 'userPermissionIds'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, Userstable $user)
    {
        // Server-side permission check
        if (!Auth::user()->hasPermission('edit_users')) {
            abort(403, 'You do not have permission to edit users.');
        }
        
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

        $oldValues = [
            'username' => $user->username,
            'email' => $user->email,
            'telephone' => $user->telephone,
            'role' => $user->role,
        ];

        try {
            $user->update($data);

            if ($request->filled('password')) {
                $user->update(['password' => $request->password]); // hashed by mutator
            }

            // Handle user permissions if role is "user"
            if (strtolower($data['role']) === 'user') {
                // Get selected permission IDs
                $selectedPermissions = $request->input('permissions', []);
                
                // Get current user permissions
                $currentPermissionIds = UserPermission::where('user_id', $user->id)
                    ->pluck('permission_id')
                    ->toArray();
                
                // Find permissions to add
                $permissionsToAdd = array_diff($selectedPermissions, $currentPermissionIds);
                
                // Find permissions to remove
                $permissionsToRemove = array_diff($currentPermissionIds, $selectedPermissions);
                
                // Add new permissions
                foreach ($permissionsToAdd as $permissionId) {
                    UserPermission::create([
                        'user_id' => $user->id,
                        'permission_id' => $permissionId
                    ]);
                }
                
                // Remove permissions
                UserPermission::where('user_id', $user->id)
                    ->whereIn('permission_id', $permissionsToRemove)
                    ->delete();
            } else {
                // If role changed from "user" to something else, remove all user-specific permissions
                UserPermission::where('user_id', $user->id)->delete();
            }

            // Regenerate session if role or permissions changed (prevents session fixation)
            if ($oldValues['role'] !== $data['role'] || 
                (strtolower($data['role']) === 'user' && $request->has('permissions'))) {
                // If updating own account, regenerate session to reflect new permissions
                if (Auth::id() == $user->id) {
                    $request->session()->regenerate();
                }
            }

            // Audit log
            $newValues = array_merge($oldValues, $data);
            if (strtolower($data['role']) === 'user' && $request->has('permissions')) {
                $newValues['permissions'] = $request->input('permissions', []);
            }
            
            AuditLogService::log(
                'update',
                'User',
                $user->id,
                $oldValues,
                $newValues
            );

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
        $oldStatus = $user->status;
        $user->status = $user->status === 'active' ? 'blocked' : 'active';
        $user->save();

        // Audit log
        AuditLogService::log(
            $user->status === 'active' ? 'unblock' : 'block',
            'User',
            $user->id,
            ['status' => $oldStatus],
            ['status' => $user->status]
        );

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

        // Audit log
        AuditLogService::log(
            'reset_password',
            'User',
            $user->id,
            null,
            ['username' => $user->username]
        );

        return redirect()->route('admin.users.index')->with('success', 'Temporary password generated: ' . $temporaryPassword);
    }
    
    /**
     * Show reset password form.
     */
    public function showResetPasswordForm(Userstable $user)
    {
        return view('adminpages.usermanagement.reset-password', compact('user'));
    }

    /**
     * Delete user from the system if they haven't uploaded any data.
     */
    public function deleteUser(Userstable $user)
    {
        // Server-side permission check
        if (!Auth::user()->hasPermission('delete_users')) {
            abort(403, 'You do not have permission to delete users.');
        }
        
        // Prevent users from deleting themselves
        if (auth()->user()->id == $user->id) {
            return redirect()->route('admin.users.show', $user)->with('error', 'You cannot delete your own account.');
        }

        // Check if user has uploaded any data
        $hasData = \App\Models\News::where('posted_by', $user->id)->exists() ||
                   \App\Models\Publication::where('posted_by', $user->id)->exists() ||
                   \App\Models\Link::where('posted_by', $user->id)->exists() ||
                   \App\Models\Videopodcast::where('posted_by', $user->id)->exists() ||
                   \App\Models\WindowApplication::where('user_id', $user->id)->exists() ||
                   \App\Models\Taasisevent::where('posted_by', $user->id)->exists() ||
                   \App\Models\TaasiseventImage::where('posted_by', $user->id)->exists() ||
                   \App\Models\Partner::where('posted_by', $user->id)->exists() ||
                   \App\Models\FAQ::where('posted_by', $user->id)->exists() ||
                   \App\Models\BoardOfDirector::where('posted_by', $user->id)->exists() ||
                   \App\Models\ExecutiveDirector::where('posted_by', $user->id)->exists() ||
                   \App\Models\Scholarship::where('posted_by', $user->id)->exists();

        if ($hasData) {
            return redirect()->route('admin.users.show', $user)->with('error', 'Cannot delete user. This user has uploaded data to the system.');
        }

        // Delete the user
        $username = $user->username;
        $userId = $user->id;
        $userData = [
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
        ];
        
        $user->delete();

        // Audit log
        AuditLogService::log(
            'delete',
            'User',
            $userId,
            $userData,
            null
        );

        return redirect()->route('admin.users.index')->with('success', "User '{$username}' has been successfully deleted from the system.");
    }

}
