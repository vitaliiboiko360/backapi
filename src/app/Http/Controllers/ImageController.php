<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\Response
 */
class ImageController extends Controller
{
  const BASE_PATH = "app/public/";
  public function getImage(Request $request)
  {
    $uriPath = $request->path();
    return response()->file(storage_path(self::BASE_PATH . $uriPath), ["Content-Type", "image/jpeg"]);
  }
}
