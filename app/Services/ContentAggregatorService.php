<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Video;
use App\Models\Clip;
use Illuminate\Support\Collection;

class ContentAggregatorService
{
    /**
     * Fetch and normalize items from Blogs, Videos, Clips.
     *
     * @param int $catId
     * @param array $limits ['blogs' => 10, 'videos' => 7, 'clips' => 7]
     * @return \Illuminate\Support\Collection
     */
    public function getAllItems(int $catId, array $limits = []): Collection
    {
        $defaults = [
            'blogs' => 10,
            'videos' => 5,
            'clips' => 5,
        ];

        $limits = array_merge($defaults, $limits);
        $allItems = collect();

        // Blogs
        $blogs = Blog::where('status', '1')
            ->where('categories_ids', $catId)
            ->where('sequence_id', '0')
            ->where('isLive', '0')
            ->orderByDesc('id')
            ->take($limits['blogs'])
            ->get();

        $allItems = $allItems->merge(
            $blogs->map(function ($item) {
                $categorySlug = $item->category->site_url ?? '';
                $categoryName = optional($item->category)->name ?? '';
                //$imageUrl = config('global.blog_images_everywhere')($item);
               
                   $imageUrl = \cached_blog_image($item); 

                $blogUrl = isset($item->site_url) ? asset($categorySlug . '/' . $item->site_url) : '';

                return [
                    'id' => $item->id,
                    'title' => isset($item->short_title) && $item->short_title ? $item->short_title : $item->name,
                    'url' => $blogUrl,
                    'image' => $imageUrl,
                    'type' => 'blog',
                    'link' => $item->link ?? null,
                    'category' => $categoryName,
                    'category_url' => $categorySlug,
                    'created_at' => $item->created_at,
                ];
            }),
        );

        // Videos
        $videos = Video::where('is_active', '1')
            ->where('category_id', $catId)
            ->orderByDesc('id')
            ->take($limits['videos'])
            ->get();

        $allItems = $allItems->merge(
            $videos->map(function ($item) {
                $categorySlug = $item->category->site_url ?? '';
                $categoryName = optional($item->category)->name ?? '';
               // $imageUrl = config('global.base_url_videos') . $item->thumbnail_path;
                $imageUrl = \cached_video_thumb($item);
                $videoUrl = $item->site_url ? asset('video/' . $categorySlug . '/' . $item->site_url) : '';

                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'url' => $videoUrl,
                    'image' => $imageUrl,
                    'type' => 'video',
                    'link' => null,
                    'category' => $categoryName,
                    'category_url' => $categorySlug,
                    'created_at' => $item->created_at,
                ];
            }),
        );

        // Clips
        // $clips = Clip::where('status', 1)
        //     ->where('categories_id', $catId)
        //     ->with('category')
        //     ->orderByDesc('id')
        //     ->take($limits['clips'])
        //     ->get();

        // $allItems = $allItems->merge(
        //     $clips->map(function ($item) {
        //         $catUrl = optional($item->category)->site_url;
        //         $thumbUrl = '';

        //         if ($item->image_path && $item->thumb_image && strpos($item->image_path, 'file') !== false) {
        //             $imagePath = str_replace('\\', '/', $item->image_path); // Normalize slashes
        //             $folderPath = substr($imagePath, strpos($imagePath, 'file')); // Keep everything from 'file/...'
        //             $thumbUrl = asset($folderPath . '/' . $item->thumb_image);
        //         }

        //         $clipUrl = url('short-videos/' . trim($catUrl, '/') . '/' . $item->site_url);

        //         return [
        //             'id' => $item->id,
        //             'title' => $item->title,
        //             'url' => $clipUrl,
        //             'image' => $thumbUrl,
        //             'type' => 'clip',
        //             'link' => null,
        //             'category' => optional($item->category)->name ?? '',
        //             'category_url' => $catUrl,
        //             'created_at' => $item->created_at,
        //         ];
        //     }),
        // );

        return $allItems->sortByDesc('created_at')->values();
    }
}
