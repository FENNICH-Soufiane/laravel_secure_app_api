<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\Auth\ProfileUpdateRequest;

class ProfileController extends Controller
{
    //
    public function updateProfile(ProfileUpdateRequest $request)
    {

        // Récupère l'utilisateur actuellement authentifié depuis la requête
        $user = $request->user();
        // Utilise la méthode validated() pour obtenir les données validées
        $validatedData = $request->validated();
        // Ne pas inclure 'email' dans la mise à jour
        unset($validatedData['email']);
        $user->update($validatedData);

        $user = $user->refresh();

        $success['success'] = true;
        $success['user'] = $user;

        return response()->json($success, 200);

    }
}
