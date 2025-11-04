<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Models\File;
use App\Models\Role;
use App\Models\BlogVideoGifs;

// Cache wrapper
if (! function_exists('cached_helper')) {
    function cached_helper(string $helperName, mixed $param = null, int $ttl = 3600)
    {
        $key = is_object($param) && property_exists($param, 'id')
            ? "helper_cache_{$helperName}_{$param->id}"
            : "helper_cache_{$helperName}_" . md5(json_encode($param));

        return Cache::remember($key, $ttl, function () use ($helperName, $param) {
            return $param !== null ? $helperName($param) : $helperName();
        });
    }
}

// Actual helpers
if (! function_exists('blog_images_everywhere')) {
    function blog_images_everywhere($blog) {
        if (!$blog) return 'nmf-home.jpg';

        $blog_file = !empty($blog->image_ids) && empty($blog->link)
            ? File::find($blog->image_ids)
            : File::find($blog->thumb_images);

        if (!$blog_file) return null;

        $basePath = $blog_file->full_path;

        if (strpos($basePath, 'file') !== false) {
            $findFilePos = strpos($basePath, 'file');
            return (config('global.base_url_image')  . substr($basePath, $findFilePos) . '/' . $blog_file->file_name);
        }

        return null;
    }
}

if (! function_exists('getuser_role')) {
    function getuser_role() {
        $user = Auth::user();
        if (!$user) abort(403, 'Unauthorized');

        return Role::find($user->role);
    }
}

if (! function_exists('blog_video_gifs_everywhere')) {
    function blog_video_gifs_everywhere($blog) {
        if (empty($blog) || empty($blog->id)) return null;

        $gif_file = BlogVideoGifs::where('blog_id', $blog->id)->first();
        if (!$gif_file) return null;

        $basePath = $gif_file->gif_path;
        if (strpos($basePath, 'file') !== false) {
            $findFilePos = strpos($basePath, 'file');
            return asset(substr($basePath, $findFilePos) . '/' . $gif_file->gif_image);
        }

        return null;
    }
}
if (! function_exists('cached_video_thumb')) {
    function cached_video_thumb($video, $seconds = 3600) {
        if (! $video) {
            return config('global.base_url_image').'nmf-home.jpg';
        }

        return Cache::remember("video_thumb_{$video->id}", $seconds, function () use ($video) {
            // base_url_videos is just a string in config, so keep it:
            return config('global.base_url_videos') . $video->thumbnail_path;
        });
    }
}
if (! function_exists('cached_blog_image')) {
    function cached_blog_image($blog, $seconds = 3600)
    {
        if (! $blog) {
            return config('global.base_url_image').'nmf-home.jpg';
        }

        return Cache::remember("blog_image_{$blog->id}", $seconds, function () use ($blog) {
            // Use your existing blog_images_everywhere() logic
            return blog_images_everywhere($blog);
        });
    }
}

if (! function_exists('webstory_image_everywhere')) {
    function webstory_image_everywhere($webStory) {
        if (! $webStory) return config('global.base_url_image').'nmf-home.jpg';

        $story_file = optional($webStory->webStoryFiles->first());
        if (! $story_file) return config('global.base_url_image').'nmf-home.jpg';

        $story_file_name = $story_file->filepath;
        $baseUrl = config('global.base_url_web_stories');

        if ($story_file_name && strpos($story_file_name, 'file') !== false) {
            $findFilePos = strpos($story_file_name, 'file');
            $story_file_path = substr($story_file_name, $findFilePos);
            return $baseUrl . $story_file_path . '/' . $story_file->filename;
        }

        return config('global.base_url_image').'nmf-home.jpg';
    }
}

if (! function_exists('cached_webstory_image')) {
    function cached_webstory_image($webStory, $seconds = 3600)
    {
        if (! $webStory) {
            return config('global.base_url_image').'nmf-home.jpg';
        }

        return Cache::remember("webstory_image_{$webStory->id}", $seconds, function () use ($webStory) {
            return webstory_image_everywhere($webStory);
        });
    }
}

if (! function_exists('big_event_background_url')) {
    function big_event_background_url($bigEvent, $seconds = 3600)
    {
        if (! $bigEvent || empty($bigEvent->background_image)) {
            return asset('asset/images/default-background.jpg'); // fallback
        }

        return Cache::remember("big_event_bg_{$bigEvent->id}", $seconds, function () use ($bigEvent) {
            return config('global.base_url_big_event') . $bigEvent->background_image;
        });
    }
}

if (! function_exists('big_event_banner_url')) {
    function big_event_banner_url($bigEvent, $seconds = 3600)
    {
        if (! $bigEvent || empty($bigEvent->banner_image)) {
            return config('global.base_url_asset').'asset/images/default-banner.jpg'; // fallback
        }

        return Cache::remember("big_event_banner_{$bigEvent->id}", $seconds, function () use ($bigEvent) {
            return config('global.base_url_big_event') . $bigEvent->banner_image;
        });
    }
}

if (! function_exists('big_event_video_url')) {
    function big_event_video_url($bigEvent, $seconds = 3600)
    {
        if (! $bigEvent || empty($bigEvent->video_path)) {
            return ''; // or a default video
        }

        return Cache::remember("big_event_video_{$bigEvent->id}", $seconds, function () use ($bigEvent) {
            return config('global.base_url_big_event') . $bigEvent->video_path;
        });
    }
}

if (! function_exists('big_event_poster_url')) {
    function big_event_poster_url($bigEvent, $seconds = 3600)
    {
        if (! $bigEvent) {
            return asset('asset/images/default-poster.jpg');
        }

        return Cache::remember("big_event_poster_{$bigEvent->id}", $seconds, function () use ($bigEvent) {
            // if a custom thumb exists, use it; else fallback to event_image
            $posterPath = $bigEvent->video_thumb ?: $bigEvent->event_image;
            return config('global.base_url_big_event') . $posterPath;
        });
    }
}
if (! function_exists('big_event_image_url')) {
    function big_event_image_url($bigEvent, $seconds = 3600)
    {
        if (! $bigEvent) {
            return asset('asset/images/default-event.jpg');
        }

        return Cache::remember("big_event_image_{$bigEvent->id}", $seconds, function () use ($bigEvent) {
            $imagePath = $bigEvent->video_thumb ?: $bigEvent->event_image;
            return config('global.base_url_big_event') . $imagePath;
        });
    }
}





