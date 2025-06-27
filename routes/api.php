<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);
Route::middleware('auth:sanctum')->get('/user', fn(Request $r)=> $r->user());
Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);
