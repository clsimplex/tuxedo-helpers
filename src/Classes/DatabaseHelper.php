<?php

namespace CLSimplex\Tuxedo\Helpers;

use Symfony\Component\Process\Process;

use CLSimplex\Tuxedo\Helpers\StorageHelper;

/**
 * Due to use of config(), this requires a laravel project.
 *
 * @TODO   maybe pass config values directly to remove implicit laravel dependencies?
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.5.0 moved into Helpers from Operations
 * @codeCoverageIgnore
 */
class DatabaseHelper {
  /**
   * Dumps database to a local file.
   *
   * @since  1.5.0 moved into Helpers from Operations
   * @codeCoverageIgnore
   * @param  string $file_name
   * @param  string $path
   * @param  int    $process_timeout
   * @return bool
   */
  public static function dump_database(string $file_name, string $path, int $process_timeout = 30) {
    StorageHelper::get_local_file($file_name); // We do nothing with the return value.

    $command_string = sprintf(
      'mysqldump --opt --user=%s --password=%s --host=%s %s > %s',
      escapeshellarg(config('database.connections.mysql.username')),
      escapeshellarg(config('database.connections.mysql.password')),
      escapeshellarg(config('database.connections.mysql.host')),
      config('database.connections.mysql.database'),
      $path
    );

    $process = new Process( $command_string );
    $process->setTimeout( $process_timeout );
    $process->run();

    return $process->isSuccessful() && StorageHelper::has_local_file($file_name);
  }

  /**
   * Base case pattern.
   * Backup specialist function.
   * Checks to see if we need to perform the backup.
   *
   * @since  1.5.0 moved into Helpers from Operations
   * @codeCoverageIgnore
   * @param  string $file_name
   * @return bool
   */
  public static function are_new_entries(string $file_name) {
    $local_dump_contents = StorageHelper::get_local_file($file_name);
    $normalized_contents = preg_replace('/-- Dump completed on[\s]+[0-9]{4}-[0-9]{2}-[0-9]{2}[\s]+[0-9]{0,2}:[0-9]{0,2}:[0-9]{0,2}/', '', $local_dump_contents);
    $hash_contents       = StorageHelper::get_local_file('data-hash.txt');
    $data_hash           = md5($normalized_contents);

    StorageHelper::put_into_local_file('data-hash.txt', $data_hash);

    return ! str_is($data_hash, $hash_contents);
  }
}
