<?php

namespace CLSimplex\Tuxedo\Helpers\Classes;

use Illuminate\Support\Facades\Cache;
use CLSimplex\Tuxedo\Helpers\Classes\FileHelper;

/**
 * New version needs to be more respectful of rate limits, and have a better fallback when
 * background queries fail.
 *
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  0.0.1
 */
class BackgroundHelper {

  /**
   * This is the public method by which Tuxedo gets a background file path.
   * Right now we cache the background for two days. Fine.
   *
   * @since  0.0.1
   * @codeConverageIgnore
   * @return string
   */
  public static function get_background() {
    $two_days_in_minutes = TimeHelper::end_of_day_minutes() + 1440;

    return Cache::remember('background_file_path', $two_days_in_minutes, function() {
      $image_filepaths = static::get_internal_backgrounds();

      return 'vendor/clsimplex/monocle/images/backgrounds/' . $image_filepaths[ array_rand($image_filepaths) ];
    });
  }

  /**
   * This will allow us to test some of the functionality.
   *
   * @since  0.0.1
   * @see    FileHelper::list_directory
   * @return array
   */
  public static function get_internal_backgrounds() {
    return FileHelper::list_directory(FileHelper::get_resource_path('/images/backgrounds/'));
  }
}
