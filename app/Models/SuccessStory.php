<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class SuccessStory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'author',
        'email',
        'phone',
        'university',
        'form_four_index_number',
        'category',
        'title',
        'content',
        'images',
        'video',
        'documents',
        'allow_photos',
        'allow_video',
        'allow_contact',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'publication_status',
        'published_at',
        'approved_by',
        'admin_notes',
        'views',
        'likes',
        'shares',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'documents' => 'array',
        'allow_photos' => 'boolean',
        'allow_video' => 'boolean',
        'allow_contact' => 'boolean',
        'published_at' => 'datetime',
        'views' => 'integer',
        'likes' => 'integer',
        'shares' => 'integer',
    ];

    /**
     * Category options - Success Stories
     */
    const CATEGORIES = [
        'tuition' => 'Tuition Fee Support',
        'meals' => 'Meals & Accommodation',
        'books' => 'Books & Stationery',
        'research' => 'Research Support',
        'special_needs' => 'Special Needs Support',
        'postgraduate' => 'Postgraduate Support',
        'success_story' => 'Success Story',
    ];

    /**
     * Publication status options
     */
    const PUBLICATION_STATUSES = [
        'pending' => 'Pending Review',
        'approved' => 'Approved & Published',
        'rejected' => 'Rejected',
        'draft' => 'Draft',
    ];

    /**
     * Get the user who approved this story.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(Userstable::class, 'approved_by');
    }

    /**
     * Get all action history for this story.
     */
    public function actionHistory()
    {
        return $this->hasMany(StoryActionHistory::class, 'story_id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope to get only published stories
     */
    public function scopePublished($query)
    {
        return $query->where('publication_status', 'approved')
                    ->whereNotNull('published_at');
    }

    /**
     * Scope to get pending stories
     */
    public function scopePending($query)
    {
        return $query->where('publication_status', 'pending');
    }

    /**
     * Scope to get stories by category
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get the category name
     */
    public function getCategoryNameAttribute()
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    /**
     * Get the publication status name
     */
    public function getPublicationStatusNameAttribute()
    {
        return self::PUBLICATION_STATUSES[$this->publication_status] ?? $this->publication_status;
    }

    /**
     * Check if story is published
     */
    public function getIsPublishedAttribute()
    {
        return $this->publication_status === 'approved' && !is_null($this->published_at);
    }

    /**
     * Get formatted published date
     */
    public function getPublishedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('M j, Y') : null;
    }

    /**
     * Get story excerpt
     */
    public function getExcerptAttribute($length = 150)
    {
        return strlen($this->content) > $length ? 
               substr($this->content, 0, $length) . '...' : 
               $this->content;
    }

    /**
     * Get reading time estimate
     */
    public function getReadingTimeAttribute()
    {
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Average reading speed: 200 words per minute
        return $readingTime . ' min read';
    }

    /**
     * Increment views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Increment likes
     */
    public function incrementLikes()
    {
        $this->increment('likes');
    }

    /**
     * Increment shares
     */
    public function incrementShares()
    {
        $this->increment('shares');
    }

    /**
     * Approve the story
     */
    public function approve($userId = null, $notes = null)
    {
        $this->update([
            'publication_status' => 'approved',
            'published_at' => now(),
            'approved_by' => $userId,
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Reject the story
     */
    public function reject($userId = null, $notes = null)
    {
        $this->update([
            'publication_status' => 'rejected',
            'approved_by' => $userId,
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Get all image URLs
     */
    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        // Handle both JSON string and array
        $imagePaths = is_string($this->images) ? json_decode($this->images, true) : $this->images;
        
        if (!is_array($imagePaths)) {
            \Illuminate\Support\Facades\Log::warning('Image paths is not an array', [
                'story_id' => $this->id,
                'images' => $this->images,
                'decoded' => $imagePaths
            ]);
            return [];
        }

        $urls = [];
        foreach ($imagePaths as $image) {
            if (is_string($image) && !empty($image)) {
                $urls[] = asset('images/storage/' . $image);
            }
        }

        \Illuminate\Support\Facades\Log::info('Generated image URLs', [
            'story_id' => $this->id,
            'image_paths' => $imagePaths,
            'urls' => $urls
        ]);

        return $urls;
    }

    /**
     * Get video URL
     */
    public function getVideoUrlAttribute()
    {
        return $this->video ? asset('images/storage/' . $this->video) : null;
    }

    /**
     * Get document URLs
     */
    public function getDocumentUrlsAttribute()
    {
        if (!$this->documents) {
            return [];
        }

        return collect($this->documents)->map(function ($document) {
            return [
                'url' => asset('images/storage/' . $document),
                'name' => basename($document),
            ];
        })->toArray();
    }
}