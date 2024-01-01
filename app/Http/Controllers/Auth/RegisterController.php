<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;

use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// 

// use Hash;

class RegisterController extends Controller
{
    //
    // Function for Register
    public function register(RegisterRequest $request) {
        // For validate data of registration
        $newuser = $request->validated();

        $newuser['password'] = Hash::make($newuser['password']);
        $newuser['role'] = 'user';
        $newuser['status'] = 'active';

        $user = User::create($newuser);

        $success['token'] = $user->createToken('user', ['app:all'])->plainTextToken;
        $success['name'] = $user->first_name;
        $success['success'] = true;
        // send notification for verify email
        $user->notify(new EmailVerificationNotification());

        return response()->json($success, 200);
    }
}
