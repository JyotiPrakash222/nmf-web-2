<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vote;
use App\Models\VoteOption;

class VoteOptionController extends Controller
{
    public function showVoteOption($id, Request $request)
    {
        $query = VoteOption::where('vote_id', $id)->orderBy('id', 'DESC');

        if (isset($request->title)) {
            $query->where('name', 'like', '%' . $request->title . '%');
        }
        $perPage = $request->input('perPage', 20);

        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }
        $latestOption = $query->paginate($perPage);

        if (isset($request->title)) {
            $title = $request->title;
            $latestOption->setPath(asset('/vote-option').'?title='.$title);
        } else {
            $title = '';
            $latestOption->setPath(asset('/vote-option'));
        }

        // Set the pagination path with query parameters
        $latestOption->setPath(asset('/vote-option') . '?' . http_build_query($queryParams));

        return view('admin/voteOptionList')->with('data', [
            'latest_option' => $latestOption,
            'title' => $title,
            'perPage' => $perPage
        ]);
    }

    public function addVoteOption(Request $request)
    {
        $get_votes = Vote::all();
        return view('admin/addVoteOption')->with('data',['get_votes' => $get_votes]);
    }

    public function saveVoteOption(Request $request)
    {
        $request->validate([
            'vote_id' => 'required|exists:votes,id',
            'name' => 'required|string',
        ]);

        VoteOption::create([
            'name' => $request->name,
            'vote_id' => $request->vote_id,
            'vote_count' => $request->vote_count ?? 0,
        ]);

       return redirect('vote/vote-option/'. $request->vote_id);
    }
    public function editVoteOption(Request $request, $id)
    {
        $option_vote = VoteOption::where('id', $id)->first();
        $get_votes = Vote::all();
        return view('admin/editVoteOption')->with('data',['get_votes' => $get_votes, 'option_vote' => $option_vote]);
    }

    public function voteOptionEdit(Request $request, $id)
    {
        $request->validate([
            'vote_id' => 'required|exists:votes,id',
            'name' => 'required|string',
        ]);

        VoteOption::where('id', $id)->update([
            'name' => $request->name,
            'vote_id' => $request->vote_id,
            'vote_count' => $request->vote_count ?? 0,
        ]);

       return redirect('vote/vote-option/'. $request->vote_id);
    }
}
