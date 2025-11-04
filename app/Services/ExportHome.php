<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ExportHome
{
    public function run()
    {
    
    
         // Clear Laravel caches before capturing homepage
        Artisan::call('cache:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
       // Artisan::call('config:clear'); // optional
    
        // Simulate a GET request to the real homepage route
        $request = Request::create('/', 'GET'); // <-- hit your actual homepage
        $response = app()->handle($request);

        // Get the HTML content
        $html = $response->getContent();

        // Save to public/index.html
        File::put(public_path('index.html'), $html);
    }
}
