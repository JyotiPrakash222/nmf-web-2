<?php

namespace App\Http\Controllers;

use App\Models\HomeSection;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\WebStories;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TopNewsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function index()
    {
        // Merge blogs and webstories by unified topnews_sequence
        $topNews = Blog::select('id', 'name', 'sequence_id as topnews_sequence', DB::raw("'blog' as type"))
            ->where('status', 1)
            ->where('sequence_id', '>', 0)

            ->unionAll(
                WebStories::select('id', 'name', 'topnews_sequence', DB::raw("'webstory' as type"))
                    ->where('status', 1)
                    ->where('show_in_topnews', 1)
                    ->where('topnews_sequence', '>', 0)
            )
            ->orderBy('topnews_sequence', 'asc')
            ->get();

        $homeSectionStatus = HomeSection::where('title', 'ShowTopNewsWithWebStory')->first();

        return view('admin.topNewsControl', compact('topNews', 'homeSectionStatus'));
    }

    public function updateTopNewsOrder(Request $request)
    {
        // foreach ($request->order as $item) {
        //     Blog::where('id', $item['id'])->update([
        //         'sequence_id' => $item['sequence_id'],
        //         'updated_at' => DB::raw('updated_at') // this keeps the original value
        //     ]);
        // }
        $items = $request->input('order', []);

        foreach ($items as $item) {
            if ($item['type'] === 'blog') {
                DB::table('blogs')
                    ->where('id', $item['id'])
                    ->update(['sequence_id' => $item['sequence_id']]);
            } elseif ($item['type'] === 'webstory') {
                DB::table('webstories')
                    ->where('id', $item['id'])
                    ->update(['topnews_sequence' => $item['sequence_id']]);
            }
        }
        app(\App\Services\ExportHome::class)->run();

        return response()->json(['success' => true]);

    }

    public function resetTopNewsOrder($type, $id)
    {

        // Blog::where('id', $id)
        // ->update([
        //     'sequence_id' => 0,
        //     'updated_at'  => DB::raw('updated_at') // keep the old value
        // ]);

        if ($type === 'blog') {
            Blog::where('id', $id)->update(['sequence_id' => 0, 'updated_at'  => DB::raw('updated_at')]);
        } elseif ($type === 'webstory') {
            WebStories::where('id', $id)->update(['topnews_sequence' => 0, 'updated_at'  => DB::raw('updated_at')]);
        }
        app(\App\Services\ExportHome::class)->run();

        return response()->json(['success' => true]);
    }

}
