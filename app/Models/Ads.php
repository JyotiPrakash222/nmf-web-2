<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ads extends Model
{
    use HasFactory;

    protected $table = 'ads';

    protected $fillable = [
        'location',
        'page_type',
        'is_google_ad',
        'file_path',
        'custom_image',
        'custom_link',
        'google_client',
        'google_slot',
    ];

    public const PAGE_TYPES = ['home', 'category', 'details'];
}
