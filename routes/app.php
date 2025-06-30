<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')
    ->group(base_path('routes/api.php'));
