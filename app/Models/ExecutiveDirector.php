<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExecutiveDirector extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'imagepath',
        'start_year',
        'end_year',
        'term_description',
        'status',
        'posted_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
