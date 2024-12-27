<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

\Tinify\setKey(config('app.tiny_key'));

class ImageUtil
{
  const PHOTO_MAX_FILESIZE = 5 * 1024;
  const PHOTO_MIN_DIM_HEIGHT = 70;
  const PHOTO_MIN_DIM_WIDTH = 70;

  const DEFAULT_PHOTO = "/images/users/default.jpg";

  /**
   * @return string
   */
  static public function getNextFileName()
  {
    return dechex((int)Carbon::now()->timestamp) . ".jpg";
  }

  /**
   *  
   * @return string
   */
  static public function storeImageFileReturnUriPath($photo)
  {
    $source = \Tinify\fromFile($photo);
    $resized = $source->resize([
      "method" => "cover",
      "width" => ImageUtil::PHOTO_MIN_DIM_WIDTH,
      "height" => ImageUtil::PHOTO_MIN_DIM_HEIGHT,
    ]);

    $fileName = self::getNextFileName();
    $relPath = "/images/users/" . $fileName;
    if (Storage::put($relPath, $resized->toBuffer())) {
      return $relPath;
    }
    error_log("Image file could not be saved at path: " . $relPath);
    return self::DEFAULT_PHOTO;
  }
}
