<?php

namespace App\Http\Controllers;
use App\Models\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use getID3;

class VideoFileController extends Controller
{
    public function videosFile(Request $request)
    {    
        $files = File::orderBy('id', 'DESC');
        if (isset($request->title)) {
            $files->Where('file_name', 'like', '%' .$request->title . '%');
        }
        // $files = $files->paginate(20);

        $perPage = $request->input('perPage', 20);
        $files = $files->where('file_type', 'video/mp4')->paginate($perPage);

        if (isset($request->title)) {
            $title = $request->title;
            $files->setPath(asset('/videofiles').'?title='.$title);
        } else {
            $title = '';
            $files->setPath(asset('/videofiles'));
        }
	
	    // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }

        // Set the pagination path with query parameters
        $files->setPath(asset('/videofiles') . '?' . http_build_query($queryParams));

        return view('admin/videoFile')->with('data',['files' => $files, 'title' => $title, 'perPage' => $perPage ]);
    }
    public function videoAdd(Request $request)
    {
        return view('admin/addVideo');
    }
    public function addVideo(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:mp4|max:102400' // Max 100 MB
        ], [
            'file.max' => 'The file size exceeds the maximum limit of 100 MB.'
        ]);

        // Determine the current year and month
        $year = date('Y');
        $month = date('m');

        $basePath = public_path("file/Video");

        // Define the video path
        $destinationPath = $basePath . '/' . $year . '/' . $month;

        $originalFileName = $request->file->getClientOriginalName();
        $sanitizedFileName = str_replace(' ', '_', pathinfo($originalFileName, PATHINFO_FILENAME));
        $fileName = $sanitizedFileName . '_' . time() . '.' . $request->file->extension();

        // Move the file to the destination directory
        $request->file->move($destinationPath, $fileName);

        // Full path (directory only, without the file name)
        $fullPath = $destinationPath;

        

        // Analyze the file with getID3
        require_once('getid3/getid3.php');
        //require_once('public/getid3/getid3.php');
        //require_once('../getid3/getid3.php');

        $getID3 = new getID3();
        $filePath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;
        $fileInfo = $getID3->analyze($filePath);
        $durationInSeconds = null;
        $formattedDuration = null;

        // Check if duration metadata is available
        if (isset($fileInfo['playtime_seconds'])) {
            $durationInSeconds = (int) $fileInfo['playtime_seconds'];

            // Format the duration as HH:MM:SS
            // $hours = floor($durationInSeconds / 3600);
            // $minutes = floor(($durationInSeconds % 3600) / 60);
            // $seconds = floor($durationInSeconds % 60);
            // $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            if ($durationInSeconds >= 3600) {
                // Format as HH:MM:SS if the duration is 1 hour or more
                $hours = floor($durationInSeconds / 3600);
                $minutes = floor(($durationInSeconds % 3600) / 60);
                $seconds = $durationInSeconds % 60;
                $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            } else {
                // Format as MM:SS for durations under 1 hour
                $minutes = floor($durationInSeconds / 60);
                $seconds = $durationInSeconds % 60;
                $formattedDuration = sprintf('%02d:%02d', $minutes, $seconds);
            }
        }

        // Store file information in the database
        File::create([
            "user_id" => 1, // Replace with dynamic user ID if required
            "file_name" => $fileName, // File name only
            "file_type" => $request->file->getClientMimeType(),
            "file_size" => filesize($filePath), // Use the permanent file path
            "full_path" => $fullPath, // Directory path only
            "duration" => $formattedDuration ?? '00:00', // Store formatted duration or 'Unknown'
        ]);

        app(\App\Services\ExportHome::class)->run();
        // Redirect with success message
        return redirect(config('global.base_url').'videofiles')->with('success', 'Video has been uploaded successfully!');
    }
    public function editVideo($id)
    {
       // dump("Edit Video Method Reached, ID: " . $id);
        $file = File::find($id); // More efficient way to fetch record
        //dump($file);
    
        if (!$file) {
            dump("No file found with ID: " . $id);
        }
        return view('admin/editVideo')->with('file', $file);
    }
    public function videoEdit($id, Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|file|mimes:mp4|max:102400' // Max 100 MB
        ], [
            'file.max' => 'The file size exceeds the maximum limit of 100 MB.'
        ]);

        // Determine the current year and month
        $year = date('Y');
        $month = date('m');

        $basePath = public_path("file/Video");

        // Define the video path
        $destinationPath = $basePath . '/' . $year . '/' . $month;

        $originalFileName = $request->file->getClientOriginalName();
        $sanitizedFileName = str_replace(' ', '_', pathinfo($originalFileName, PATHINFO_FILENAME));
        $fileName = $sanitizedFileName . '_' . time() . '.' . $request->file->extension();

        // Move the file to the destination directory
        $request->file->move($destinationPath, $fileName);

        // Full path (directory only, without the file name)
        $fullPath = $destinationPath;

        // Analyze the file with getID3
        require_once('getid3/getid3.php');
        //require_once('public/getid3/getid3.php');
        //require_once('../getid3/getid3.php');

        $getID3 = new getID3();
        $filePath = $destinationPath . DIRECTORY_SEPARATOR . $fileName;
        $fileInfo = $getID3->analyze($filePath);

        $durationInSeconds = null;
        $formattedDuration = null;

        // Check if duration metadata is available
        if (isset($fileInfo['playtime_seconds'])) {
            $durationInSeconds = (int) $fileInfo['playtime_seconds'];

            // Format the duration as HH:MM:SS
            // $hours = floor($durationInSeconds / 3600);
            // $minutes = floor(($durationInSeconds % 3600) / 60);
            // $seconds = floor($durationInSeconds % 60);
            // $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            if ($durationInSeconds >= 3600) {
                // Format as HH:MM:SS if the duration is 1 hour or more
                $hours = floor($durationInSeconds / 3600);
                $minutes = floor(($durationInSeconds % 3600) / 60);
                $seconds = $durationInSeconds % 60;
                $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            } else {
                // Format as MM:SS for durations under 1 hour
                $minutes = floor($durationInSeconds / 60);
                $seconds = $durationInSeconds % 60;
                $formattedDuration = sprintf('%02d:%02d', $minutes, $seconds);
            }
        }

        // Store file information in the database
        File::where('id', $id)->update([
            "user_id" => 1, // Replace with dynamic user ID if required
            "file_name" => $fileName, // File name only
            "file_type" => $request->file->getClientMimeType(),
            "file_size" => filesize($filePath), // Use the permanent file path
            "full_path" => $fullPath, // Directory path only
            "duration" => $formattedDuration ?? '00:00', // Store formatted duration or 'Unknown'
        ]);

        // Redirect with success message
         app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'videofiles')->with('success', 'Video has been edited successfully!');
    }
    public function uploadVideo(Request $request)
    {
        $year = date('Y');
        $month = date('m');

        $destinationPath = public_path("filevideo/$year/$month");

        // Get the uploaded file
        $file = $request->file('file');

        // Get the file's original extension
        $extension = $file->getClientOriginalExtension();

        // Get the original filename without extension
        $originalFileName = $file->getClientOriginalName();
        $fileNameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);

        // Replace spaces with underscores and add a timestamp for uniqueness
        $fileName = str_replace(' ', '_', $fileNameWithoutExt) . time() . '.' . $extension;
        
        $file_data = File::create(
            [
                "user_id" => '1',
                "file_name" => $fileName,
                "file_type" => $request->file->getClientMimeType(),
                "file_size" => $request->file->getSize(),
                "full_path" => public_path('file'),
            ]
        );
        //Move the file to the destination path
        $file->move($destinationPath, $fileName);
         app(\App\Services\ExportHome::class)->run();
        return response()->json(['file_id' => $file_data->id, 'file_name' => $fileName, 'box' => $request->box, 'success'=> true]);
    }
    public function del($id, Request $request) 
    {
        ?>
        <script>
            if (confirm('Are you sure? This action will permanently delete this video.')) {
                window.location.href =  '<?php echo asset('/videofiles/del').'/'.$id; ?>'
            } else {
                window.location.href =  '<?php echo asset('/videofiles'); ?>'
            }
        </script>
        <?php
    }
    public function deleteVideo($id, Request $request)
    {
        // Retrieve the Video record
        $file = File::find($id);

        $videoPath = $file->full_path;
        if (!empty($videoPath)) {
            if (strpos($videoPath, 'file') !== false) 
            {
                $findFilePos = strpos($videoPath, 'file');                      
                $videoFilePath = substr($videoPath, $findFilePos);
                $videoFilePath = $videoFilePath . '/' . $file->file_name;
            }
        }

        // Delete video file if it exists
        if (!empty($videoFilePath) && file_exists($videoFilePath)) {
            unlink($videoFilePath);
        }

        // Delete the database record
        $file->delete();
        app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'videofiles')->with('success', 'Video has been deleted successfully.');
    }
    
}

