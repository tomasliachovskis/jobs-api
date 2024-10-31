<?php

use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;

Route::post('/jobs', [JobController::class, 'store']);
Route::get('/jobs/{id}', [JobController::class, 'show']);
Route::delete('/jobs/{id}', [JobController::class, 'destroy']);
