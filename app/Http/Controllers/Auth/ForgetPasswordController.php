<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\ResetPasswordVerificationNotification;
use Illuminate\Http\Request;

use App\Http\Requests\Auth\ForgetPasswordRequest;
use App\Models\User;

class ForgetPasswordController extends Controller
{
    //
    public function forgotPassword(ForgetPasswordRequest $request) {
        $input = $request->only('email');
        $user = User::where('email', $input)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->notify(new ResetPasswordVerificationNotification());
        $success['success'] = true;
        return response()->json($success, 200);
    }
}
