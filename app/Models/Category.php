<?php

// App/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'posted_by',
        'display_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer'
    ];

    /**
     * Get all publications for this category
     */
    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }

    /**
     * Get only active publications for this category
     */
    public function activePublications(): HasMany
    {
        return $this->hasMany(Publication::class)->where('is_active', true);
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Find category by slug
     */
    public function scopeBySlug($query, $slug)
    {
        return $query->where('slug', $slug);
    }

    /**
     * Get publications count
     */
    public function getPublicationsCountAttribute()
    {
        return $this->publications()->count();
    }

    /**
     * Get active publications count
     */
    public function getActivePublicationsCountAttribute()
    {
        return $this->activePublications()->count();
    }

     public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }

}