<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'contact_type',
        'message',
        'date_of_incident',
        'location',
        'image',
        'consent',
        'status',
        'delete',
        'deleted_by',
        'seen_by', // new column
        'published', // new column
        'views', // new column
    ];

    // Relationship for deleted_by
    public function deletedByUser()
    {
        return $this->belongsTo(Userstable::class, 'deleted_by');
    }

    // Relationship for seen_by
    public function seenByUser()
    {
        return $this->belongsTo(Userstable::class, 'seen_by');
    }

    // Method to increment views
    public function incrementViews()
    {
        $this->increment('views');
    }
}

