<?php
// app/Models/ViewerComment.php (Updated)

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ViewerComment extends Model
{
    protected $table = 'viewercomments';
    
    protected $fillable = [
        'body',
        'commentable_id',
        'commentable_type', 
        'viewer_id',
        'parent_id',
        'moderation_status',
        'contains_profanity',
        'flagged_words',
        'original_body',
        'moderated_by',
        'moderated_at',
        'moderation_notes'
    ];
    
    protected $casts = [
        'contains_profanity' => 'boolean',
        'moderated_at' => 'datetime',
        'flagged_words' => 'array'
    ];

    // Existing relationships
    public function viewer()
    {
        return $this->belongsTo(Viewer::class);
    }

    public function parent()
    {
        return $this->belongsTo(ViewerComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ViewerComment::class, 'parent_id');
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }
    
    // New moderation relationships
    public function moderator()
    {
        return $this->belongsTo(\App\Models\User::class, 'moderated_by');
    }

    // Existing methods
    public function isParent()
    {
        return is_null($this->parent_id);
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    public function isLikedByViewer($viewerId)
    {
        return $this->likes()->where('viewer_id', $viewerId)->exists();
    }
    
    // New moderation scopes
    public function scopeApproved($query)
    {
        return $query->where('moderation_status', 'approved');
    }
    
    public function scopePending($query)
    {
        return $query->where('moderation_status', 'pending');
    }
    
    public function scopeRejected($query)
    {
        return $query->where('moderation_status', 'rejected');
    }
    
    public function scopeWithProfanity($query)
    {
        return $query->where('contains_profanity', true);
    }
    
    public function scopePublic($query)
    {
        return $query->where('moderation_status', 'approved');
    }
    
    // New moderation methods
    public function approve($moderatorId = null, $notes = null)
    {
        $this->update([
            'moderation_status' => 'approved',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
            'moderation_notes' => $notes
        ]);
    }
    
    public function reject($moderatorId = null, $notes = null)
    {
        $this->update([
            'moderation_status' => 'rejected',
            'moderated_by' => $moderatorId,
            'moderated_at' => now(),
            'moderation_notes' => $notes
        ]);
    }
    
    public function isPending()
    {
        return $this->moderation_status === 'pending';
    }
    
    public function isApproved()
    {
        return $this->moderation_status === 'approved';
    }
    
    public function isRejected()
    {
        return $this->moderation_status === 'rejected';
    }
}
