<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Auth;
//use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    public function index(Request $request) 
    {
        // Join users with roles manually
        $users = User::select('users.*', 'roles.role_name')
            ->leftJoin('roles', 'users.role', '=', 'roles.id') // Joining users with roles
            ->where('users.id', '!=', 6);

        if (isset($request->title)) {
            $users->where(function ($query) use ($request) { // Show both hindi name and english url name in search title 
                $query->where('name', 'like', '%' . $request->title . '%')
                    ->orWhere('url_name', 'like', '%' . $request->title . '%');
            });
        }
        $perPage = $request->input('perPage', 30);
        $users = $users->paginate($perPage);

        if (isset($request->title)) {
            $title = $request->title;
            $users->setPath(asset('/alluserslist') . '?title=' . $title);
        } else {
            $title = '';
            $users->setPath(asset('/alluserslist'));
        }

        // Build query parameters for pagination links
        $queryParams = $request->except('page');
        if ($perPage != 30) {
            $queryParams['perPage'] = $perPage;
        }

        // Set the pagination path with query parameters
        $users->setPath(asset('/alluserslist') . '?' . http_build_query($queryParams));

        return view('admin/allUsersList')->with('data', ['users' => $users, 'title' => $title, 'perPage' => $perPage]);
    }
    public function add(Request $request)
    {
        // $role = Role::whereNot('id', 3)->get()->all();
        $role = Role::all();
        return view('admin/addUser', ['roles'=>$role]);
    }
    public function save(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => 'required|string|exists:roles,id',
        ],[
            'role.exists' => 'The user type is required.',
        ]);
        
        User::create([
            'role' => $request->role,
            'name' =>  $request->name,
            'url_name' => $request->url_name,
            'email' => $request->email,
            'description' => $request->description,
            'password' => Hash::make($request->password),
        ]);
        return redirect('alluserslist');
    }
    public function edit($id)
    {
        $user = User::where('id', $id)->get()->first();
        // $role = Role::whereNot('id', 3)->get()->all();
        $role = Role::all();
        $file = File::where('id', $user->image)->first();
        return view('admin/editUser', ['roles'=>$role,'user'=>$user,'file'=>$file]);
    }
    public function editSave($id, Request $request)
    {
        $user = User::where('id', $id)->first();
        $file = File::where('id', $user->image)->first();
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url_name' => ['required', 'string', 'max:255'],
            'role' => 'required|string|exists:roles,id',
        ],[
            'role.exists' => 'The user type is required.',
        ]);
        $image = isset($file->file_name) ? $file->file_name : '';
        if(isset($request->image)) {
            $destinationPath = public_path('file');
            $image = $request->image->getClientOriginalName();
            $image = str_replace(' ', '_',$image);
            $image = pathinfo($image, PATHINFO_FILENAME).time() . '.'. $request->image->extension();
            $uploaded_file = File::create(
                    [
                        "user_id" => '1',
                        "file_name" => $image,
                        "file_type" => $request->image->getClientMimeType(),
                        "file_size" => $request->image->getSize(),
                        "full_path" => public_path('file'),
                    ]   
            );
            $request->image->move($destinationPath,$image);
            $image = $uploaded_file->id;
        }

        //echo "role=".$request->role;
        User::where('id', $id)->update([
            'role' => $request->role,
            'name' =>  $request->name,
            'image' => $image,
            'url_name' => $request->url_name,
            'description' => $request->description
        ]);
        return redirect('alluserslist');   
    }
    public function del($id, Request $request) 
    {
        ?>
        <script>
            if (confirm('Are You Sure You want Delete User')) {
                window.location.href =  '<?php echo asset('/alluserslist/del').'/'.$id; ?>'
            } else {
                window.location.href =  '<?php echo asset('/alluserslist'); ?>'
            }
        </script>
        <?php
    }
    public function deleteUser($id, Request $request)
    {
        User::where('id', $id)->delete();
        return redirect('/alluserslist');
    }
    // public function changePassword($id)
    // {
    //     $user = User::where('id', $id)->get()->first();
    //     return view('admin/allUsersChangePassword')->with('user', $user);
    // }
    // public function savePassword($id, Request $request)
    // {
    //     $user = User::where('id', $id)->get()->first();
    //     $request->validate([
    //         'password' => 'required',
    //         'new_password' => ['required', 'string', 'min:8', 'confirmed'],
    //         'new_password_confirmation' => 'required',
    //     ]);        
    //     if (!Hash::check($request->password, $user->password)) {
    //         return back()->withErrors(['password' => 'The old password is incorrect.']);
    //     }
    
    //     $user->where('id', $id)->update([
    //         'password' => Hash::make($request->new_password),
    //     ]);
    
    //     return redirect('dashboard')->with('success', 'Password updated successfully.');
    // }
    public function resetPassword($id)
    {
        $user = User::where('id', $id)->get()->first();
        return view('admin/allUsersResetPassword')->with('user',  $user);
    }
    public function saveResetPassword($id, Request $request)
    {
        $user = User::where('id', $id)->get()->first();
        $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => 'required',
        ]);        
        // if (!Hash::check($request->password, $user->password)) {
        //     return back()->withErrors(['password' => 'The old password is incorrect.']);
        // }
    
        $user->where('id', $id)->update([
            'password' => Hash::make($request->new_password),
        ]);
    
        return redirect('dashboard')->with('success', 'Password updated successfully.');
    }
    public function updateUserStatus(Request $request)
    {
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid user ID']);
        }

        $user->status = $request->active_status ? 1 : 0;
        $user->save();

        return response()->json(['success' => true]);
    }
}
