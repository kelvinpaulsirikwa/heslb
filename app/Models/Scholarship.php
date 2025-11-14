<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'donor_organization',
        'application_deadline',
        'eligible_applicants',
        'fields_of_study',
        'level_of_study',
        'benefits_summary',
        'external_link',
        'content_html',
        'cover_image',
        'is_active',
        'published_at',
        'posted_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'published_at' => 'datetime',
        'application_deadline' => 'date',
        'level_of_study' => 'array',
    ];

    public function getCoverImageUrlAttribute(): ?string
    {
        if (!$this->cover_image) {
            return null;
        }
        // Project serves uploaded media from public/images/storage
        return asset('images/storage/' . ltrim($this->cover_image, '/'));
    }

    /**
     * Generate a unique slug for the scholarship
     */
    public static function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug($title);
        $slug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $slug)->when($excludeId, function ($query, $excludeId) {
            return $query->where('id', '!=', $excludeId);
        })->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}


