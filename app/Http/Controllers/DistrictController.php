<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\District;
use App\Models\State;

class DistrictController extends Controller
{
    public function index()
    {
        $data = District::select('districts.id', 'districts.name', 'states.name as state_name', 'districts.status')->JoinState()->paginate(20);
        $data->setPath(asset('/district'));
        return view('admin/districtList')->with('districts',$data);
    }
    public function add()
    {
    return view('admin/addDistrict')->with('states', State::get()->all());
    }
    public function save(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'state_id' => 'required',
        ]);
        District::create([
            'name' => $request->name,
            'state_id' => $request->state_id,
        ]);
        return redirect('district');
    }
    public function edit($id)
    {
        return view('admin/editDistrict')->with('data', ['district' => District::where('id', $id)->first(), 'states'=>State::get()->all()]);
    }
    public function editSave($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'state_id' => 'required',
        ]);
        District::where('id', $id)->update([
            'name' => $request->name,
            'state_id' => $request->state_id,
        ]);
        return redirect('district');
    }
    public function del($id, Request $request) 
    {
        ?>
        <script>
            if (confirm('Are You Sure You want Delete District')) {
                window.location.href =  '<?php echo asset('/district/del').'/'.$id; ?>'
            } else {
                window.location.href =  '<?php echo asset('/district'); ?>'
            }
        </script>
        <?php
    }
    public function deleteDistrict($id, Request $request)
    {
        District::where('id', $id)->delete();
        return redirect('/district');
    }

    public function updateStatus(Request $request)
    {
        $district = District::find($request->district_id);

        if (!$district) {
            return response()->json(['success' => false, 'message' => 'Invalid district ID']);
        }

        $district->status = $request->active_status ? 1 : 0;
        $district->save();

        return response()->json(['success' => true]);
    }
}
