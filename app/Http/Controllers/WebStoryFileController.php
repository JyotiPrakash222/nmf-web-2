<?php

namespace App\Http\Controllers;

use App\Models\WebStories;
use App\Models\WebStoryFiles;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File as FileFacade;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver as GdDriver;

class WebStoryFileController extends Controller
{
    public function webStoriesFile($id, Request $request)
    {
        $query = WebStoryFiles::where('webstories_id', $id)->orderBy('file_sequence', 'asc');
        $status = WebStories::where('id', $id)->value('status');

        if (isset($request->title)) {
            $query->where('filename', 'like', '%' . $request->title . '%');
        }
        $perPage = $request->input('perPage', 20);

        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }
        $files = $query->paginate($perPage);

        if (isset($request->title)) {
            $title = $request->title;
            $files->setPath(asset('/webstory-files') . '?title=' . $title);
        } else {
            $title = '';
            $files->setPath(asset('/webstory-files'));
        }

        // Set the pagination path with query parameters
        $files->setPath(url('/webstory/webstory-files') . '?' . http_build_query($queryParams));

        return view('admin/webStoryFileList')->with('data', [
            'files' => $files,
            'title' => $title,
            'perPage' => $perPage,
            'webstory_id' => $id,
            'status' => $status,
        ]);
    }
    public function webStoryFileAdd(Request $request)
    {
        $get_webstories = WebStories::all();
        $selected_webstory_id = $request->query('id'); // get ?id=5 from the URL

        return view('admin/addWebStoryFile')->with('data', [
            'get_webstories' => $get_webstories,
            'selected_webstory_id' => $selected_webstory_id,
        ]);
    }
     public function addWebStoryFile(Request $request)
    {
        $rules = [
            'description' => 'required|string',
            'file_type' => 'required|in:image,video',
        ];

        if ($request->file_type === 'image') {
           // $rules['image_file'] = 'required|file|mimes:jpg,jpeg,png|max:200'; // Max 200 KB
              $rules['image_file'] = 'required|file|mimes:jpg,jpeg,png,webp|max:200'; // Max 200 KB
        } elseif ($request->file_type === 'video') {
           // $rules['image_file'] = 'required|file|mimes:jpg,jpeg,png,webp|max:200'; // Thumbnail
           $rules['image_file'] = 'required|file|mimetypes:image/webp,image/x-webp,image/png,image/jpeg|max:200';

            $rules['video_file'] = 'required|file|mimes:mp4|max:20000'; // Max 20MB
        }

        $messages = [
            'image_file.required' => 'Please upload an image.',
            'image_file.mimes' => 'Only JPG, JPEG, PNG, webp formats are allowed for image.',
            'image_file.max' => 'The image size must not exceed 200 KB.',

            'video_file.required' => 'Please upload a video.',
            'video_file.mimes' => 'Only MP4 format is allowed for video.',
            'video_file.max' => 'The video size must not exceed 20 MB.',
        ];

        $request->validate($rules, $messages);

        // Determine the current year and month
        $year = date('Y');
        $month = date('m');

        $basePath = public_path("file/webstories/$year/$month");
        $videoPath = public_path("file/webstories/videos/$year/$month");

        FileFacade::makeDirectory($basePath, 0755, true, true);
        FileFacade::makeDirectory($videoPath, 0755, true, true);

        $webstoryId = $request->input('webstories_id');
        $fileType = $request->file_type;
        $fileName = null;
        $thumbPath = null;

        $manager = new ImageManager(new GdDriver());

       if ($fileType === 'image') {
            $image = $request->file('image_file');

            // Create unique filename
            $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $fileName = str_replace(' ', '_', $fileName) . time();

            // Read directly from uploaded file (tmp path)
            $img = $manager->read($image->getRealPath());

            // Save as WebP only
            $webpFile = $fileName . ".webp";
            $img->toWebp(80)->save($basePath . '/' . $webpFile);

            // Final stored name
            $fileName = $webpFile;
        }

        // ✅ Video Upload & Save (Thumbnail → WebP, No ffmpeg conversion)
        if ($fileType === 'video') {
            $image = $request->file('image_file'); // Thumbnail
            $video = $request->file('video_file');

            // Video save
            $videoFileName = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
            $videoFileName = str_replace(' ', '_', $videoFileName) . time() . '.' . $video->getClientOriginalExtension();
            $video->move($videoPath, $videoFileName);

            // Thumbnail save as WebP only
            $thumbFileName = 'thumb_' . time();
            $img = $manager->read($image->getRealPath());
            $webpThumb = $thumbFileName . ".webp";
            $img->toWebp(80)->save($basePath . '/' . $webpThumb);

            $thumbPath = "$basePath/$webpThumb";

            // Final stored file
            $fileName = $videoFileName;
        }

            WebStoryFiles::create([
                'webstories_id' => $webstoryId,
                'display_status' => '1',
                'file_type' => $fileType,
                'filename' => $fileName,
                'filepath' => ($fileType === 'video') ? $videoPath : $basePath,
                'thumb_path' => $thumbPath,
                'description' => $request->description,
                'desc_eng' => $request->desc_eng,
                'credit' => $request->credit,
                'file_sequence' => 0,
            ]);

            app(\App\Services\ExportHome::class)->run();


            return redirect(config('global.base_url').'webstory/webstory-files/' . $webstoryId)
                ->with('success', ucfirst($fileType) . ' has been uploaded successfully!');
    }

    public function uploadWebStoryFile(Request $request)
    {
        $year = date('Y');
        $month = date('m');
        $basePath = public_path("file/webstories");

        // Define the destination path
        $destinationPath = $basePath . '/' . $year . '/' . $month;

        // Create the directories if they don't exist
        // FileFacade::makeDirectory($destinationPath, 0755, true, true);

        $file = $request->file('file');

        // Get the file's original extension
        $extension = $file->getClientOriginalExtension();

        // Get the original filename without extension
        $originalFileName = $file->getClientOriginalName();
        $fileNameWithoutExt = pathinfo($originalFileName, PATHINFO_FILENAME);

        // Replace spaces with underscores and add a timestamp for uniqueness
        $fileName = str_replace(' ', '_', $fileNameWithoutExt) . time() . '.' . $extension;

        $file_data = WebStoryFiles::create([
            "webstories_id" => $request->webstory,
            "display_status" => '1',
            "filename" => $fileName,
            "filepath" => $destinationPath,
            "description" => $request->description,
            "desc_eng" => $request->desc_eng,
            "credit" => $request->credit,
        ]);

        $file->move($destinationPath, $fileName);
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
        return response()->json([
            'file_id' => $file_data->id,
            'filename' => $fileName,
            //'box' => $request->box,
            'success' => true,
        ]);
    }
    public function editWebStoryFile($id)
    {
        $file = WebStoryFiles::find($id); // More efficient way to fetch record
        if (!$file) {
            dump("No file found with ID: " . $id);
        }
        $categories = WebStories::get()->all();
        return view('admin/editWebStoryFile')->with('data', ['file' => $file, 'categories' => $categories]);
    }
    public function webStoryFileEdit($id, Request $request)
    {
        $request->validate([
            'description' => 'required|string',
            'file_type' => 'required|in:image,video',
            'webstory' => 'required|integer|exists:webstories,id',
            'image_file' => 'sometimes|file|mimes:jpg,jpeg,png|max:200',
            'video_file' => 'sometimes|required_if:file_type,video|file|mimes:mp4|max:20000',
        ], [
            'image_file.max' => 'The image size must not exceed 200 KB.',
            'video_file.required_if' => 'Please upload a video.',
            'video_file.mimes' => 'Only MP4 format is allowed for video.',
            'video_file.max' => 'The video size must not exceed 20 MB.',
            'webstory.exists' => 'The webstory field is required.'
        ]);

        $year = date('Y');
        $month = date('m');

        $basePath = public_path("file/webstories/$year/$month");
        $videoPath = public_path("file/webstories/videos/$year/$month");

        FileFacade::makeDirectory($basePath, 0755, true, true);
        FileFacade::makeDirectory($videoPath, 0755, true, true);

        $fileType = $request->file_type;
        $fileName = null;
        $thumbPath = null;

        // Find existing record
        $webStoryFile = WebStoryFiles::findOrFail($id);

        if ($fileType === 'image') {
            if (!$request->hasFile('image_file') && empty($webStoryFile->filename)) {
                return redirect()->back()->withErrors(['image_file' => 'Please upload an image.']);
            }
        }

        // Additional server-side validation for video file type
        if ($fileType === 'video') {
            $existingVideo = $webStoryFile->filename && FileFacade::exists($webStoryFile->filepath . '/' . $webStoryFile->filename);
            $existingThumb = $webStoryFile->thumb_path && FileFacade::exists($webStoryFile->thumb_path);
            $hasNewVideo = $request->hasFile('video_file');
            $hasNewThumb = $request->hasFile('image_file');

            if (!$existingVideo && !$hasNewVideo) {
                return back()->withErrors(['video_file' => 'Please upload a video file.']);
            }

            if (!$existingThumb && !$hasNewThumb) {
                return back()->withErrors(['image_file' => 'Please upload a thumbnail image.']);
            }
        }

        if ($fileType === 'image' && $request->hasFile('image_file')) {
            // Delete old image
            if ($webStoryFile->filename && $webStoryFile->filepath) {
                $oldFile = $webStoryFile->filepath . '/' . $webStoryFile->filename;
                if (FileFacade::exists($oldFile)) {
                    FileFacade::delete($oldFile);
                }
            }

            $image = $request->file('image_file');
            $originalName = $image->getClientOriginalName();
            $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '', pathinfo($originalName, PATHINFO_FILENAME));
            $cleanName = str_replace(' ', '_', $cleanName) . '_';
            $fileName = $cleanName . time() . '.' . $image->getClientOriginalExtension();
            $image->move($basePath, $fileName);
        }

        if ($fileType === 'video') {

            if ($request->hasFile('video_file')) {
                // Delete old video file
                if ($webStoryFile->filename && $webStoryFile->filepath) {
                    $oldVideoFile = $webStoryFile->filepath . '/' . $webStoryFile->filename;
                    if (FileFacade::exists($oldVideoFile)) {
                        FileFacade::delete($oldVideoFile);
                    }
                }

                $video = $request->file('video_file');
                $cleanVideo = preg_replace('/[^A-Za-z0-9_\-]/', '', pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME));
                $videoFileName = str_replace(' ', '_', $cleanVideo) . time() . '.' . $video->getClientOriginalExtension();
                $video->move($videoPath, $videoFileName);
                $fileName = $videoFileName;
            }

            // If new thumbnail uploaded
            if ($request->hasFile('image_file')) {
                // Delete old thumbnail
                if ($webStoryFile->thumb_path && FileFacade::exists($webStoryFile->thumb_path)) {
                    FileFacade::delete($webStoryFile->thumb_path);
                }

                $image = $request->file('image_file');
                $cleanThumb = preg_replace('/[^A-Za-z0-9_\-]/', '', pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
                $thumbFileName = 'thumb_' . time() . '.' . $image->getClientOriginalExtension();
                $image->move($basePath, $thumbFileName);
                $thumbPath = "$basePath/$thumbFileName";
            }
        }

        // Prepare update data
        $updateData = [
            'webstories_id' => $request->webstory,
            'display_status' => '1',
            'file_type' => $fileType,
            'description' => $request->description,
            'desc_eng' => $request->desc_eng,
            'credit' => $request->credit,
        ];

        // If new file was uploaded
        if ($fileName) {
            $updateData['filename'] = $fileName;
            $updateData['filepath'] = ($fileType === 'video') ? $videoPath : $basePath;
        }

        if ($thumbPath) {
            $updateData['thumb_path'] = $thumbPath;
        }

        WebStoryFiles::where('id', $id)->update($updateData);
       try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
        return redirect(config('global.base_url').'webstory/webstory-files/' . $request->webstory)
            ->with('success', ucfirst($fileType) . ' has been updated successfully!');
    }

    public function deleteWebStoryFile($id, Request $request)
    {
        // Retrieve the Image01 record
        $file = WebStoryFiles::find($id);

        $imagePath = $file->filepath;
        if (strpos($imagePath, 'file') !== false) {
            $findFilePos = strpos($imagePath, 'file');
            $imageFilePath = substr($imagePath, $findFilePos);
            $imageFilePath = $imageFilePath . '/' . $file->filename;
        }

        // Delete image file if it exists
        if (!empty($file->filename) && file_exists($imageFilePath)) {
            unlink($imageFilePath);
        }

        // Delete the database record
        $file->delete();
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
        return redirect(config('global.base_url').'webstory/webstory-files/' . $file->webstories_id . '?t=' . time())->with('success', 'Image has been deleted successfully.');
    }

    public function allwebStoryList(Request $request)
    {
        $query = WebStories::orderBy('sequence', 'asc');

        if (isset($request->title)) {
            $query->where('name', 'like', '%' . $request->title . '%');
        }
        $perPage = $request->input('perPage', 20);

        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }
        $webstories = $query->paginate($perPage);

        // Set the pagination path with query parameters
        $webstories->setPath(asset('/webstory/file-sequence') . '?' . http_build_query($queryParams));

        return view('admin.webStoryFileSequenceList', compact('webstories', 'perPage'));
    }

    public function getwebstoryFilesById($id)
    {
        // Get the web story by ID
        $webStory = WebStories::findOrFail($id);

        // Fetch files associated with the web story
        $webStoryFiles = WebStoryFiles::where('webstories_id', $id)->orderBy('file_sequence', 'asc')->get();

        return view('admin.webStoryFileSequence', compact('webStoryFiles', 'id'));
    }

    public function updatewebstoryFileSequence(Request $request)
    {
        Log::info('Received request to update web story file sequence', ['request' => $request->all()]);
        $order = $request->webstoryFiles;

        foreach ($order as $item) {
            WebStoryFiles::where('id', $item['webstoryFile_id'])
                ->update(['file_sequence' => $item['position']]);
        }

        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
        return response()->json(['success' => true]);
    }
}
