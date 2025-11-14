<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoryActionHistory extends Model
{
    use HasFactory;

    protected $table = 'story_action_history';

    protected $fillable = [
        'story_id',
        'admin_id',
        'action',
        'old_status',
        'new_status',
        'notes',
        'changes'
    ];

    protected $casts = [
        'changes' => 'array'
    ];

    // Relationships
    public function story()
    {
        return $this->belongsTo(SuccessStory::class, 'story_id');
    }

    public function admin()
    {
        return $this->belongsTo(Userstable::class, 'admin_id');
    }

    // Accessors
    public function getActionNameAttribute()
    {
        $actions = [
            'submit' => 'Story Submitted',
            'approve' => 'Approved',
            'reject' => 'Rejected',
            'draft' => 'Marked as Draft',
            'edit' => 'Edited',
            'delete' => 'Deleted',
            'restore' => 'Restored',
            'publish' => 'Published Online'
        ];

        return $actions[$this->action] ?? ucfirst($this->action);
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'draft' => 'Draft'
        ];

        return $statuses[$this->new_status] ?? $this->new_status;
    }
}
