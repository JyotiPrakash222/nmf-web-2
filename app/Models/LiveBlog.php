<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LiveBlog extends Model
{
    use HasFactory;

    protected $table = 'live_blogs';

    protected $fillable = [
        'blog_id',
        'author',
        'breaking_status',
        'update_title',
        'update_content',
        'image_id',
        'video_url',
        'category_id',
        'status',
    ];


    public function blog()
    {
        return $this->belongsTo(Blog::class, 'blog_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function image()
    {
        return $this->belongsTo(File::class, 'image_id');
    }
}