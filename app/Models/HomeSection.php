<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class, 'catid');
    }

    protected $table = 'home_sections'; // Explicitly define the table name

    protected $fillable = ['title', 'catid', 'image_url', 'banner_link', 'section_order','sidebar_sec_order', 'status', 'type']; // Allow mass assignment

    public $timestamps = false;

    public const TYPES = ['section', 'sidebar','banner', 'other'];
}