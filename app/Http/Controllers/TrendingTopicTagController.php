<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Blog;
use App\Models\TrendingTag;
use Illuminate\Support\Facades\Log;

class TrendingTopicTagController extends Controller
{
   public function index(Request $request) 
    {
        $query = TrendingTag::whereNotNull('name')
            ->orderByDesc('status')         // ensures status = 1 comes first
            ->orderBy('sequence_id', 'asc'); // then sort by sequence_id

        if ($request->has('title') && trim($request->title) !== '') {
            $query->where('name', 'like', '%' . trim($request->title) . '%');
        }

        $tags = $query->limit(100)->get();

        return view('admin/trendingTopicTag')->with('data', [
            'tags' => $tags,
            'title' => $request->title ?? ''
        ]);
    }

    public function addTrendingTag(Request $request)
    {
        return view('admin/addTrendingTag');
    }
    public function saveTrendingTag(Request $request)
    {
       $request->validate([
            'name' => 'required|string|unique:trending_tags,name',
        ]);
        TrendingTag::create([
            'name' => $request->name,
            'status' => '1',
        ]);
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
        return redirect(config('global.base_url').'posts/trendingtopictag');
       // return redirect('/posts/trendingtopictag');
    }

    public function editTrendingTag($id)
    {
        $get_tag = TrendingTag::where('id', $id)->first();
        return view('admin/editTrendingTag')->with('get_tag', $get_tag);
    }
    public function trendingTagEdit($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:trending_tags,name',
        ]);
        TrendingTag::where('id', $id)->update([
            'name' => $request->name,
            'status' => '1',
        ]);
      //  return redirect('/posts/trendingtopictag');
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }
        return redirect(config('global.base_url').'posts/trendingtopictag');
    }
    public function updateStatus(Request $request)
    {
        $tagName = $request->input('tag');
        $isActive = $request->input('active_status');

        // Find the tag record by name
        $tag =TrendingTag::where('name', $tagName)->first();

        if ($isActive) {
            // If activating: insert or update the tag with status 1
            if ($tag) {
               TrendingTag::where('id', $tag->id)->update([
                    'status' => 1,
                    'updated_at' => now(),
                ]);
            } else {
                // Insert new tag
               TrendingTag::insert([
                    'name' => $tagName,
                    'status' => 1,
                    'sequence_id' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            // If deactivating: set status to 0 if tag exists
            if ($tag) {
               TrendingTag::where('id', $tag->id)->update([
                    'status' => 0,
                    'updated_at' => now(),
                ]);
            }
        }
        try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }


        return response()->json(['success' => true]);
    }

    public function updateTrendingTagSequence(Request $request)
    {   
        Log::info('Received request to update trending tag sequence', ['request' => $request->all()]);
        $order = $request->trendingTags;

        foreach ($order as $item) {
            TrendingTag::where('id', $item['trendingTag_id'])
                ->update(['sequence_id' => $item['position']]);
        }
       try {
            app(\App\Services\ExportHome::class)->run();
        } catch (\Throwable $e) {
            \Log::error('ExportHome failed', ['error' => $e->getMessage()]);
        }

        return response()->json(['success' => true]);
    }
}
