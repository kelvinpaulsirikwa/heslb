<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taasisevent extends Model
{
    use HasFactory;

    protected $table = 'taasisevent';

    protected $fillable = [
        'posted_by',
        'name_of_event',
        'description',
    ];

    // Relationship: Event belongs to a User
    public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }
    public function images()
{
    return $this->hasMany(TaasiseventImage::class, 'taasisevent_id');
}

}
