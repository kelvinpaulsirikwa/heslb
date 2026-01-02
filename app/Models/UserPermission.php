<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'permission_id',
    ];

    /**
     * Get the user that has this permission
     */
    public function user()
    {
        return $this->belongsTo(Userstable::class, 'user_id');
    }

    /**
     * Get the permission
     */
    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
