<?php

// app/Models/CommentLike.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $fillable = [
        'viewer_id',
        'comment_id',
        'ip',
        'user_agent'
    ];

    public function viewer()
    {
        return $this->belongsTo(Viewer::class);
    }

    public function comment()
    {
        return $this->belongsTo(ViewerComment::class);
    }

    // Scope for guest likes (by IP)
    public function scopeForIp($query, string $ip)
    {
        return $query->where('ip', $ip);
    }

    public function scopeForUserAgent($query, string $userAgent)
    {
        return $query->where('user_agent', $userAgent);
    }
}