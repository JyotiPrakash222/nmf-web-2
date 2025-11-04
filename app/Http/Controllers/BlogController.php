<?php

namespace App\Http\Controllers;

require_once base_path('vendor/autoload.php');

use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\Category;
use App\Models\File;
use App\Models\State;
use App\Models\District;
use App\Models\LiveBlog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Google\Client;
use Google\Service\FirebaseCloudMessaging;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\NotificationService;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        //$blogs = Blog::orderBy('created_at', 'DESC');
        //$blogs = Blog::where('status', 1)->orderBy('created_at', 'DESC');
        $blogs = Blog::where('status', Blog::STATUS_PUBLISHED)
            ->orderBy('created_at', 'DESC');
        if (isset($request->title)) {
            $blogs->Where('name', 'like', '%' . $request->title . '%');
        }
        if (isset($request->category)) {
            $blogs->where('categories_ids', $request->category);
        }
        if (isset($request->status)) {
            // echo "Coming here=" . $request->status;
            if ($request->status == 0) {
                $blogs->where('breaking_status', 1);
            } else if ($request->status == 1) {
                $blogs->where('sequence_id', ">", 0);
            } else if ($request->status == 2) {
                $blogs->where('ispodcast_homepage', 1);
            } else {
                $blogs->where('status', $request->status);
            }
        }
        if (isset($request->author)) {
            $blogs->where('author', $request->author);
        }

        // $blogs = $blogs->paginate(30);

        $perPage = $request->input('perPage', 30);
        $blogs = $blogs->paginate($perPage);

        if (isset($request->category)) {
            $category = $request->category;
            $title = $request->title;
            $blogs->setPath(asset('/posts') . '?category=' . $request->category . '&title=' . $title . '&status=' . $request->status . '&author=' . $request->author);
        } else {
            $title = '';
            $category = 0;
            $blogs->setPath(asset('/posts'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        // Set the pagination path with query parameters
        $blogs->setPath(asset('/posts') . '?' . http_build_query($queryParams));

        return view('admin/articleList')->with('data', ['blogs' => $blogs, 'category' => $request->category, 'title' => $request->title, 'author' => $request->author, 'status' => $request->status, 'perPage' => $perPage]);
    }

    public function archivelist(Request $request)
    {
        $blogs = Blog::where('status', Blog::STATUS_ARCHIVED)
            ->orderBy('created_at', 'DESC');
        if (isset($request->title)) {
            $blogs->Where('name', 'like', '%' . $request->title . '%');
        }
        if (isset($request->category)) {
            $blogs->where('categories_ids', $request->category);
        }
        if (isset($request->status)) {
            // echo "Coming here=" . $request->status;
            if ($request->status == 0) {
                $blogs->where('breaking_status', 1);
            } else if ($request->status == 1) {
                $blogs->where('sequence_id', ">", 0);
            } else if ($request->status == 2) {
                $blogs->where('ispodcast_homepage', 1);
            } else {
                $blogs->where('status', $request->status);
            }
        }
        if (isset($request->author)) {
            $blogs->where('author', $request->author);
        }

        // $blogs = $blogs->paginate(30);

        $perPage = $request->input('perPage', 30);
        $blogs = $blogs->paginate($perPage);

        if (isset($request->category)) {
            $category = $request->category;
            $title = $request->title;
            $blogs->setPath(asset('/posts') . '?category=' . $request->category . '&title=' . $title . '&status=' . $request->status . '&author=' . $request->author);
        } else {
            $title = '';
            $category = 0;
            $blogs->setPath(asset('/posts'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        // Set the pagination path with query parameters
        $blogs->setPath(asset('/posts/archive') . '?' . http_build_query($queryParams));

        return view('admin/articleArchiveList')->with('data', ['blogs' => $blogs, 'category' => $request->category, 'title' => $request->title, 'author' => $request->author, 'status' => $request->status, 'perPage' => $perPage]);
    }

    public function draftList(Request $request)
    {
        $blogs = Blog::where('status', Blog::STATUS_DRAFT)
            ->orderBy('created_at', 'DESC');
        if (isset($request->title)) {
            $blogs->Where('name', 'like', '%' . $request->title . '%');
        }
        if (isset($request->category)) {
            $blogs->where('categories_ids', $request->category);
        }
        if (isset($request->status)) {
            // echo "Coming here=" . $request->status;
            if ($request->status == 0) {
                $blogs->where('breaking_status', 1);
            } else if ($request->status == 1) {
                $blogs->where('sequence_id', ">", 0);
            } else if ($request->status == 2) {
                $blogs->where('ispodcast_homepage', 1);
            } else {
                $blogs->where('status', $request->status);
            }
        }
        if (isset($request->author)) {
            $blogs->where('author', $request->author);
        }

        // $blogs = $blogs->paginate(30);

        $perPage = $request->input('perPage', 30);
        $blogs = $blogs->paginate($perPage);

        if (isset($request->category)) {
            $category = $request->category;
            $title = $request->title;
            $blogs->setPath(asset('/posts') . '?category=' . $request->category . '&title=' . $title . '&status=' . $request->status . '&author=' . $request->author);
        } else {
            $title = '';
            $category = 0;
            $blogs->setPath(asset('/posts'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        // Set the pagination path with query parameters
        $blogs->setPath(asset('/posts/draft') . '?' . http_build_query($queryParams));

        return view('admin/articleDraftList')->with('data', ['blogs' => $blogs, 'category' => $request->category, 'title' => $request->title, 'author' => $request->author, 'status' => $request->status, 'perPage' => $perPage]);
    }

    public function unpublishedList(Request $request)
    {
        $blogs = Blog::where('status', Blog::STATUS_UNPUBLISHED)
            ->orderBy('created_at', 'DESC');
        if (isset($request->title)) {
            $blogs->Where('name', 'like', '%' . $request->title . '%');
        }
        if (isset($request->category)) {
            $blogs->where('categories_ids', $request->category);
        }
        if (isset($request->status)) {
            // echo "Coming here=" . $request->status;
            if ($request->status == 0) {
                $blogs->where('breaking_status', 1);
            } else if ($request->status == 1) {
                $blogs->where('sequence_id', ">", 0);
            } else if ($request->status == 2) {
                $blogs->where('ispodcast_homepage', 1);
            } else {
                $blogs->where('status', $request->status);
            }
        }
        if (isset($request->author)) {
            $blogs->where('author', $request->author);
        }

        // $blogs = $blogs->paginate(30);

        $perPage = $request->input('perPage', 30);
        $blogs = $blogs->paginate($perPage);

        if (isset($request->category)) {
            $category = $request->category;
            $title = $request->title;
            $blogs->setPath(asset('/posts') . '?category=' . $request->category . '&title=' . $title . '&status=' . $request->status . '&author=' . $request->author);
        } else {
            $title = '';
            $category = 0;
            $blogs->setPath(asset('/posts'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        // Set the pagination path with query parameters
        $blogs->setPath(asset('/posts/unpublished') . '?' . http_build_query($queryParams));

        return view('admin/articleUnpublishedList')->with('data', ['blogs' => $blogs, 'category' => $request->category, 'title' => $request->title, 'author' => $request->author, 'status' => $request->status, 'perPage' => $perPage]);
    }

    public function podcastList(Request $request)
    {
        $blogs = Blog::where('ispodcast_homepage', 1)->where('status', Blog::STATUS_PUBLISHED)
            ->orderBy('created_at', 'DESC');
        if (isset($request->title)) {
            $blogs->Where('name', 'like', '%' . $request->title . '%');
        }
        if (isset($request->category)) {
            $blogs->where('categories_ids', $request->category);
        }
        if (isset($request->status)) {
            // echo "Coming here=" . $request->status;
            if ($request->status == 0) {
                $blogs->where('breaking_status', 1);
            } else if ($request->status == 1) {
                $blogs->where('sequence_id', ">", 0);
            }
            // else if($request->status==2){
            //     $blogs->where('ispodcast_homepage',1);
            // }
            else {
                $blogs->where('status', $request->status);
            }
        }
        if (isset($request->author)) {
            $blogs->where('author', $request->author);
        }

        // $blogs = $blogs->paginate(30);

        $perPage = $request->input('perPage', 30);
        $blogs = $blogs->paginate($perPage);

        if (isset($request->category)) {
            $category = $request->category;
            $title = $request->title;
            $blogs->setPath(asset('/posts') . '?category=' . $request->category . '&title=' . $title . '&status=' . $request->status . '&author=' . $request->author);
        } else {
            $title = '';
            $category = 0;
            $blogs->setPath(asset('/posts'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        // Set the pagination path with query parameters
        $blogs->setPath(asset('/posts/podcast') . '?' . http_build_query($queryParams));

        return view('admin/articlePodcastList')->with('data', ['blogs' => $blogs, 'category' => $request->category, 'title' => $request->title, 'author' => $request->author, 'status' => $request->status, 'perPage' => $perPage]);
    }

    public function scheduledList(Request $request)
    {
        $blogs = Blog::where('status', Blog::STATUS_SCHEDULED)
            ->orderBy('created_at', 'DESC');
        if (isset($request->title)) {
            $blogs->Where('name', 'like', '%' . $request->title . '%');
        }
        if (isset($request->category)) {
            $blogs->where('categories_ids', $request->category);
        }
        if (isset($request->status)) {
            // echo "Coming here=" . $request->status;
            if ($request->status == 0) {
                $blogs->where('breaking_status', 1);
            } else if ($request->status == 1) {
                $blogs->where('sequence_id', ">", 0);
            } else if ($request->status == 2) {
                $blogs->where('ispodcast_homepage', 1);
            } else {
                $blogs->where('status', $request->status);
            }
        }
        if (isset($request->author)) {
            $blogs->where('author', $request->author);
        }

        // $blogs = $blogs->paginate(30);

        $perPage = $request->input('perPage', 30);
        $blogs = $blogs->paginate($perPage);

        if (isset($request->category)) {
            $category = $request->category;
            $title = $request->title;
            $blogs->setPath(asset('/posts') . '?category=' . $request->category . '&title=' . $title . '&status=' . $request->status . '&author=' . $request->author);
        } else {
            $title = '';
            $category = 0;
            $blogs->setPath(asset('/posts'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        // Set the pagination path with query parameters
        $blogs->setPath(asset('/posts/scheduled') . '?' . http_build_query($queryParams));

        return view('admin/articleScheduleList')->with('data', ['blogs' => $blogs, 'category' => $request->category, 'title' => $request->title, 'author' => $request->author, 'status' => $request->status, 'perPage' => $perPage]);
    }

    public function statusBlog($id, $status)
    {
        $status = $status == 0 ? 1 : 0;
        $data = [
            'status' => $status,
            'created_at' => date('Y-m-d h:i:s'),
        ];
        Blog::where('id', $id)->update($data);
        app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'posts');
    }
    public function addBlog(Request $request)
    {

    $query = File::query();

    $keyword = trim($request->keyword);
    if ($request->filled('keyword')) {
        $query->where('file_name', 'like', '%' . $keyword . '%');
    }

   $file = $query->whereIn('file_type', ['image/jpeg', 'image/png'])
             ->paginate(18)
             ->appends([
                 'keyword' => $keyword,
                 'modal' => $request->modal,
             ]);

       $data['file'] = $file;


        //$file = File::orderBy('id', 'DESC')->paginate(12);
        $categories = Category::where('home_page_status', 1)->get();
        $state = State::where('home_page_status', 1)->get();
        $district = District::where('status', 1)->get();
        $authors = User::where('id', '!=', 6)->where('status', 1)->get();
        $data = [
            'categories' => $categories,
            'file' => $file,
            'states' => $state,
            'district' => $district,
            'authors' =>  $authors
        ];
        if ($request->has('modal')) {
           return view('admin.partials.image_modal_body', compact('data'));
        }

        return view('admin/addArticle')->with('data', $data);
    }

    public function blogAdd(Request $request)
    {
        $from = $request->input('from');

        // Clean URL and inject into request
        $url = $this->clean($request->eng_name);
        $url = strtolower(str_replace(' ', '-', trim($url)));

        // Inject into request so Laravel validator sees it
        $request->merge(['site_url' => $url]);

        $rules = [
            'name' => 'required|string',
            'sort_desc' => 'required|string',
            'category' => 'required|numeric',
            'author' => 'required|numeric',
            'eng_name' => 'required|string',
            'published_at' => 'nullable|date',
            'site_url' => [Rule::unique('blogs', 'site_url')],
            'scheduletime' => 'nullable|date',
        ];

        $messages = [
            'name.required' => 'Please enter the blog title.',
            'sort_desc.required' => 'Please enter a short description.',
            'category.required' => 'Please select a category.',
            'category.numeric' => 'Invalid category format.',
            'author.required' => 'Please select an author.',
            'author.numeric' => 'Invalid author format.',
            'eng_name.required' => 'Please enter the English name.',
            'published_at.date' => 'The publish date must be a valid date.',
            'site_url.unique' => 'A blog with this English name already exists.',
        ];

        // Optional/Conditional validation examples
        if ($request->filled('send_Notification')) {
            $rules['notification_title'] = 'required|string';
            $messages['notification_title.required'] = 'Please enter a notification title for push notification.';
        }

        // Only validate sequence_order if sequence is selected (UI-based logic)
        if ($request->has('sequence')) {
            $rules['sequence_order'] = 'required|numeric|min:1';
            $messages['sequence_order.required'] = 'Please select a sequence order.';
        } else {
            $rules['sequence_order'] = 'nullable';
        }

        // Run validation
        $request->validate($rules, $messages);


        $publishedAt =  $request->scheduletime;
        $status = Blog::determineStatusFromSchedule($request);
        $home_page_status = $request->filled('home_page_status') ? 1 : 0;
        $header_sec = $request->filled('header_sec') ? 1 : 0;
        $Ispodcast = $request->filled('ispodcast_homepage') ? 1 : 0;
        $IsNotification = $request->filled('send_Notification') ? 1 : 0;

        $state = $request->state ?? null;
        $district = $request->district ?? null;
        $cat = $request->category;
        $ima = $request->images;
        $mult_cat = isset($request->mult_cat) ? (count($request->mult_cat) > 0 ? implode(',', $request->mult_cat) : '') : '';


        //Add Sequence Logic
        $sequence = 0;
        if (
            $status === Blog::STATUS_PUBLISHED && 
            $request->has('sequence') && 
            $request->filled('sequence_order')
        ) {
            $sequence = (int) $request->sequence_order;

            // Efficient update to shift other blogs
            DB::table('blogs')
                ->whereNotNull('sequence_id')
                ->where('sequence_id', '>=', $sequence)
                ->increment('sequence_id');
        }
        else{
            if ($request->has('sequence_order') && $request->filled('sequence_order')) {
                $sequence = (int) $request->sequence_order;

                // Prevent duplicate sequence_id for scheduled blogs
                if (
                    $status !== Blog::STATUS_PUBLISHED &&
                    $sequence > 0
                ) {
                    $exists = DB::table('blogs')
                        ->where('sequence_id', $sequence)
                        ->where('status', Blog::STATUS_SCHEDULED)
                        ->exists();

                    if ($exists) {
                        return back()
                            ->withInput()
                            ->withErrors(['sequence_order' => 'Another scheduled blog already has this sequence order. Please choose a different one.']);
                    }
                }
            }
        }

        $isLive = (int) $request->input('isLive');
        $livestatus = $request->query('callfrom');
        if ($livestatus == 'live') {
            $isLive = 1;
        }
        Log::info("Is notificastion:  { $IsNotification }");

        $blog = Blog::create([
            'name' => $request->name,
            'short_title' => $request->short_title ?? '',
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            'link' => $request->link,
            'author' => $request->author,
            'home_page_status' => $home_page_status,
            'header_sec' => $header_sec,
            'status' => $status,
            'credits' => $request->credits,
            'tags' => $request->tags,
            'sort_description' => $request->sort_desc,
            'keyword' => $request->keyword,
            'state_ids' => $state,
            'district_ids' => $district,
            'thumb_images' => $request->thumb_images,
            'image_ids' => $ima,
            'categories_ids' => $cat,
            'mult_cat' => $mult_cat,
            'description' => $request->description,
            'ispodcast_homepage' => $Ispodcast,
            'AppHitCount' => $request->app_hit_count ?? 0,
            'WebHitCount' => $request->web_hit_count ?? 0,
            'sub_category_id' => $request->sub_cat,
            'sequence_id' => $status === Blog::STATUS_SCHEDULED ? 0 : $sequence,
            'scheduled_sequence_id' => $status === Blog::STATUS_SCHEDULED ? $sequence : null,
            'isLive' => $isLive,
            'published_at' => $publishedAt,
            'isNotification'=>$IsNotification
        ]);

        if ($IsNotification) {
            //$this->sendNotification($request, $blog->id);
             app(NotificationService::class)->sendNotification($request, $blog->id);
        }
        app(\App\Services\ExportHome::class)->run();

        //return redirect('posts');
        if ($request->has('draft')) {
            return redirect(config('global.base_url').'posts/draft?t=' . time());
        } else {
            return redirect(config('global.base_url').'posts?t=' . time());
        }
    }

    public function edit(Request $request, $id)
    
    {
        
         $query = File::query();

        $keyword = trim($request->keyword);
        if ($request->filled('keyword')) {
            $query->where('file_name', 'like', '%' . $keyword . '%');
        }

         $file = $query->whereIn('file_type', ['image/jpeg', 'image/png'])
                ->orderBy('id', 'DESC')
                ->paginate(12)
                ->appends([
                    'keyword' => $keyword,
                    'modal' => $request->modal,
                ]);

        $file->setPath(asset('/posts/edit') . '/' . $id);
        $blogs = Blog::where('id', $id)->with('images')->with('thumbnail')->first();
       // $file = File::orderBy('id', 'DESC')->paginate(12);
      //  $file->setPath(asset('/posts/edit') . '/' . $id);
        $categories = Category::where('home_page_status', 1)->get();
        $state = State::where('home_page_status', 1)->get();
        $district = District::where('status', 1)->get();
        $authors = User::where('id', '!=', 6)->where('status', 1)->get();


         $data = [
        'categories' => $categories,
        'file' => $file,
        'states' => $state,
        'district' => $district,
        'blogs' => $blogs,
        'authors' =>  $authors
    ];

    if ($request->has('modal')) {
        return view('admin.partials.image_modal_body', compact('data'));
    }

    return view('admin/editArticle')->with('data', $data);

        //return view('admin/editArticle')->with('data', ['categories' => $categories, 'file' => $file, 'states' => $state, 'district' => $district, 'blogs' => $blogs, 'authors' =>  $authors]);
    }

    public function editSave($id, Request $request)
    {

     

        $filteredstatus = $_GET['status'] ?? '';
        $from = $request->input('from');

        $isUnpublish = $request->boolean('isunpublish');
        $isUnpublishfooter = $request->boolean('isunpublishfooter');
        $Isbreaking = $request->has('breaking_status') ? 1 : 0;
        $IsNotification = $request->has('send_Notification') ? 1 : 0;
        $Ispodcast = $request->has('ispodcast_homepage') ? 1 : 0;

        // Generate site_url from eng_name
        $url = $this->clean($request->eng_name);
        $url = strtolower(str_replace(' ', '-', trim($url)));

        $request->merge([
            'site_url' => $url,
        ]);

        if ($Isbreaking != 1) {
            // Full validation if not breaking
            $rules = [
                'name' => 'required|string',
                'sort_desc' => 'required|string',
                'category' => 'required|numeric',
                'author' => 'required|numeric',
                'eng_name' => 'required|string',
                'site_url' => ['required', Rule::unique('blogs', 'site_url')->ignore($id)],
                'published_at' => 'nullable|date',
            ];

            $messages = [
                'name.required' => 'Please enter the blog title.',
                'sort_desc.required' => 'Please enter a short description.',
                'category.required' => 'Please select a category.',
                'category.numeric' => 'Invalid category format.',
                'author.required' => 'Please select an author.',
                'author.numeric' => 'Invalid author format.',
                'eng_name.required' => 'Please enter the English name.',
                'site_url.unique' => 'A blog with this English name already exists.',
                'published_at.date' => 'The publish date must be a valid date.',
            ];

            if ($IsNotification) {
                $rules['notification_title'] = 'required|string';
                $messages['notification_title.required'] = 'Please enter a notification title for push notification.';
            }

            if ($request->has('sequence')) {
                $rules['sequence_order'] = 'required|numeric|min:1';
                $messages['sequence_order.required'] = 'Please select a sequence order.';
            } else {
                $rules['sequence_order'] = 'nullable';
            }
        } else {
            // Minimal validation for breaking
            $rules = [
                'name' => 'required|string',
            ];
             $messages = [
                'name.required' => 'Please enter the blog title.',
            ];
        }

        $request->validate($rules, $messages);

        $status = Blog::determineStatusFromSchedule($request);
        if ($isUnpublish || $isUnpublishfooter) {
            $status = 0;
        }


          


        $publishedAt =  $request->scheduletime;
        $blogs = Blog::where('id', $id)->firstOrFail();
        $oldSequence = $blogs->sequence_id;

        // Handle sequence removal
        if ($Isbreaking != 1 && !$request->has('sequence') && $oldSequence) {
            // Shift up all sequences greater than the one being removed
            DB::table('blogs')
                ->where('id', '!=', $id)
                ->whereNotNull('sequence_id')
                ->where('sequence_id', '>', $oldSequence)
                ->decrement('sequence_id');

            $oldSequence = 0; // Clear sequence
        }

        $sequence = 0;

        // Handle sequence logic only if not breaking
        if ($Isbreaking != 1 && $request->has('sequence') && $request->filled('sequence_order')) {
            $newSequence = (int) $request->sequence_order;

            if ($status === Blog::STATUS_PUBLISHED) {
                if ($newSequence !== $oldSequence) {
                    if ($oldSequence && $newSequence > $oldSequence) {
                        // Moved down (e.g., from 7 to 9): shift up intermediate ones
                        DB::table('blogs')
                            ->where('id', '!=', $id)
                            ->whereNotNull('sequence_id')
                            ->whereBetween('sequence_id', [$oldSequence + 1, $newSequence])
                            ->decrement('sequence_id');
                    } elseif ($oldSequence && $newSequence < $oldSequence) {
                        // Moved up (e.g., from 9 to 7): shift down
                        DB::table('blogs')
                            ->where('id', '!=', $id)
                            ->whereNotNull('sequence_id')
                            ->whereBetween('sequence_id', [$newSequence, $oldSequence - 1])
                            ->increment('sequence_id');
                    } else {
                        // If no old sequence, shift others >= newSequence
                        DB::table('blogs')
                            ->where('id', '!=', $id)
                            ->whereNotNull('sequence_id')
                            ->where('sequence_id', '>=', $newSequence)
                            ->increment('sequence_id');
                    }
                }

                $sequence = $newSequence;
            } else {
                $exists = DB::table('blogs')
                    ->where('id', '!=', $id)
                    ->where('sequence_id', $newSequence)
                    ->where('status', Blog::STATUS_SCHEDULED)
                    ->exists();

                if ($exists) {
                    return back()
                        ->withInput()
                        ->withErrors(['sequence_order' => 'Another scheduled blog already has this sequence order. Please choose a different one.']);
                }

                $sequence = $newSequence;
            }
        }




        $ima = $request->images;
        $cat = $request->category;
        $state = $request->state;
        $district = $request->district;
        $mult_cat = isset($request->mult_cat) ? (count($request->mult_cat) > 0 ? implode(',', $request->mult_cat) : '') : '';
        $home_page_status = $request->filled('home_page_status') ? 1 : 0;
        $header_sec = $request->filled('header_sec') ? 1 : 0;

        $isLive = (int) $request->input('isLive');
      


        $data = [
            'name' => $request->name,
            'short_title' => $request->short_title ?? '',
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            'author' => $request->author,
            'link' => $request->link,
            'home_page_status' => $home_page_status,
            'header_sec' => $header_sec,
            'status' => $status,
            'credits' => $request->credits,
            'tags' => $request->tags,
            'sort_description' => $request->sort_desc,
            'keyword' => $request->keyword,
            'state_ids' => $state,
            'district_ids' => $district,
            'thumb_images' => $request->thumb_images,
            'image_ids' => $ima,
            'categories_ids' => $cat,
            'mult_cat' => $mult_cat,
            'description' => $request->description,
            'breaking_status' => $Isbreaking,
            'ispodcast_homepage' => $Ispodcast,
            'sequence_id' => $status === Blog::STATUS_SCHEDULED ? $oldSequence : $sequence,
            'scheduled_sequence_id' => $status === Blog::STATUS_SCHEDULED ? $sequence : null,
            'user_id' => $request->loggedinuserid,
            'AppHitCount' => $request->app_hit_count ?? 0,
            'WebHitCount' => $request->web_hit_count ?? 0,
            'sub_category_id' => $request->sub_cat,
            'isLive' => $isLive,
            'published_at' => $publishedAt,
            'isNotification'=>$IsNotification,
        ];

        Blog::where('id', $id)->update($data);

        if ($IsNotification == 1) {
           // $this->sendNotification($request, $id);
           app(NotificationService::class)->sendNotification($request, $id);
        }
        app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'posts/' . $from . '?status=' . $filteredstatus);

        //return redirect('posts/' . $from . '?status=' . $filteredstatus);
    }

    public function del($id, Request $request)
    {
        if (isset($_GET['from'])) {
            $from = $_GET['from'];
        }
        // Instead of deleting the blog, archive it
        Blog::where('id', $id)->update(['status' => Blog::STATUS_ARCHIVED]);
        app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'posts/' . $from . '?t=' . time());
    }
    public function deleteBlog($id, Request $request)
    {
        $role = call_user_func(config('global.getuser_role'));
       
        if ($role->role_name === 'Super Admin') {
            blog::where('id', $id)->delete();
            app(\App\Services\ExportHome::class)->run();
            return redirect(config('global.base_url').'/posts/archive?t=' . time());
        }else {
            return redirect()->back()
                ->with('error', 'Only super admin can delete this article.');
        }
    }
    public function deleteLiveUpdates($id, Request $request)
    {
        if (isset($_GET['from'])) {
            $from = $_GET['from'];
        }
        $role = call_user_func(config('global.getuser_role'));

        $liveBlog = LiveBlog::where('id', $id)->first();

        if ($liveBlog) {
            $blogId = $liveBlog->blog_id;
        }

        LiveBlog::where('id', $id)->delete();
        app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'posts/' . $from . '/' . $blogId . '?t=' . time());
    }
    function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return preg_replace('/-+/', ' ', $string); // Replaces multiple hyphens with single one.
    }
    public function breaking()
    {
        $blogs = Blog::limit(30)->orderBy('id', 'DESC')->get();
        return view('admin/breaking', ['blogs' => $blogs]);
    }
    public function changeStatus(Request $request)
    {
        if ($request->status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        $data = ['breaking_status' =>  $status];
        Blog::where('id', $request->id)->update($data);
        app(\App\Services\ExportHome::class)->run();
        return "status";
    }
  
    public function Subscribetotopic($accessToken, $payload)
    {
        $url = 'https://iid.googleapis.com/iid/v1:batchAdd';  // Replace with your Firebase project ID

        // Send the request to Firebase Cloud Messaging API
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

        // Execute the request
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    public function breakingList(Request $request)
    {

        $query = Blog::query()
            ->where('breaking_status', 1)
            ->where('status', 1);

        if ($request->filled('date')) {
            $query->whereDate('created_at', Carbon::parse($request->date));
        } else {
            $query->whereDate('created_at', Carbon::today());
        }
        $query->orderBy('id', 'DESC');

        if ($request->filled('category')) {
            $query->where('categories_ids', $request->category);
        }

        if ($request->filled('author')) {
            $query->where('author', $request->author);
        }

        $blogs = $query->paginate(100)->appends($request->query());

        $role = call_user_func(config('global.getuser_role'));

        return view('admin/breakingAricleList', [
            'blogs' => $blogs,
            'category' => $request->category,
            'title' => $request->title,
            'author' => $request->author,
            'status' => $request->status,
            'perPage' => 100
        ]);


        //  return view('admin/breakingAricleList', ['blogs' => $blogs]);
    }
    public function addBreakingArticle()
    {
        $file = File::orderBy('id', 'DESC')->paginate(12);
        $categories = Category::where('home_page_status', 1)->get();
        $state = State::where('home_page_status', 1)->get();
        $district = District::where('status', 1)->get();

        $role = call_user_func(config('global.getuser_role'));

        return view('admin/addBreakingArticle')->with('data', ['categories' => $categories, 'file' => $file, 'states' => $state, 'district' => $district]);
    }
    public function breakingArticleAdd(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
        ]);
        $status = isset($request->draft) ? 0 : 1;
        $IsNotification = $request->filled('send_Notification') ? 1 : 0;
        $blog =Blog::create([
            'name' => $request->name,
            'eng_name' => $request->name,
            'author' => 48,
            'home_page_status' => 0,
            'status' => 1,
            'header_sec' => 0,
            'breaking_status' => 1,
            'isNotification'=>$IsNotification,

        ]);
        if ($IsNotification) {
            //$this->sendNotification($request, $blog->id,1);
              app(NotificationService::class)->sendNotification($request, $blog->id,1);
        }
        app(\App\Services\ExportHome::class)->run();

        return redirect(config('global.base_url').'posts/breakingList');
    }

    public function updateBreakingStatus(Request $request)
    {
        $request->validate([
            'blog_id' => 'required|integer|exists:blogs,id',
            'breaking_status' => 'required|boolean',
        ]);

        $blog = Blog::find($request->blog_id);

        if ($blog) {
            $blog->breaking_status = $request->breaking_status;
            $blog->status = 0; // <-- Here we are also updating 'status' to 0
            $blog->save();
            app(\App\Services\ExportHome::class)->run();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }


    public function allLiveBlogs(Request $request)
    {
        $blogs = Blog::where('isLive', '1')->where('status', Blog::STATUS_PUBLISHED);


        if ($request->filled('date')) {
            $blogs->where(function ($query) use ($request) {
                $query->whereDate('created_at', Carbon::parse($request->date))
                    ->orWhereDate('updated_at', Carbon::parse($request->date));
            });
        } else {
            //$blogs->whereDate('created_at', Carbon::today());
            $blogs->where(function ($query) {
                $query->whereDate('created_at', Carbon::today())
                    ->orWhereDate('updated_at', Carbon::today());
            });
        }
        $blogs->orderBy('id', 'DESC');

        if (isset($request->title)) {
            $blogs->Where('name', 'like', '%' . $request->title . '%');
        }
        if (isset($request->category)) {
            $blogs->where('categories_ids', $request->category);
        }
        if (isset($request->status)) {
            // echo "Coming here=" . $request->status;
            if ($request->status == 0) {
                $blogs->where('breaking_status', 1);
            } else if ($request->status == 1) {
                $blogs->where('sequence_id', ">", 0);
            }
            // else if($request->status==2){
            //     $blogs->where('ispodcast_homepage',1);
            // }
            else {
                $blogs->where('status', $request->status);
            }
        }
        if (isset($request->author)) {
            $blogs->where('author', $request->author);
        }

        // $blogs = $blogs->paginate(30);

        $perPage = $request->input('perPage', 30);
        $blogs = $blogs->paginate($perPage);

        if (isset($request->category)) {
            $category = $request->category;
            $title = $request->title;
            $blogs->setPath(asset('/posts') . '?category=' . $request->category . '&title=' . $title . '&status=' . $request->status . '&author=' . $request->author);
        } else {
            $title = '';
            $category = 0;
            $blogs->setPath(asset('/posts'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }
        // Set the pagination path with query parameters
        $blogs->setPath(asset('/posts/live') . '?' . http_build_query($queryParams));

        return view('admin/articleLiveList')->with('data', ['blogs' => $blogs, 'category' => $request->category, 'title' => $request->title, 'author' => $request->author, 'status' => $request->status, 'perPage' => $perPage]);
    }


    public function liveUpdates($id, Request $request)
    {
        $liveBlogs = LiveBlog::with(['blog', 'category', 'image'])->where('blog_id', $id) // Eager loading
            ->orderBy('created_at', 'DESC');

        // Filter by title
        if ($request->filled('title')) {
            $liveBlogs->whereHas('blog', function ($query) use ($request) {
                $query->where('update_title', 'like', '%' . $request->title . '%');
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $liveBlogs->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status == 0) {
                $liveBlogs->where('breaking_status', 1);
            } else {
                $liveBlogs->where('status', $request->status);
            }
        }

        // Filter by author
        if ($request->filled('author')) {
            $liveBlogs->where('author', $request->author);
        }

        $perPage = $request->input('perPage', 30);
        $liveBlogs = $liveBlogs->paginate($perPage);

        // Build query string for pagination
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }

        $liveBlogs->setPath(asset('/posts') . '?' . http_build_query($queryParams));

        return view('admin.articleLiveUpdateList')->with('data', [
            'liveBlogs' => $liveBlogs,
            'category' => $request->category,
            'title' => $request->title,
            'author' => $request->author,
            'status' => $request->status,
            'perPage' => $perPage,
        ]);
    }
    public function addLiveUpdates()
    {
        $file = File::orderBy('id', 'DESC')->paginate(12);
        // $categories = Category::get()->all();

        // Add this line to fetch the 10 live articles for the dropdown
        $today = Carbon::today();
        $liveBlogOptions = Blog::with('category')
            ->where('status', '1')
            ->where('isLive', '1')
            ->where(function ($query) use ($today) {
                $query->whereDate('created_at', $today)
                    ->orWhereDate('updated_at', $today);
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        // return view('admin/addLiveUpdates')->with('data', ['file' => $file, 'liveBlogOptions' => $liveBlogOptions]);

        // Map: category ID => category name (for old('category') handling)
        $categoryNameMap = [];
        foreach ($liveBlogOptions as $blog) {
            if ($blog->category) {
                $categoryNameMap[$blog->category->id] = $blog->category->name;
            }
        }

        return view('admin/addLiveUpdates')->with([
            'data' => [
                'file' => $file,
                'liveBlogOptions' => $liveBlogOptions,
            ],
            'categoryNameMap' => $categoryNameMap,
        ]);
    }
    public function liveUpdatesAdd(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            // 'category' => 'required|numeric',
            'author' => 'required|numeric',
            'live_blog_name' => 'required|numeric',
        ]);

        $status = isset($request->draft) ? 0 : 1;
        $Isbreaking = $request->has('breaking_status') ? 1 : 0;

        LiveBlog::create([
            'blog_id' => $request->live_blog_name,
            'author' => $request->author,
            'breaking_status' => $Isbreaking,
            'update_title' => $request->name,
            'update_content' => $request->description,
            'image_id' => $request->images,
            'video_url' => $request->link,
            'category_id' => $request->category,
            'status' => $status,
        ]);
        app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'posts/livelists/' . $request->live_blog_name);
    }
    public function editLiveUpdates($id)
    {
        $liveBlogs = LiveBlog::where('id', $id)->first();
        $file = File::orderBy('id', 'DESC')->paginate(12);
        // $categories = Category::get()->all();

        // Add this line to fetch the 10 live articles for the dropdown
        $today = Carbon::today();
        $liveBlogOptions = Blog::with('category')
            ->where('status', '1')
            ->where('isLive', '1')
            ->where(function ($query) use ($today) {
                $query->whereDate('created_at', $today)
                    ->orWhereDate('updated_at', $today);
            })
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        return view('admin/editLiveUpdates')->with('data', ['file' => $file, 'liveBlogs' => $liveBlogs, 'liveBlogOptions' => $liveBlogOptions]);
    }
    public function liveUpdatesEdit($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            // 'category' => 'required|numeric',
            'author' => 'required|numeric',
            'live_blog_name' => 'required|numeric',
        ]);

        $status = isset($request->draft) ? 0 : 1;
        $Isbreaking = $request->has('breaking_status') ? 1 : 0;
        $images = NULL;
        if ($request->images == 0) {
            $images = NULL;
        } else {
            $images = $request->images;
        }

        LiveBlog::where('id', $id)->update([
            'blog_id' => $request->live_blog_name,
            'author' => $request->author,
            'breaking_status' => $Isbreaking,
            'update_title' => $request->name,
            'update_content' => $request->description,
            'image_id' => $images,
            'video_url' => $request->link,
            'category_id' => $request->category,
            'status' => $status,
        ]);
        //echo $request->live_blog_name;
        //echo $request->images;
        app(\App\Services\ExportHome::class)->run();
        return redirect(config('global.base_url').'posts/livelists/' . $request->live_blog_name);
    }
    public function loadThumbImages(Request $request)
    {
        $query = File::query();

        if ($request->has('search')) {
            $query->where('file_name', 'like', '%' . $request->search . '%');
        }

        $files = $query->whereIn('file_type', ['image/jpeg', 'image/png'])->orderBy('id', 'desc')->paginate(8);

        if ($request->ajax()) {
            return view('admin.media.thumb-list', compact('files'))->render();
        }

        return back();
    }

}
