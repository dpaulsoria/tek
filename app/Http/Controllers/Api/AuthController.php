<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
  public function register(Request $req){
    $data = $req->validate([
      'name'=>'required',
      'email'=>'required|email|unique:users',
      'password'=>'required|min:8'
    ]);
    $user = User::create([
      'name'=>$data['name'],
      'email'=>$data['email'],
      'password'=>Hash::make($data['password']),
    ]);
    return $this->login($req);
  }

  public function login(Request $req){
    $req->validate([
      'email'=>'required|email',
      'password'=>'required'
    ]);
    $user = User::where('email',$req->email)->first();
    if (! $user || ! Hash::check($req->password,$user->password)) {
      throw ValidationException::withMessages([
        'email'=>['Credenciales inválidas'],
      ]);
    }
    $token = $user->createToken('main')->plainTextToken;
    return response()->json(['token'=>$token,'user'=>$user]);
  }

  public function logout(Request $req){
    $req->user()->tokens()->delete();
    return response()->json([],204);
  }
}
