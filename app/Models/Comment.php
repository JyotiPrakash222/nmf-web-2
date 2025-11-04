<?php

// app/Models/Comment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'body',
        'commentable_id',
        'commentable_type',
        'viewer_id',
        'parent_id'
    ];

    protected $dates = ['deleted_at'];

    // Relationship to viewer (commenter)
    public function viewer()
    {
        return $this->belongsTo(Viewer::class);
    }

    // Polymorphic relationship (can comment on any model)
    public function commentable()
    {
        return $this->morphTo();
    }

    // Nested comments (replies)
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')->oldest();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Likes relationship
    public function likes()
    {
        return $this->hasMany(CommentLike::class);
    }

    // Check if this is a top-level comment
    public function isParent()
    {
        return is_null($this->parent_id);
    }

    // app/Models/Comment.php
    public function getLikesCountAttribute()
    {
        return $this->likes()->count();
    }

    // Also add this method for the UI
    public function isLikedByViewer($viewerId)
    {
        return $this->likes()->where('viewer_id', $viewerId)->exists();
    }
}