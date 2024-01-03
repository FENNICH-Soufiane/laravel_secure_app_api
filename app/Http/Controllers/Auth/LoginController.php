<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Notifications\LoginNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(LoginRequest $request) {
        
        $credentials = [
            "email"=> $request->email,
            "password"=> $request->password
        ];
        if(auth()->attempt($credentials)) {
            // $user = Auth::user();
            $user = auth()->user();

            // delete last token if exist
            $user->tokens()->delete();
            $success['token'] = $user->createToken(request()->userAgent())->plainTextToken;
            $success['name'] = $user->first_name;
            $success['success'] = true;
            // send notification of login
            $user->notify(new LoginNotification());
            return response()->json($success, 200);
        } else {
          return response()->json(['error'=> __('auth.Unauthorised')], 401);
        }

        
    }
}
