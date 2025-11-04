<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Auth;
use App\Models\Rashifal;
//use Illuminate\Support\Facades\Validator;

class RashiphalController extends Controller
{
    public function index(Request $request) 
    {
        $perPage = $request->input('perPage', 20);
        $rashiphal = Rashifal::paginate($perPage);

        $rashiphal->setPath(asset('/rashiphal'));

        // /print_r($rashiphal);
        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 20) {
            $queryParams['perPage'] = $perPage;
        }

        // Set the pagination path with query parameters
        $rashiphal->setPath(asset('/rashiphal') . '?' . http_build_query($queryParams));
        echo "here";
       // return view('admin/rashiphalList2')->with(['rashiphal' => $rashiphal,'perPage' => $perPage ]);
       return view('admin/rashiphal')->with('rashiphal', $rashiphal);
    }
    public function edit($id)
    {
        $rashiphal = Rashifal::where('id', $id)->get()->first();
        return view('/admin/editRashi', ['rashiphal'=>$rashiphal]);
    }
    public function editSave($id, Request $request)
    {
        $user = Rashifal::where('id', $id)->first();
        $request->validate([
            'name' => ['required', 'string'],
            'description' => ['required','string']
        ]);
        $image = isset($file->file_name) ? $file->file_name : '';
        //echo "role=".$request->role;
        Rashifal::where('id', $id)->update([
            'name' =>  $request->name,
            'description' => $request->description
        ]);
        return redirect('rashiphal');   
    }
}
