<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogVideoGifs extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'blog_video_gifs';
}