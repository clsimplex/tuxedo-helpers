<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\TimeHelper;
use PHPUnit\Framework\TestCase;

/**
 * None of these tests catch invalid emails.
 * Email validity is handled by the Laravel framework,
 * which explodes when trying to use here.
 *
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  0.0.1
 */
class TimeHelperTest extends TestCase {

  /**
   * Do we get a month's worth of carbon instances?
   *
   * @since  0.0.1
   * @covers TimeHelper::get_carbon__month
   * @group  helpers
   * @group  unit
   * @small
   * @return void
   */
  public function test_get_carbon__month__valid() {
    $date_string_lambda = function($carbon) {
      return $carbon->toDateString();
    };

    $carbon_array = TimeHelper::get_carbon__month(1970, 1);
    $string_dates = array_map($date_string_lambda, $carbon_array);

    $this->assertEquals([
      '1970-01-01',
      '1970-01-02',
      '1970-01-03',
      '1970-01-04',
      '1970-01-05',
      '1970-01-06',
      '1970-01-07',
      '1970-01-08',
      '1970-01-09',
      '1970-01-10',
      '1970-01-11',
      '1970-01-12',
      '1970-01-13',
      '1970-01-14',
      '1970-01-15',
      '1970-01-16',
      '1970-01-17',
      '1970-01-18',
      '1970-01-19',
      '1970-01-20',
      '1970-01-21',
      '1970-01-22',
      '1970-01-23',
      '1970-01-24',
      '1970-01-25',
      '1970-01-26',
      '1970-01-27',
      '1970-01-28',
      '1970-01-29',
      '1970-01-30',
      '1970-01-31',
    ], $string_dates);
  }

  /**
   * We attempt a graceful failure of the call.
   * NOTE - Carbon handles months 13, 14...it continues to the next year.
   *
   * @since  0.0.1
   * @covers TimeHelper::get_carbon__month
   * @group  helpers
   * @group  unit
   * @small
   * @return void
   */
  public function test_get_carbon__month__invalid() {
    $test_cases = [
      [-100, 1970],
      [10, 900],
      [0, 2017],
    ];

    array_walk($test_cases, function($parameters) {
      $this->assertEmpty( TimeHelper::get_carbon__month($parameters[1], $parameters[0]) );
    });
  }
}
