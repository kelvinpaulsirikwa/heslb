<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Userstable extends Authenticatable
{
    use Notifiable, HasRoles;

    protected $table = 'userstable';

    protected $fillable = [
        'username',
        'email',
        'profile_image',
        'password',
        'telephone',
        'nida',
        'status',
        'role',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'nida' => 'integer',
        'must_change_password' => 'boolean',
    ];

    const STATUS_ACTIVE    = 'active';
    const STATUS_BLOCKED   = 'blocked';
    const STATUS_SUSPENDED = 'suspended';

    public static function getStatuses()
    {
        return [
            self::STATUS_ACTIVE,
            self::STATUS_BLOCKED,
            self::STATUS_SUSPENDED,
        ];
    }

    // Example relationships
    public function applications()
    {
        return $this->hasMany(WindowApplication::class, 'user_id');
    }

    public function links()
    {
        return $this->hasMany(Link::class, 'posted_by');
    }
    public function taasisevents()
{
    return $this->hasMany(Taasisevent::class, 'posted_by');
}
public function taasiseventImages()
{
    return $this->hasMany(TaasiseventImage::class, 'posted_by');
}

    // Ensure all passwords are hashed
    public function setPasswordAttribute($value): void
    {
        if ($value === null || $value === '') {
            return;
        }
        // Detect if value already looks like a hash (bcrypt/argon2 variants)
        $looksHashed = is_string($value) && (str_starts_with($value, '$2y$') || str_starts_with($value, '$argon2i$') || str_starts_with($value, '$argon2id$'));
        $this->attributes['password'] = $looksHashed
            ? $value
            : \Illuminate\Support\Facades\Hash::make($value);
    }

    // Accessor for display name
    public function getDisplayNameAttribute()
    {
        return $this->username . ' (' . $this->email . ')';
    }

    /**
     * Get audit logs for this user
     */
    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    /**
     * Get user-specific permissions
     */
    public function userPermissions()
    {
        return $this->hasMany(UserPermission::class, 'user_id');
    }

    /**
     * Check if user has a specific permission
     * 
     * @param string $permission The permission name to check
     * @return bool True if user has the permission, false otherwise
     */
    public function hasPermission(string $permission): bool
    {
        // Admin role has all permissions automatically
        if (strtolower($this->role) === 'admin') {
            return true;
        }

        // Check if permission exists
        $permissionExists = Permission::where('name', $permission)
            ->where('guard_name', 'web')
            ->exists();
        
        if (!$permissionExists) {
            return false;
        }

        // Get the permission ID
        $permissionModel = Permission::where('name', $permission)
            ->where('guard_name', 'web')
            ->first();
        
        if (!$permissionModel) {
            return false;
        }

        // Check if user has individual permission assigned
        $hasUserPermission = UserPermission::where('user_id', $this->id)
            ->where('permission_id', $permissionModel->id)
            ->exists();
        
        if ($hasUserPermission) {
            return true;
        }

        // Check if user's role has this permission
        return RolePermission::where('role', $this->role)
            ->where('permission_id', $permissionModel->id)
            ->exists();
    }

    /**
     * Get all permissions for this user's role
     */
    public function getPermissions(): \Illuminate\Support\Collection
    {
        return RolePermission::where('role', $this->role)
            ->with('permission')
            ->get()
            ->pluck('permission');
    }
}
