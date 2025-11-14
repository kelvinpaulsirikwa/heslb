<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'partners';

    // The attributes that are mass assignable
    protected $fillable = [
        'name',
        'acronym_name',
        'link',
        'image_path',
        'posted_by',
    ];

    // Cast fields to native types
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'posted_by' => 'integer',
    ];

    /**
     * Get the user who posted this partner
     */
    public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }

    /**
     * Get the full image path with partner_image folder
     */
    public function getFullImagePathAttribute()
    {
        return $this->image_path ? 'partner_image/' . $this->image_path : null;
    }
}