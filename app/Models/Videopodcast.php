<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Videopodcast extends Model
{
    // Table name
    protected $table = 'Videopodcast';

    // Mass assignable attributes
    protected $fillable = [
        'name',
        'posted_by',
        'date_posted',
        'link',
        'description',
    ];

    // Casts
    protected $casts = [
        'date_posted' => 'datetime',
    ];

    /**
     * Relationship: VideoPodcast belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }
}
