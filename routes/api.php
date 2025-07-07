<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CiteController;
use App\Http\Controllers\PersonController;
use Illuminate\Http\Request;

Route::post('/login',[AuthController::class,'login']);
Route::post('/register',[AuthController::class,'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $r)=> $r->user());
    Route::post('/logout', [AuthController::class,'logout']);
    Route::get('clientes', [PersonController::class, 'indexApp']);
    Route::post('citas', [CiteController::class, 'storeApp']);
    Route::get('citas', [CiteController::class,'indexApp']);
    Route::patch('citas/{cita}', [CiteController::class,'updateApp']);
    Route::delete('citas/{cita}', [CiteController::class,'destroyApp']);
});
