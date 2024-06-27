<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/verifyEmail/token={key}', [AuthController::class, 'decryptVerifyEmailToken'])->name('get token from url');
Route::get('/verified_email', [AuthController::class, 'verifyEmailToken']);
