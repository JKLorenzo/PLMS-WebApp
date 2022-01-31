<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function index()
    {
        return view('password');
    }


    public function ChangePass(Request $request)
    {

        $password = Auth::user()->password;
        $rules = [
            'password' => [
                'required',
                'string',
            ],
            'new-password' => [
                'required',
                'string',
                'min:8-20',
                'required_with:verify'
            ],
            'verify' => ['required', 'same:new-password', 'string']
        ];


        $valid = Validator::make($request->all(), $rules);

        if (!Hash::check($request['password'], $password)) {
            return back()->withErrors(['pass' => 'The Current Password is Wrong.'])->withInput();
        }
        if (Hash::check($request['new-password'], $password)) {
            return back()->withErrors(['newpass' => 'New Password Must not match to the Current Password.'])->withInput();
        }

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput();
        } else {
            $user = Auth::user();
            $disUser = User::where('id', $user->id)->first();

            $hash = Hash::make($request['new-password']);

            $disUser->password = $hash;

            $disUser->update();

            toast('Password successfully changed!', 'success');

            return redirect('/');
        }
    }
}
