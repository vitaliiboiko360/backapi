<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

\Tinify\setKey(env("TINY_KEY"));

class PhotoUtil
{
  const PHOTO_MAX_FILESIZE = 5 * 1024;
  const PHOTO_MIN_DIM_HEIGHT = 70;
  const PHOTO_MIN_DIM_WIDTH = 70;

  const DEFAULT_PHOTO = "/images/users/default.jpg";

  static private int $counter;

  /**
   * @return string
   */
  static public function getNextFileName()
  {
    return hexdec((int)Carbon::today()->format("Ymd") + self::$counter++) . ".jpg";
  }

  /**
   *  
   * @return string
   */
  static public function storeImageFileReturnPath($photo)
  {
    $imageSource = \Tinify\fromBuffer($photo)->toBuffer();
    $resized = $imageSource->resize([
      "method" => "cover",
      "width" => PhotoUtil::PHOTO_MIN_DIM_WIDTH,
      "height" => PhotoUtil::PHOTO_MIN_DIM_HEIGHT,
    ]);

    $relativePath = "/images/users/" . self::getNextFileName();
    if (! Storage::disk("public")->put($relativePath, $resized)) {
      error_log("Image file could not be saved at path: " . $relativePath);
      return self::DEFAULT_PHOTO;
    }
    return $relativePath;
  }
}
