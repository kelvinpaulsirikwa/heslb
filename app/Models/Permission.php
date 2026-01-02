<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'category',
    ];

    /**
     * Get all roles that have this permission
     */
    public function roles()
    {
        return $this->hasMany(RolePermission::class);
    }
}
