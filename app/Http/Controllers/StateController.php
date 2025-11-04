<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Ads;
use App\Models\Blog;
use Illuminate\Support\Facades\Log;

class StateController extends Controller
{
    public function index()
    {
        $states = State::paginate(20);
        $states->setPath(asset('/state'));
        return view('admin/stateList')->with('states', $states);
    }
    public function add()
    {
        return view('admin/addState');
    }
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'eng_name' => 'required|string'
        ]);
        $url = $this->clean($request->eng_name);
        $url = strtolower(str_replace(' ', '-', trim($url)));
        $home_page_status = 0;
        if ($request->home_page_status) {
            $home_page_status = 1;
        }
        State::create([
            'name' => $request->name,
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            'home_page_status' => $home_page_status,
            'sequence_id' => $request->sequence,
        ]);
        return redirect('state');
    }
    public function edit($id)
    {
        $state = State::where('id', $id)->first();
        return view('admin/editState')->with('state', $state);
    }
    public function editSave($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'eng_name' => 'required|string'
        ]);
        $home_page_status = 0;
        $url = $this->clean($request->eng_name);
        $url = strtolower(str_replace(' ', '-', trim($url)));
        if ($request->home_page_status) {
            $home_page_status = 1;
        }
        State::where('id', $id)->update([
            'name' => $request->name,
            'eng_name' => $request->eng_name,
            'site_url' => $url,
            'home_page_status' => $home_page_status,
            'sequence_id' => $request->sequence,
        ]);
        return redirect('state');
    }
    public function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        $string = trim($string);
        return preg_replace('/-+/', ' ', $string); // Replaces multiple hyphens with single one.
    }
    public function del($id, Request $request)
    {
        ?>
        <script>
            if (confirm('Are You Sure You want Delete State')) {
                window.location.href = '<?php echo asset('/state/del') . '/' . $id; ?>'
            } else {
                window.location.href = '<?php echo asset('/state'); ?>'
            }
        </script>
        <?php
    }
    public function deleteState($id, Request $request)
    {
        State::where('id', $id)->delete();
        return redirect('/state');
    }

    public function updateStatus(Request $request)
    {
        $state = State::find($request->state_id);

        if (!$state) {
            return response()->json(['success' => false, 'message' => 'Invalid state ID']);
        }

        $state->home_page_status = $request->active_status ? 1 : 0;
        $state->save();

        return response()->json(['success' => true]);
    }

    public function stateLoadMore(Request $request, $name)
    {
        try {
            // Log::info('stateLoadMore called', [
            //     'name' => $name,
            //     'offset' => $request->input('offset', 0)
            // ]);

            $offset = (int) $request->input('offset', 0);
            $limit = 10;

            // State lookup (same as state() function)
            $name = str_replace('_', ' ', $name);
            $state = State::where('site_url', $name)->firstOrFail();

            // Ads (same type you used in state())
            $sateAds = Ads::where('page_type', 'category')->get()->keyBy('location');

            // Top 5 blogs (to exclude)
            $topBlogIds = Blog::where('state_ids', $state->id)
                ->where('status', 1)
                ->orderBy('created_at', 'DESC')
                ->limit(5)
                ->pluck('id')
                ->toArray();

            // Remaining blogs with offset
            $blogs = Blog::where('state_ids', $state->id)
                ->where('status', 1)
                ->whereNotIn('id', $topBlogIds)
                ->with('images')
                ->orderBy('created_at', 'DESC')
                ->skip($offset)
                ->take($limit)
                ->get();

            // Render only the <li> elements as a partial
            // $html = view('partials.blogList', [
            //     'blogs' => $blogs,
            //     'sateAds' => $sateAds  // blade expects this variable
            // ])->render();

            // return response()->json([
            //     'blogs' => $html,
            //     'count' => $blogs->count()
            // ]);

            return response()->json([
                'blogs' => view('components.category.state-blog-list', [
                    'blogs' => $blogs,
                    'sateAds' => $sateAds ?? []
                ])->render(),
                'count' => $blogs->count()
            ]);

        } catch (\Exception $e) {
            Log::error('stateLoadMore error: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json(['blogs' => '', 'count' => 0], 500);
        }
    }

}
