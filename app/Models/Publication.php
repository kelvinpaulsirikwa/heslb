<?php

// App/Models/Publication.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'description',
        'publication_date',
        'is_active',
        'is_direct_guideline',
        'posted_by',
        'download_count'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_direct_guideline' => 'boolean',
        'publication_date' => 'date',
        'file_size' => 'integer',
        'download_count' => 'integer'
    ];

    /**
     * Get the category that owns the publication
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

     public function user()
    {
        return $this->belongsTo(Userstable::class, 'posted_by');
    }

    /**
     * Scope to get only active publications
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get publications by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope to get publications by category slug
     */
    public function scopeByCategorySlug($query, $slug)
    {
        return $query->whereHas('category', function ($query) use ($slug) {
            $query->where('slug', $slug);
        });
    }

    /**
     * Scope to search publications
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        });
    }

    /**
     * Scope to order by publication date (newest first)
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('publication_date', 'desc');
    }

    /**
     * Scope to order by download count (most downloaded first)
     */
    public function scopePopular($query)
    {
        return $query->orderBy('download_count', 'desc');
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size * 1024; // Convert KB to bytes
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Get the download URL
     */
    public function getDownloadUrlAttribute()
    {
        return $this->file_path ?: '/downloads/' . $this->file_name;
    }

    /**
     * Increment download count
     */
    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    /**
     * Check if publication has file
     */
    public function hasFile()
    {
        return !empty($this->file_name) || !empty($this->file_path);
    }

    /**
     * Get file extension from file name
     */
    public function getFileExtensionAttribute()
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Check if publication is in guidelines category
     */
    public function isGuidelineCategory()
    {
        return $this->category && $this->category->slug === 'guidelines';
    }

    /**
     * Check if publication can be set as direct guideline
     */
    public function canBeDirectGuideline()
    {
        return $this->isGuidelineCategory() && $this->is_active;
    }

    /**
     * Scope to get only guideline publications
     */
    public function scopeGuidelines($query)
    {
        return $query->whereHas('category', function ($q) {
            $q->where('slug', 'guidelines');
        });
    }

    /**
     * Scope to get direct guidelines
     */
    public function scopeDirectGuidelines($query)
    {
        return $query->where('is_direct_guideline', true);
    }
}
