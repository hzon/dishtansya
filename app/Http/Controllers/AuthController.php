<?php

namespace App\Http\Controllers;

use App\Mail\EmailVerification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
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

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $accessKey = Str::random(40);

            $user = User::where('email', $request->email)->first();
            $user->access_token = $accessKey;
            $user->save();

            return response(['access_token' => $accessKey], 201);
        } else {
            return response(['message' => 'Invalid credentials'], 401);
        }
    }
}
