<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\FileHelper;
use PHPUnit\Framework\TestCase;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.4.0
 */
class FileHelperTest extends TestCase {

  /**
   * @since  1.4.0
   * @see    FileHelperTest::test_get_directory__classpaths
   * @return array
   */
  public function dataprovider__get_directory__classpaths() {
    return [
      'empty case'    => ['',             str_random(10),  []],
      'bad directory' => [str_random(10), '/class/prefix', []],
      'classes'       => [dirname(dirname(__DIR__)) . '/src/Classes', 'CLSimplex\\Tuxedo\\Helpers', [
        'ArrayHelper'   => 'CLSimplex\\Tuxedo\\Helpers\\ArrayHelper',
        'FileHelper'    => 'CLSimplex\\Tuxedo\\Helpers\\FileHelper',
        'MailHelper'    => 'CLSimplex\\Tuxedo\\Helpers\\MailHelper',
        'SpamHelper'    => 'CLSimplex\\Tuxedo\\Helpers\\SpamHelper',
        'StorageHelper' => 'CLSimplex\\Tuxedo\\Helpers\\StorageHelper',
        'StringHelper'  => 'CLSimplex\\Tuxedo\\Helpers\\StringHelper',
        'TimeHelper'    => 'CLSimplex\\Tuxedo\\Helpers\\TimeHelper'
      ]],
    ];
  }

  /**
   * @since        1.5.0 testing for array subset
   * @since        1.4.0
   * @dataProvider dataprovider__get_directory__classpaths
   * @covers       FileHelper::get_directory__classpaths
   * @group        helpers
   * @group        unit
   * @small
   * @return       void
   */
  public function test_get_directory__classpaths(string $directory_path, string $class_prefix, array $expected) {
    $this->assertArraySubset($expected, FileHelper::get_directory__classpaths($directory_path, $class_prefix));
  }

  /**
   * This is most reliable form currently.
   *
   * @since  1.4.0
   * @covers FileHelper::get_directory
   * @group  helpers
   * @group  unit
   * @small
   * @return void
   */
  public function test_list_directory() {
    $this->assertContains('FileHelperTest.php', FileHelper::get_directory(__DIR__));
  }

  /**
   * list_directory should remove parent
   * and grandparent options.
   *
   * @since  1.4.0
   * @covers FileHelper::get_directory
   * @group  helpers
   * @group  unit
   * @small
   * @return void
   */
  public function test_list_directory__parent_folders() {
    $this->assertNotContains(['..', '.'], FileHelper::get_directory(__DIR__));
  }

}
