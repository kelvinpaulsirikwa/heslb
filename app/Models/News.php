<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'title',
        'content',
        'category',
        'date_expire',
        'posted_by',
        'front_image'
    ];

    protected $casts = [
        'date_expire' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }
}
