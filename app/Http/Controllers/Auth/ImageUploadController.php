<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ImageUploadRequest;

use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageUploadController extends Controller
{
    //
    public function uploadImage(ImageUploadRequest $request) {
        if($request->file('image')) {
            // $takeimage = $request->file('image');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()).'.'.$request->file('image')->getClientOriginalExtension();
            $img = $manager->read($request->file('image'));
            $img = $img->resize(370, 246);
            $img->toJpeg(80)->save(base_path('public/upload/'.$name_gen));
            $save_url = 'upload/category/'.$name_gen;
            User::insert([
                'image' => $save_url
            ]);
        }
    }
}
