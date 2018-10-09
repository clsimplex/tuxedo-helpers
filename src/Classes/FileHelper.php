<?php

namespace CLSimplex\Tuxedo\Helpers;

use Illuminate\Support\Facades\Log;

/**
 * This got copied over from Monocle - I'm getting rid of the
 * previous version comments for clarity.
 * Refer to older monocle commits for more information!
 *
 * I also introduce a couple of undocumented changes here -
 * like unifying the function names: list_directory -> get_directory
 *
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.4.0 moved from Monocle/Helpers/FileHelper
 */
class FileHelper {

  /**
   * Some controller action patterns use a "find the static view or find the database entry"
   * flow.
   * In those cases, we don't want to abort(404) if the static view doesn't exist.
   * This also means we don't want to throw any Exception - even if this method
   * really suggests that throwing an exception is the best fall through case.
   *
   * @TODO   rename? The public part is a little misleading and doesnt add to function name.
   * @since  1.4.0
   * @codeCoverageIgnore
   * @param  string $view_name
   * @param  string $local_view_path
   * @param  array  $custom_data
   * @return null|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
   */
  public static function get_view(string $view_name, string $local_view_path, array $custom_data = []) {
    $full_path = $local_view_path . '.' . $view_name;

    if (! view()->exists($full_path)) {
      return null;
    }

    return view($full_path, $custom_data);
  }

  /**
   * @since  1.4.0
   * @TODO   write tests.
   * @param  string $file_name
   * @return string
   */
  public static function get_resource_path(string $file_name = '') {
    $resource_base_path = dirname(__DIR__) . '/resources';

    if (empty($file_name)) {
      return $resource_base_path;
    }

    if (! starts_with($file_name, '/')) {
      $file_name = '/' . $file_name;
    }

    if (env('APP_ENV') === 'testing') {
      $resource_base_path = str_replace('tuxedo-monocle/resources', 'tuxedo-monocle/src/resources', $resource_base_path);
    }

    return $resource_base_path . $file_name;
  }

  /**
   * scandir() returns false if the directory is not found.
   * We will return an empty list in that case.
   * We use the absolute path as the key, and the filename as the
   * value.
   *
   * @since  1.4.0
   * @link   http://php.net/manual/en/function.scandir.php
   * @param  string $directory_path
   * @param  array  $exclusions things we do not want to list.
   * @return array
   */
  public static function get_directory(string $directory_path, array $exclusions = ['.', '..']) {
    try {
      $raw_file_list = scandir($directory_path);
    } catch (\Exception $e) {
      Log::error('list_directory() failed.', ['exception' => $e]);
      return [];
    }

    $result    = [];
    $file_list = array_diff($raw_file_list, $exclusions);

    foreach ($file_list as $file) {
      $real_path          = realpath($directory_path . '/' . $file);
      $result[$real_path] = $file;
    }

    return $result;
  }

  /**
   * @since  1.4.0
   * @param  string $directory_path
   * @param  array  $exclusions
   * @return array
   */
  public static function get_directory__files(string $directory_path, array $exclusions = []) {
    $list_directory = static::get_directory($directory_path, $exclusions);

    // Unusual formatting on my part here, I usually put the closure on it's own line but it's small.
    return array_filter($list_directory, function($file_path) { return ! is_dir($file_path); }, ARRAY_FILTER_USE_KEY);
  }

  /**
   * @since  1.4.0
   * @link   https://secure.php.net/manual/en/function.strripos.php
   * @link   https://laravel.com/docs/5.4/helpers#method-class-basename
   * @param  string $directory_path
   * @param  string $class_prefix
   * @return array
   */
  public static function get_directory__classpaths(string $directory_path, string $class_prefix) {
    if (! is_dir($directory_path)) {
      return [];
    }

    $assets  = static::get_directory__files($directory_path); // It's assumed we don't have mixed assets.
    $results = [];

    // real_path -> file_name. We don't need real_path here
    foreach($assets as $asset) {
      $class_path = $class_prefix . '\\' . substr($asset, 0, strripos($asset, '.'));  // We are assuming there is a file extension here.

      $results[class_basename($class_path)] = $class_path;
    }

    return $results;
  }

  /**
   * Depth first search.
   * Recursively generates all directory assets in a flat array.
   *
   * @since  1.4.0
   * @param  string $file_path
   * @return array
   */
  public static function get_directory_traversal(string $file_path) {
    $base_directory = static::get_directory($file_path, ['.', '..', '.git', 'vendor', 'node_modules', 'storage', 'bootstrap']);
    $results        = [];

    foreach ($base_directory as $full_path => $file_name) {
      if (is_dir($full_path)) {
        $results = array_merge($results, static::get_directory_traversal($full_path));
      } else {
        $results[$full_path] = $file_name;
      }
    }

    return $results;
  }

  /**
   * Only goes down one level - no need for exceptions.
   * This is useful for bringing togwether project and tuxedo assets
   * together in a single choice list.
   *
   * @since  0.0.1
   * @param  array $directories
   * @return array
   */
  public static function merge_directory_files(array $directories) {
    $results = [];

    foreach ($directories as $directory) {
      $results = array_merge($results, static::get_directory__files($directory));
    }

    return $results;
  }

  // Deprecated functions

  /**
   * Some controller action patterns use a "find the static view or find the database entry"
   * flow.
   * In those cases, we don't want to abort(404) if the static view doesn't exist.
   * This also means we don't want to throw any Exception - even if this method
   * really suggests that throwing an exception is the best fall through case.
   *
   * @TODO   remove in 2.0.0
   * @deprecated
   * @since  1.4.0 deprecated
   * @codeCoverageIgnore
   * @param  string $slug
   * @param  string $local_path
   * @param  array  $extra_data
   * @return null|\Illuminate\View\View|\Illuminate\Contracts\View\Factory
   */
  public static function get_public_view(string $slug, string $local_path, array $extra_data = []) {
    return static::get_view($slug, $local_path, $extra_data);
  }

}
