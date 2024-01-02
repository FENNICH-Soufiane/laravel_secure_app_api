<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Http\Controllers\ImageUploadController;
use App\Http\Requests\Auth\ProfileUpdateRequest;
use Illuminate\Http\Request;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
        if($request->file('image')) {
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $request->file('image')->getClientOriginalExtension();
            $img = $manager->read($request->file('image'));
            $img = $img->resize(370, 246);
            $img->toJpeg(80)->save(public_path('upload/category/' . $name_gen)); // Utilisation de public_path() pour le chemin du fichier
            $save_url = 'upload/category/' . $name_gen;

            // Enregistrement dans la base de données
            // User::insert([
            //     'image' => $save_url,
            // ]);
            $user->update(['image' => $save_url]);
        }
        $user = $user->refresh();

        $success['success'] = true;
        $success['user'] = $user;

        return response()->json($success, 200);

    }
}
