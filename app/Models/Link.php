<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = [
        'link_name',
        'link',
        'posted_by',
        'is_file',

    ];

    public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }
    protected $casts = [
    'is_file' => 'boolean',
];
}
