<?php

namespace App\Http\Controllers;

use App\Models\Image as model_image;
use Illuminate\Foundation\Console\OptimizeCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\File;

class FileUpload extends Controller
{
    public function createForm()
    {
        $image = model_image::pluck('name');
        return view('image-upload', [
            'image' => $image
        ]);
    }


    public function fileUpload(Request $req)
    {
        $req->validate(
            [
                'imageFile' => 'required',
                'imageFile.*' => 'image|mimes:jpeg,jpg,png|max:2048'
            ],
            [
                'imageFile.required' => 'Mohon masukkan file gambar!',
                'imageFile.*.mimes' => 'Format file tidak sesuai.',
                'imageFile.*.max' => 'Ukuran maksimal 2MB!',
                'imageFile.*.image' => 'File bukan foto!',
                'imageFile.*.uploaded' => 'File gagal diunggah.',
            ]
        );


        if ($req->hasfile('imageFile')) {

            foreach ($req->file('imageFile') as $file) {

                $name = $file->getClientOriginalName();
                // $req_file = $req->file('imageFile');
                // $extension = $file->extension();
                $custom_name = date("dmy-His") . '-' . $name;

                // Store to laravel disk storage
                // $path = $file->storeAs('/', $custom_name, 'public');

                //INTERVENTION IMAGE
                $img = \Image::make($file);
                // resize the image to a width of 700 and constrain aspect ratio (auto height)
                $img->resize(
                    700,
                    null,
                    function ($constraint) {
                        $constraint->aspectRatio();
                    }
                );
                // Folder in public must be created MANUALLY before uploading
                $new_path = public_path('uploads/' . $custom_name);
                $img->save($new_path);

                $store_photos = [
                    'name' => $custom_name
                ];
                model_image::create($store_photos);
            }

            //Return must be outside of the foreach
            return back()->with('success', 'Gambar berhasil diunggah!');
        }
    }

    public function deleteAllImages()
    {
        // dd(app_path());
        $photos = model_image::pluck('name');

        // delete folder in STORAGE
        // Storage::deleteDirectory('public');

        //delete folder in PUBLIC
        foreach ($photos as $photo) {
            if (File::exists(public_path('uploads/' . $photo))) {
                File::delete(public_path('uploads/' . $photo));
            } else {
                model_image::truncate();
                return back()->with('success', 'Folder tidak terbaca!');
            }
        }

        // truncate table
        model_image::truncate();

        return back()->with('success', 'Semua gambar berhasil dihapus!');
    }
}
