<?php

namespace App\Http\Controllers;

use App\Models\Image as model_image;
use Illuminate\Foundation\Console\OptimizeCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Storage;

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
                // $extension = $file->extension();
                $custom_name = date("dmy-His") . '-' . $name;

                $path = $file->storeAs('/', $custom_name, 'public');

                $store_photos = [
                    'name' => $path
                ];
                model_image::create($store_photos);
            }

            //Return must be outside of the foreach
            return back()->with('success', 'Gambar berhasil diunggah!');
        }
    }

    public function deleteAllImages()
    {
        // truncate table
        model_image::truncate();
        // delete folder
        Storage::deleteDirectory('public');

        return back()->with('success', 'Semua gambar berhasil dihapus!');
    }
}
