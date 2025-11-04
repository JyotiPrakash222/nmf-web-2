<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\File;
use Illuminate\Support\Facades\Hash;
use Auth;
//use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function changePassword()
    {
        $user = Auth::user();
        return view('admin/changePassword')->with('user', $user);
    }    
    // public function savePassword(Request $request)
    // {
    //     $user = Auth::user();
    //     $request->validate([
    //         'password' => ['required', 'string', 'min:8', 'confirmed'],
    //     ]);
    //     User::where('id', $user->id)->update([
    //         'password' => Hash::make($request->password),
    //     ]);
    //     return redirect('dashboard');
    // }
    public function savePassword(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'password' => 'required',
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => 'required',
        ]);        
        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'The old password is incorrect.']);
        }
    
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
    
        return redirect('dashboard')->with('success', 'Password updated successfully.');
    }
}
