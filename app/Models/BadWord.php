<?php
// app/Models/BadWord.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BadWord extends Model
{
    protected $fillable = [
        'word',
        'language', 
        'severity',
        'is_active'
    ];
    
    protected $casts = [
        'is_active' => 'boolean'
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        // Clear cache when bad words are modified
        static::saved(function () {
            Cache::forget('bad_words_list');
        });
        
        static::deleted(function () {
            Cache::forget('bad_words_list');
        });
    }
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    public function scopeBySeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }
}
