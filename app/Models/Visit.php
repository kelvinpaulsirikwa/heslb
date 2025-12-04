<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Visit extends Model
{
    protected $fillable = ['ip_address', 'user_agent', 'visited_at'];
    public $timestamps = true;
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
