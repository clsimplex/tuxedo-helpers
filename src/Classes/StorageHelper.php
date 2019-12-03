<?php

namespace CLSimplex\Tuxedo\Helpers;

use Illuminate\Support\Facades\Storage;

/**
 * StorageHelper is an attempt to provide additional
 * helpers that can be moved outside of Monocle.
 *
 * The Storage Facade is part of the laravel framework.
 * Nothing in here is testable outside of integration.
 *
 * @since  0.1.0
 * @author Levon Zadravec-Powell levon@clsimplex.com
 */
class StorageHelper {

  /**
   * @since  0.1.0
   * @param  string $file_name
   * @return bool
   */
  public static function has_local_file(string $file_name) {
    return Storage::disk('local')->exists($file_name);
  }

  /**
   * @since  0.1.0
   * @param  string $file_name
   * @param  mixed  $contents
   * @return void
   */
  public static function put_into_local_file(string $file_name, $contents) {
    Storage::disk('local')->put($file_name, $contents);
  }

  /**
   * Silent failure pattern.
   *
   * @since  0.1.0
   * @param  string $file_name
   * @return void
   */
  public static function delete_local_file(string $file_name) {
    if (static::has_local_file($file_name)) {
      Storage::delete($file_name);
    }
  }

  /**
   * "Guarantees" file creation - so you'll get something,
   * whether it is an empty string, or existing content.
   * So in general, call this only if you plan on using the
   * file you just created.
   *
   * @since  0.1.0
   * @param  string $file_name
   * @return string
   */
  public static function get_local_file(string $file_name) {
    if (! static::has_local_file($file_name)) {
      Storage::disk('local')->put($file_name, '');
    }

    return Storage::disk('local')->get($file_name);
  }

  /**
   * @since  0.1.0
   * @param  string $file_name
   * @return string
   */
  public static function get_local_file_path(string $file_name) {
    return storage_path('app/' . $file_name);
  }

}
