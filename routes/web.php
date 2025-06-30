<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
USE App\Http\Controllers\PersonController;
USE App\Http\Controllers\CiteController;
USE App\Http\Controllers\AttentionController;
USE App\Http\Controllers\ServiceController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth','verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // CRUD Peluquería
    Route::resource('clientes', PersonController::class);
    Route::resource('citas',    CiteController::class);
    Route::resource('atenciones', AttentionController::class)->parameters(['atenciones' => 'atencion']);
    Route::resource('servicios', ServiceController::class);
});

require __DIR__.'/auth.php';
