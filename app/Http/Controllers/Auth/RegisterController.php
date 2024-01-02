<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;


use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Services\ImageUploadService;

use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


// 

// use Hash;

class RegisterController extends Controller
{

    protected $imageUploadService;

    

    //
    // Function for Register
    public function register(RegisterRequest $request)
    {
        // For validate data of registration
        $newuser = $request->validated();

        $newuser['password'] = Hash::make($newuser['password']);
        $newuser['role'] = 'user';
        $newuser['status'] = 'active';



        $user = User::create($newuser);

        // Appel du service pour télécharger l'image
        // if ($request->file('image')) {
        //     $this->imageUploadService->uploadImage($request);
        // }
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

        $success['token'] = $user->createToken('user', ['app:all'])->plainTextToken;
        $success['name'] = $user->first_name;
        $success['success'] = true;
        // send notification for verify email
        $user->notify(new EmailVerificationNotification());

        return response()->json($success, 200);
    }
}
