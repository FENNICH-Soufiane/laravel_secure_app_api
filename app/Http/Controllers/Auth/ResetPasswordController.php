<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;

use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    //
    private $otp;

    public function __construct() {
        $this->otp = new Otp;
    }

    public function passwordReset(ResetPasswordRequest $request) {
        $otp_ = $this->otp->validate($request->email, $request->otp);
        if(!$otp_->status) { // equal $otp===false
            return response()->json(['error'=> $otp_], 401);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->update(['password' => Hash::make($request->password)]);
        $user->tokens()->delete();
        $success['success'] = true;
        return response()->json($success,200);
    }
}
