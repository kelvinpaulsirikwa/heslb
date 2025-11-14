<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaasiseventImage extends Model
{
    use HasFactory;

    protected $table = 'taasisevent_images';

    protected $fillable = [
        'taasisevent_id',
        'posted_by',
        'image_link',
        'description',
    ];

    // Relationships
    public function taasisevent()
    {
        return $this->belongsTo(Taasisevent::class, 'taasisevent_id');
    }

    public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }
}
 