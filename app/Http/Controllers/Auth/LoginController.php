<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    protected $redirectTo = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        return view('auth.login');
    }


    protected function validateLogin(Request $request)
    {
        return $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    }

    public function login(Request $request)
    {
        // dd('ddd');
      $data=  $this->validateLogin($request);

      if (Auth::attempt($data)) {
        $user = Auth::user();
        $user->assignRole('admin');
        $user->api_token = Str::random(60);
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Account Logged In Successfully',
            'token' =>  $user->api_token,
            'user' => $user,
        ], 200);
    }

    return response()->json([
        'status' => false,
        'message' => 'Invalid email, password, or role',
    ], 401);
}

  protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.home');
        }

        return redirect()->route('user.home');
    }
}

