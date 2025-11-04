<?php

// app/Http/Controllers/PlayerController.php

namespace App\Http\Controllers;

use App\Models\Video; // Make sure you import your Video model
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function show($cat_name, $name)
    {
        // Find the video by its 'eng_name'. Eager load the category.
        $video = Video::with('category')->where('eng_name', $name)->firstOrFail();

        // Fetch related videos (e.g., from the same category, excluding the current one)
        $relatedVideos = Video::where('category_id', $video->category_id)
                              ->where('id', '!=', $video->id)
                              ->latest() // Order by most recent
                              ->take(5)    // Limit to 5
                              ->get();

        // Pass both the main video and related videos to the view
        return view('show_video', [
            'video' => $video,
            'relatedVideos' => $relatedVideos
        ]);
    }
}
