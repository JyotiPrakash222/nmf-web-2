<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebStories extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'webstories';

    // NL1026:16Sep2025: Cache category page webstory :Start
    // Auto clear cache on create/update/delete
    protected static function booted()
    {
        static::created(function ($webStory) {
            $webStory->clearCategoryCache();
        });

        static::updated(function ($webStory) {
            $webStory->clearCategoryCache();
        });

        static::deleted(function ($webStory) {
            $webStory->clearCategoryCache();
        });
    }

    protected function clearCategoryCache()
    {
        if ($this->category) {
            // Simple forget (only clears default state/subcat=0 cache)
            $key = "category_page_ids_{$this->category->site_url}_state_0_subcat_0";
            Cache::forget($key);

            // If you ever switch to tags:
            // Cache::tags(['category_'.$this->category->id])->flush();
        }
    }
    // NL1026:16Sep2025: Cache category page webstory :End

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    // Relationship with WebStoryFiles
    public function webStoryFiles()
    {
        return $this->hasMany(WebStoryFiles::class, 'webstories_id');
    }

    // public function scopeJoinSequence($query){
    //     $query->rightjoin('page_sequences', function($join) {
    //         $join->on('page_sequences.webstories_id', '=', 'webstories.id');
    //     });
    // }

    public function getFirstImageUrlAttribute()
    {
        //$file = $this->webStoryFiles->firstWhere('file_sequence', 1);
        $file = $this->webStoryFiles()
            ->orderBy('file_sequence', 'asc')
            ->first();

        if ($file && $file->filepath && $file->filename) {
            // Try to find "file/" in the full path and extract from there
            $position = strpos($file->filepath, 'file/');

            if ($position !== false) {
                $relativePath = substr($file->filepath, $position); // extract from "file/" onward
                $relativePath = rtrim(str_replace('\\', '/', $relativePath), '/'); // ensure consistent slashes

                return config('global.base_url_web_stories') . '/' . $relativePath . '/' . ltrim($file->filename, '/');
            }
        }

        return null;
    }
}