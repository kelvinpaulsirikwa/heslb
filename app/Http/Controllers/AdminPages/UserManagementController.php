<?php

namespace App\Http\Controllers\AdminPages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Userstable;
use App\Models\Permission;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\AuditLogService;

class UserManagementController extends Controller
{
    /**
     * Get list of admin-level permissions that should not be assigned to standard users
     */
    private function getAdminLevelPermissions(): array
    {
        return [
            'manage_users',
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'reset_user_password',
            'view_audit_logs',
            'manage_settings',
        ];
    }

    /**
     * Check if a permission is admin-level
     */
    private function isAdminLevelPermission(string $permissionName): bool
    {
        return in_array($permissionName, $this->getAdminLevelPermissions());
    }

    /**
     * Display a listing of admin.users.
     */
    public function index()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('view_users')) {
            abort(403, 'You do not have permission to view users.');
        }
        
        $users = Userstable::all();
        return view('adminpages.usermanagement.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('create_users')) {
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
            Log::warning('No permissions found in database');
        }
        
        return view('adminpages.usermanagement.create', compact('permissions'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Server-side permission check
        /** @var Userstable $user */
        $user = Auth::user();
        if (!$user || !$user->hasPermission('create_users')) {
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
            // Use database transaction to ensure data consistency
            $user = DB::transaction(function () use ($validatedData, $imagePath, $temporaryPassword, $request) {
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
                    
                    // Validate that no admin-level permissions are being assigned to standard users
                    $adminPermissionIds = Permission::whereIn('name', $this->getAdminLevelPermissions())
                        ->where('guard_name', 'web')
                        ->pluck('id')
                        ->toArray();
                    
                    $attemptedAdminPermissions = array_intersect($permissionIds, $adminPermissionIds);
                    
                    if (!empty($attemptedAdminPermissions)) {
                        $adminPermissionNames = Permission::whereIn('id', $attemptedAdminPermissions)
                            ->pluck('display_name')
                            ->toArray();
                        
                        // Rollback transaction and return error
                        throw new \Exception('Cannot assign admin-level permissions (' . implode(', ', $adminPermissionNames) . ') to standard users.');
                    }
                    
                    foreach ($permissionIds as $permissionId) {
                        UserPermission::create([
                            'user_id' => $user->id,
                            'permission_id' => $permissionId
                        ]);
                    }
                }

                return $user;
            });

            // Audit log (outside transaction to avoid transaction issues)
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
        } catch (\Exception $e) {
            // Handle permission validation errors
            if (strpos($e->getMessage(), 'Cannot assign admin-level permissions') !== false) {
                return redirect()->back()
                    ->withErrors(['permissions' => $e->getMessage()])
                    ->withInput();
            }
            
            // Re-throw if it's a database query exception
            if ($e instanceof \Illuminate\Database\QueryException) {
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
            
            // Re-throw other exceptions
            throw $e;
        }
    }

    /**
     * Display the specified user.
     */
    public function show(Userstable $user)
    {
        // Server-side permission check
        /** @var Userstable $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->hasPermission('view_users')) {
            abort(403, 'You do not have permission to view users.');
        }
        
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
        
        // Get user's permissions (only for 'user' role, admins have all permissions)
        $userPermissions = [];
        $permissionsByCategory = [];
        
        if (strtolower($user->role) === 'user') {
            // Get user-specific permissions
            $userPermissionIds = \App\Models\UserPermission::where('user_id', $user->id)
                ->pluck('permission_id')
                ->toArray();
            
            // Get all permissions grouped by category
            $allPermissions = \App\Models\Permission::where('guard_name', 'web')
                ->orderBy('category')
                ->orderBy('display_name')
                ->get();
            
            // Get user's assigned permissions
            $userPermissions = \App\Models\Permission::whereIn('id', $userPermissionIds)
                ->orderBy('category')
                ->orderBy('display_name')
                ->get();
            
            // Group by category for display
            $permissionsByCategory = $userPermissions->groupBy('category');
        } else {
            // For admin role, show all available permissions (they have all)
            $allPermissions = \App\Models\Permission::where('guard_name', 'web')
                ->orderBy('category')
                ->orderBy('display_name')
                ->get();
            $permissionsByCategory = $allPermissions->groupBy('category');
        }
        
        return view('adminpages.usermanagement.show', compact('user', 'stats', 'hasUploadedData', 'userPermissions', 'permissionsByCategory'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Userstable $user)
    {
        // Server-side permission check
        /** @var Userstable $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->hasPermission('edit_users')) {
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
        /** @var Userstable $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->hasPermission('edit_users')) {
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
            // Use database transaction to ensure data consistency
            DB::transaction(function () use ($user, $data, $request) {
                $user->update($data);

                if ($request->filled('password')) {
                    $user->update(['password' => $request->password]); // hashed by mutator
                }

                // Handle user permissions if role is "user"
                if (strtolower($data['role']) === 'user') {
                    // Get selected permission IDs
                    $selectedPermissions = $request->input('permissions', []);
                    
                    // Validate that no admin-level permissions are being assigned to standard users
                    $adminPermissionIds = Permission::whereIn('name', $this->getAdminLevelPermissions())
                        ->where('guard_name', 'web')
                        ->pluck('id')
                        ->toArray();
                    
                    $attemptedAdminPermissions = array_intersect($selectedPermissions, $adminPermissionIds);
                    
                    if (!empty($attemptedAdminPermissions)) {
                        $adminPermissionNames = Permission::whereIn('id', $attemptedAdminPermissions)
                            ->pluck('display_name')
                            ->toArray();
                        
                        // Rollback transaction and throw exception
                        throw new \Exception('Cannot assign admin-level permissions (' . implode(', ', $adminPermissionNames) . ') to standard users.');
                    }
                    
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
            });

            // Regenerate session if role or permissions changed (prevents session fixation)
            if ($oldValues['role'] !== $data['role'] || 
                (strtolower($data['role']) === 'user' && $request->has('permissions'))) {
                // If updating own account, regenerate session to reflect new permissions
                if (Auth::id() == $user->id) {
                    $request->session()->regenerate();
                }
            }

            // Audit log (outside transaction to avoid transaction issues)
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
        } catch (\Exception $e) {
            // Handle permission validation errors
            if (strpos($e->getMessage(), 'Cannot assign admin-level permissions') !== false) {
                return redirect()->back()
                    ->withErrors(['permissions' => $e->getMessage()])
                    ->withInput();
            }
            
            // Re-throw if it's a database query exception
            if ($e instanceof \Illuminate\Database\QueryException) {
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
            
            // Re-throw other exceptions
            throw $e;
        }
    }

    /**
     * Toggle user status instead of deleting.
     */
    public function destroy(Userstable $user)
    {
        // Server-side permission check
        /** @var Userstable $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->hasPermission('manage_users')) {
            abort(403, 'You do not have permission to manage users.');
        }
        
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
        // Server-side permission check
        /** @var Userstable $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->hasPermission('reset_user_password')) {
            abort(403, 'You do not have permission to reset user passwords.');
        }
        
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
        // Server-side permission check
        /** @var Userstable $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->hasPermission('reset_user_password')) {
            abort(403, 'You do not have permission to reset user passwords.');
        }
        
        return view('adminpages.usermanagement.reset-password', compact('user'));
    }

    /**
     * Delete user from the system if they haven't uploaded any data.
     */
    public function deleteUser(Userstable $user)
    {
        // Server-side permission check
        /** @var Userstable $authUser */
        $authUser = Auth::user();
        if (!$authUser || !$authUser->hasPermission('delete_users')) {
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
