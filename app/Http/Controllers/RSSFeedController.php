<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Support\Facades\Response;
use App\Models\Blog;

class RSSFeedController extends Controller
{
    public function index()
    {
       $blogs = Blog::where('status', 1)
    ->with('category')
    ->whereHas('category') // ensures category exists
    ->latest()
    ->take(20)
    ->get();



        $rss = view('feed', compact('blogs'));

        return Response::make($rss, 200, [
            'Content-Type' => 'application/rss+xml; charset=UTF-8'
        ]);
    }
}
