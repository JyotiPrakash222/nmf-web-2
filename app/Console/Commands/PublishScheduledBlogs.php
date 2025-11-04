<?php
// app/Console/Commands/PublishScheduledBlogs.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Blog;
use Carbon\Carbon;

class PublishScheduledBlogs extends Command
{
    protected $signature = 'blog:publish-scheduled';
    protected $description = 'Publish blogs scheduled for publishing';

   public function handle()
{
    // Log the command run with the correct time zone (IST)
    \Log::info('Running scheduled blog publish at ' . now('Asia/Kolkata'));

    // Ensure that we're checking for posts that are due for publishing today, according to IST
    Blog::where('status', 0)
        ->whereNotNull('published_at')
        ->whereDate('published_at', '<=', now('Asia/Kolkata')->toDateString())  // Use IST
        ->update(['status' => 1]);

    // Log the scheduled time of publish in IST as well
    \Log::info('Scheduled blog published at ' . now('Asia/Kolkata'));
}

}
