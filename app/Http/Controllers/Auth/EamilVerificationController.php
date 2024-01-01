<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EamilVerificationRequest;
use App\Notifications\EmailVerificationNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use App\Models\User;

class EamilVerificationController extends Controller
{
    private $otp;

    public function __construct()
    {
        $this->otp = new Otp;
    }
    //

    public function sendEmailVerification(Request $request) {
        // je dois cree un nouveau notification request à la place de EmailVerificationNotification()
        $request->user()->notify(new EmailVerificationNotification());//user actuellement connecté
        $success['success'] = true;
        return response()->json($success, 200);
    }

    public function email_verification(EamilVerificationRequest $request)
    {
        $otp_ = $this->otp->validate($request->email, $request->otp);
        if (!$otp_->status) { //if return false
            return response()->json(['error' => "Invalid OTP", 'statu' => $otp_->status], 401);
        }
        $user = User::where('email', $request->email)->first();
        // Vérification si l'utilisateur existe
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->update(["email_verified_at" => now()]);
        $success['success'] = true;
        return response()->json($success, 200);
    }
}
