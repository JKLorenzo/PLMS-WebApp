<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('password');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
                'min:8',
                'max:255',
                'required_with:verify'
            ],
            'verify' => ['required', 'same:new-password', 'string']
        ];

        $valid = Validator::make($request->all(), $rules);

        if (!Hash::check($request['password'], $password)) {
            return back()->withErrors(['pass' => 'Password not match'])->withInput();
        }

        if (Hash::check($request['new-password'], $password)) {
            return back()->withErrors(['newpass' => 'New password must not match to the current password.'])->withInput();
        }

        if ($valid->fails()) {
            return back()->withErrors($valid)->withInput();
        }

        $user = Auth::user();
        $disUser = User::where('email', $user->email)->first();

        $hash = Hash::make($request['new-password']);

        $disUser->password = $hash;
        $disUser->save();

        // toast('Password successfully changed!', 'success');

        Auth::logout();
        Session::flush();

        return redirect('/login');
    }
}
