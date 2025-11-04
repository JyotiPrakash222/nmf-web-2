<?php
namespace App\Observers;

use App\Models\Blog;
use App\Services\ExportHome;
use Illuminate\Support\Facades\Log;

class BlogObserver
{
    public function saved(Blog $blog)
    {
        Log::info('Blog saved event fired', ['id' => $blog->id]);
        app(ExportHome::class)->run();
    }

    public function deleted(Blog $blog)
    {
        Log::info('Blog deleted event fired', ['id' => $blog->id]);
        app(ExportHome::class)->run();
    }
}
