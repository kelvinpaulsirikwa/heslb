<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationGuideline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'academic_year',
        'publication_id',
        'version',
        'description',
        'file_path',
        'file_name',
        'file_original_name',
        'file_size',
        'file_type',
        'is_active',
        'is_current',
        'sort_order',
        'published_at',
        'expires_at',
        'download_count',
        'tags',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_current' => 'boolean',
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'tags' => 'array',
    ];

    /**
     * Get the publication associated with this guideline
     */
    public function publication(): BelongsTo
    {
        return $this->belongsTo(Publication::class);
    }

    /**
     * Get the user who created this guideline
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(Userstable::class, 'created_by');
    }

    /**
     * Get the creator's name for display
     */
    public function getCreatorNameAttribute()
    {
        return $this->creator ? $this->creator->username : 'System';
    }

    /**
     * Get the user who last updated this guideline
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(Userstable::class, 'updated_by');
    }

    /**
     * Scope to get current active guideline
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true)->where('is_active', true);
    }

    /**
     * Scope to get active guidelines
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get published guidelines
     */
    public function scopePublished($query)
    {
        return $query->where('is_active', true)
                    ->where(function($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    })
                    ->where(function($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', now());
                    });
    }

    /**
     * Scope to get guidelines by academic year
     */
    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope to order by custom sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('is_current', 'desc')
                    ->orderBy('published_at', 'desc')
                    ->orderBy('created_at', 'desc');
    }


    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute()
    {
        if ($this->publication) {
            return $this->publication->formatted_file_size;
        }

        if (!$this->file_size) {
            return null;
        }

        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get file path (prioritize stored value, fallback to publication)
     */
    public function getFilePathAttribute()
    {
        return $this->attributes['file_path'] ?: ($this->publication ? $this->publication->file_path : null);
    }

    /**
     * Get file name (prioritize stored value, fallback to publication)
     */
    public function getFileNameAttribute()
    {
        return $this->attributes['file_name'] ?: ($this->publication ? $this->publication->file_name : null);
    }

    /**
     * Get file original name (prioritize stored value, fallback to publication)
     */
    public function getFileOriginalNameAttribute()
    {
        return $this->attributes['file_original_name'] ?: ($this->publication ? $this->publication->title : null);
    }

    /**
     * Get file size (prioritize stored value, fallback to publication)
     */
    public function getFileSizeAttribute()
    {
        return $this->attributes['file_size'] ?: ($this->publication ? $this->publication->file_size : null);
    }

    /**
     * Get file type (prioritize stored value, fallback to publication)
     */
    public function getFileTypeAttribute()
    {
        return $this->attributes['file_type'] ?: ($this->publication ? $this->publication->file_type : null);
    }

    /**
     * Check if guideline can be edited (only if not current)
     */
    public function getCanEditAttribute()
    {
        return !$this->is_current;
    }

    /**
     * Check if guideline can be deleted
     * All guidelines can be deleted
     */
    public function getCanDeleteAttribute()
    {
        return true; // All guidelines can be deleted
    }

    /**
     * Get the absolute file path for download/read operations
     */
    public function getAbsoluteFilePathAttribute()
    {
        // First check if we have a stored file path
        if ($this->attributes['file_path']) {
            // Check if it's a public path (starts with /downloads/)
            if (str_starts_with($this->attributes['file_path'], '/downloads/')) {
                return public_path($this->attributes['file_path']);
            }
            // Check if it's a storage path
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($this->attributes['file_path'])) {
                return storage_path('app/public/' . $this->attributes['file_path']);
            }
        }

        // Fallback to publication file path
        if ($this->publication) {
            $publicRelativePath = $this->publication->file_path ?: ('/downloads/' . $this->publication->file_name);
            return public_path($publicRelativePath);
        }

        return null;
    }

    /**
     * Check if the file exists and is accessible
     */
    public function hasAccessibleFile()
    {
        $absolutePath = $this->absolute_file_path;
        return $absolutePath && file_exists($absolutePath);
    }

    /**
     * Get the download URL for the guideline
     */
    public function getDownloadUrlAttribute()
    {
        return route('loanapplication.guideline.download', $this->id);
    }

    /**
     * Get the read URL for the guideline
     */
    public function getReadUrlAttribute()
    {
        return route('loanapplication.guideline.read', $this->id);
    }
}
