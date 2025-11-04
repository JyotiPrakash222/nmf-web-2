<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebStoryFiles extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'webstories_file';
}