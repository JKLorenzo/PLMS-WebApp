<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;



class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);


        if ($response = $this->authenticated($request, $this->guard()->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $fields = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $fields['email'])->first();
        if (!$user) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->withInput();
        }

        if (!Hash::check($fields['password'], $user->password)) {
            return back()->withErrors(['password' => 'The provided password is incorrect.'])->withInput();
        }

        //Request from API

        // $response = Http::post('https://plms-clz.herokuapp.com/api/auth/login', [
        //     'email' => $request->email,
        //     'password' => $request->password,
        // ]);

        // $message = $response->getBody()->getContents();
        // $message = json_decode($message);

        // if ($response->clientError() && strpos(json_encode($message), "password") == true) {
        //     return back()->withErrors(['password' => $message])->withInput();
        // } else if ($response->clientError() && strpos(json_encode($message), "credentials") == true) {
        //     return back()->withErrors(['email' => $message])->withInput();
        // }
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($response = $this->loggedOut($request)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect('/');
    }
}
