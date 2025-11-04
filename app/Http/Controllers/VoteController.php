<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;

class VoteController extends Controller
{
    public function show(Request $request)
    {
        $query = Vote::orderBy('id', 'DESC');

        if (isset($request->title)) {
            $query->where('title', 'like', '%' . $request->title . '%');
        }
        $perPage = $request->input('perPage', 20);

        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }
        $latestPoll = $query->paginate($perPage);

        if (isset($request->title)) {
            $title = $request->title;
            $latestPoll->setPath(asset('/vote').'?title='.$title);
        } else {
            $title = '';
            $latestPoll->setPath(asset('/vote'));
        }

        // Set the pagination path with query parameters
        $latestPoll->setPath(asset('/vote') . '?' . http_build_query($queryParams));

        return view('admin/voteList')->with('data', [
            'latest_poll' => $latestPoll,
            'title' => $title,
            'perPage' => $perPage
        ]);
    }
    public function addVote(Request $request)
    {
        return view('admin/addVote');
    }

    public function saveVote(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $ip = $request->ip();
        Vote::create([
            'title' => $request->title,
            //'option' => $request->option,
            //'user_ip' => $ip,
        ]);

       return redirect('/vote');
    }
    public function editVote(Request $request, $id)
    {
        $vote = Vote::where('id', $id)->first();
        return view('admin/editVote')->with('vote', $vote);
    }
    public function voteEdit(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string',
        ]);

        $ip = $request->ip();
        Vote::where('id', $id)->update([
            'title' => $request->title,
            //'option' => $request->option,
            //'user_ip' => $ip,
        ]);

       return redirect('/vote');
    }
}
