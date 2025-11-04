<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
// use App\Models\Blog;
use App\Models\State;
//use Illuminate\Support\Facades\Validator;

class StateSequenceController extends Controller
{
    public function index(Request $request) 
    {
        $states = State::where('home_page_status', '1')
                ->whereNotNull('sequence_id')
                ->where('sequence_id', '!=', 0)
                ->orderBy('sequence_id', 'asc')
                ->get();

        //$states->setPath(asset('/statesequence'));
       
       return view('admin/stateControl')->with('states', $states);
    }
    public function updateStateOrder(Request $request)
    {
        foreach ($request->order as $item) {

            State::where('id', $item['id'])->update(['sequence_id' => $item['sequence_id']]);
        }

        return response()->json(['success' => true]);
    } 
    public function updateDefaultState(Request $request)
    {
        $defaultId = $request->input('default_state');

        // Unset all defaults
        State::query()->update(['is_default' => 0]);

        // If a new default is selected, update it
        if (!empty($defaultId)) {
            State::where('id', $defaultId)->update(['is_default' => 1]);
        }

        return back()->with('success', 'Default state updated successfully!');
    }
}
