<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use storage
use Illuminate\Support\Facades\Storage;
//use response
use Illuminate\Support\Facades\Response;

class publicController extends Controller
{
    public function getImage($url)
    {
        $exists = Storage::disk('local')->exists('image/item/'.$url);
        if($exists) {

           //get content of image
           $content = Storage::get('image/item/'.$url);

           //get mime type of image
           $mime = Storage::mimeType('image/item/'.$url);

           //if mime type is not image then abort
              if(!str_contains($mime, 'image')) {

                  abort(404);
              }
           //prepare response with image content and response code
           $response = Response::make($content, 200);
           //set header
           $response->header("Content-Type", $mime);
           // return response
           return $response;
        } else {
           abort(404);
        }
    }
}
