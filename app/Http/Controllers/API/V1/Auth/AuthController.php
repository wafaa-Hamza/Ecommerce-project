<?php

namespace App\Http\Controllers\API\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckOTPForgetPassowrdRequest;
use App\Http\Requests\Auth\CheckOTPRequest;
use App\Http\Requests\Auth\ForgetPassowrdRequest;
use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Mail\ForgetPasswordMail;
use App\Mail\RegisterMail;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    // public function register(RegisterUserRequest $request)
    // {
    //     $otp = rand(100000, 999999);

    //     $data = $request->validated();

    //     $data['otp'] = $otp;

    //     $user = User::create($data);


    //     if ($request->hasFile('image')) {
    //         $user->clearMediaCollection("main");
    //         $user->addMediaFromRequest('image')->toMediaCollection("main");
    //     }

    //     $user->assignRole('client');

    //     // Mail::to($user->email)->send(new RegisterMail($otp));

    //     // $expiresAt = new DateTime('+7 minutes');

    //     // $user->token = $user->createToken(env('SANCTUM_TOKEN'), ['*'], $expiresAt)->plainTextToken;

    //     return $this->respondCreated(UserResource::make($user), 'Registered successfully');

    // }
    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);
    }

//     public function login(Request $request)
//     {
//         // dd('ddd');
//         $this->validateLogin($request);

//         if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

//             $user = Auth::user();
//             $user->save();


//             return response()->json([
//                 'status' => true,
//                 'message' => 'Account Logged In Successfully',
//                 'token' =>  Str::random(60),

//             ], 200);
//         }


//         return response()->json([
//             'status' => false,
//             'message' => 'Invalid email, password, or role',
//         ], 401);

// }

    // public function showLoginForm()
    // {
    //     return view('auth.login');
    // }

    //   $token = $user->createToken(env("SANCTUM_TOKEN"))->plainTextToken;

    //   $user->token = $token;

    //   return $this->respondOk([
    //       "user" => UserResource::make($user)
    //   ] , 'Login successfully');




    // public function check_otp(CheckOTPRequest $request)
    // {
    //     $data = $request->validated();

    //     $user = $request->user;

    //     if ($data['otp'] != $user->otp) {
    //        return $this->respondError('Wrong OTP');
    //     }

    //     $user->update([
    //         'otp' => null,
    //         'email_verified_at' => now()
    //     ]);

    //     return $this->respondNoContent();
    // }

    public function forget_password(ForgetPassowrdRequest $request)
    {

        $data = $request->validated();

        if (isset($data['email'])){
            $user = User::where('email', $data['email'])->first();
        } else {
            $user = User::where('phone', $data['phone'])->first();
        }

        if (! $user) {
            return $this->respondError('User not found.');
        }

        $otp = rand(100000, 999999);

        Mail::to($user->email)->send(new ForgetPasswordMail($otp));

        $user->update([
           'otp' => $otp
        ]);

        return $this->respondNoContent();

    }

    public function check_forget_password_otp(CheckOTPForgetPassowrdRequest $request)
    {
        $data = $request->validated();

        if (isset($data['email'])){
            $user = User::where('email', $data['email'])->first();
        } else {
            $user = User::where('phone', $data['phone'])->first();
        }

        if (! $user) {
            return $this->respondError('User not found.');
        }

        if ($data['otp'] != $user->otp) {
           return $this->respondError('Wrong OTP');
        }

        $token = $user->createToken(env("SANCTUM_TOKEN") , ['*'] , new DateTime('+7 minutes'))->plainTextToken;

        return $this->respondOk([
            "token" => $token
        ] , 'Use this token to reset your password');

    }

    public function reset_password(ResetPasswordRequest $request)
    {
        $fields = $request->validated();

        $user = $request->user;
        $user->password = Hash::make($fields['password']);
        $user->update();

        $user->tokens()->delete();

        return $this->respondNoContent();
    }

    public function logout(Request $request){

        $request->user->currentAccessToken()->delete();
        return $this->respondNoContent();
    }

    public function logoutAllDevices(Request $request){

        $request->user->tokens()->delete();
        return $this->respondNoContent();
    }

}

