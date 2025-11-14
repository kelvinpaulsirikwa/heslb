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
}
