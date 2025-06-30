<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $r)
    {
        $data = $r->validate([
            'name' => 'required|string',
            'email'=> 'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $token = $user->createToken('mobile')->plainTextToken;
        return response()->json(compact('user','token'), 201);
    }

    public function login(Request $r)
    {
        $creds = $r->only('email','password');
        if (!Auth::attempt($creds)) {
            return response()->json(['message'=>'Credenciales inválidas'], 401);
        }
        $user  = Auth::user();
        $token = $user->createToken('mobile')->plainTextToken;
        return response()->json(compact('user','token'));
    }

    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();
        return response()->json(['message'=>'Logout exitoso']);
    }

    public function user(Request $r)
    {
        return response()->json($r->user());
    }
}
