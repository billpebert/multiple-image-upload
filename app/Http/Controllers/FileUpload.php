<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class FileUpload extends Controller
{
    public function createForm()
    {
        return view('image-upload');
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
                'imageFile.*.max' => 'Ukuran terlalu besar!',
                'imageFile.*.image' => 'File bukan foto!',
                'imageFile.*.uploaded' => 'File gagal diunggah.',
            ]
        );

        if ($req->hasfile('imageFile')) {
            // foreach ($req->file('imageFile') as $file) {
            //     $name = $file->getClientOriginalName();
            //     $file->move(public_path() . '/uploads/', $name);
            //     $imgData[] = $name;
            // }

            // $fileModal = new Image();
            // $fileModal->name = json_encode($imgData);
            // $fileModal->image_path = json_encode($imgData);


            // $fileModal->save();

            $images = $req->file('imageFile');

            foreach ($images as $image) {
                // $name = $image->getClientOriginalName();
                $path = $image->store('assets/products', 'public');

                $store_photos = [
                    'name' => $path
                ];

                Image::create($store_photos);
            }

            return back()->with('success', 'File has successfully uploaded!');
        }
    }
}
