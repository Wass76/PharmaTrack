<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class PhotoController extends Controller
{
    public function show($filename)
    {
        // Get the photo from the storage or any other location
        $path = storage_path('app\public/' . $filename);
        // dd($path);

        // Return the photo as a response
        $file = Storage::get($path);
        // dd($file);
        $type = Storage::mimeType($path);
        dd($type);
        // return response($file);
    //   return response()->file($file);
      return (new Response($file, 200));
        //  (new Response($file, 200))->header('Content-Type', $type);
    }
}
