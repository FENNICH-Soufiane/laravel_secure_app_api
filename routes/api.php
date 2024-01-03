<?php

use App\Http\Controllers\Auth\EamilVerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;







/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::middleware('setapplang')->prefix('{locale}')->group(function () {

    Route::post('register', [RegisterController::class, 'register']);
    Route::post('login',[LoginController::class, 'login']);
    
    Route::post('password/forgot-password',[ForgetPasswordController::class, 'forgotPassword']);
    Route::post('password/reset',[ResetPasswordController::class, 'passwordReset']);

});



Route::middleware(['auth:sanctum', 'setapplang'])->prefix('{locale}')->group(function () {

    Route::get('/profile', function (Request $request) {
        return $request->user();
    });

    Route::post('email-verification',[EamilVerificationController::class, 'email_verification']);
    Route::get('email-verification',[EamilVerificationController::class, 'sendEmailVerification']);

    Route::put('update-profile',[ProfileController::class, 'updateProfile']);
});


