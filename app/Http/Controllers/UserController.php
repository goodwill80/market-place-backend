<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;


class UserController extends Controller
{
    // REGISTER USER
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myAppToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    // LOGIN USER
    public function login (Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);
        // Check email
        $user = User::where('email', $fields['email'])->first();
        // Check User Exist, Password and Authenticate User
        if( !$user || 
            !Hash::check($fields['password'], $user->password) ||
            !auth()->attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Bad credentials'
            ], 401);
        }
        // Create Token and Cookie
        $token = $user->createToken('myAppToken')->plainTextToken;
        $cookie = cookie('jwt', $token, 60*2);

        $response = [
                // 'user' => $user,
                // 'token' => $token,
                'message' => 'Sucess'
                
            ];
        return response($response, 201)->withCookie($cookie);
    }

    // // GET USER
    // public function user () {
    //     return auth()->user();
    // }

    // LOGOUT USER
    public function logout(Request $request) {
        $cookie = Cookie::forget('jwt');
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
