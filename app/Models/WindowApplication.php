<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindowApplication extends Model
{
    // Explicit table name
    protected $table = 'windowapplications';

    protected $fillable = [
        'user_id',
        'program_type',
        'extension_type',
        'academic_year',
        'submitted_at',
        'starting_date',
        'ending_date',
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(Userstable::class, 'user_id');
    }

    // Cast attributes
    protected $casts = [
        'starting_date' => 'date',
        'ending_date' => 'date',
        'submitted_at' => 'date',
    ];

    // Scope for active windows (based on date range)
    public function scopeActive($query)
    {
        $now = now();
        return $query->where('starting_date', '<=', $now)
                    ->where('ending_date', '>=', $now);
    }

    // Scope for current academic year
    public function scopeCurrentAcademicYear($query)
    {
        $currentYear = date('Y');
        $academicYear = ($currentYear - 1) . '/' . $currentYear;
        return $query->where('academic_year', $academicYear);
    }

    // Get available program types
    public static function getAvailableProgramTypes()
    {
        return [
            'all' => 'All (Diploma, Bachelor\'s Degree, Master\'s Degree, PhD, Laws School PGDL, Samia Scholarship)',
            'diploma_only' => 'Diploma Only'
        ];
    }

    // Get available extension types
    public static function getAvailableExtensionTypes()
    {
        return [
            'window_open' => 'Window Open',
            'extension_of_window' => 'Extension of Window',
        ];
    }
}
