<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    use AuthenticatesUsers;

    protected $maxAttempts = 5;
    protected $decayMinutes = 5;

    /**
     * Registration API
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'unique:users',
        ]);

        if ($validator->fails()) {
            return response(['message' => 'Email already taken'], 400);
        }

        $user = new User;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        Mail::to($user)->queue(new EmailVerification());

        return response(['message' => 'User successfully registered'], 201);
    }

    /**
     * Authentication API
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($this->hasTooManyLoginAttempts($request)) {         // Lockout user after specified attempts
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if (Auth::attempt($credentials)) {
            $accessKey = Str::random(40);

            $user = User::where('email', $request->email)->first();
            $user->access_token = $accessKey;
            $user->save();

            $this->clearLoginAttempts($request);

            return response(['access_token' => $accessKey], 201);
        } else {
            $this->incrementLoginAttempts($request);

            return response(['message' => 'Invalid credentials'], 401);
        }
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
