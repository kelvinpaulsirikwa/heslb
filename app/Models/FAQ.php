<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FAQ extends Model
{
    protected $table = 'faqs';

    protected $fillable = [
        'question',
        'answer',
        'posted_by',
        'type',
        'qnstype',
    ];

    // Relationship to user
    public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }
}
