<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\Classes\Background;
use PHPUnit\Framework\TestCase;

/**
 * This adds important insight into the functionality of
 * the background module and gives us a platform to integrate
 * third party sources later.
 *
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  0.0.1
 */
class BackgroundHelperTest extends TestCase {

  /**
   * @since  0.0.1
   * @see    BackgroundTest::test_get_internal_backgrounds
   * @return array
   */
  public function dataprovider__test_get_internal_background() {
    $test_cases = Background::get_internal_backgrounds();

    $results = [];

    foreach ( $test_cases as $filepath ) {
      $results[ $filepath ] = [$filepath];
    }

    return $results;
  }

  /**
   * We take the directory returned from test_internal_directory__exists.
   *
   * @since   0.0.1
   * @link    https://stackoverflow.com/questions/10707526/can-i-use-depends-to-depend-on-a-test-using-an-dataprovider
   * @dataProvider dataprovider__test_get_internal_background
   * @depends test_get_internal_directory
   * @covers  Background::get_internal_backgrounds
   * @group   helpers
   * @group   unit
   * @small
   * @param   string $background
   * @param   string $directory  this comes from test_get_internal_directory()
   * @return  void
   */
  public function test_get_internal_backgrounds(string $background, string $directory) {
    $this->assertFileExists($directory . $background);
  }
}
