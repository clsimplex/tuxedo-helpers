<?php

namespace CLSimplex\Tuxedo\Helpers;

use Carbon\Carbon;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  0.0.1
 */
class TimeHelper {

  /**
   * End of day is used in several spaces.
   * We are ignoring this method from code coverage
   * because testing this method would involve the exact code
   * used to build it.
   *
   * @since  0.0.1
   * @codeConverageIgnore
   * @return int
   */
  public static function end_of_day_minutes() {
    $carbon_now = Carbon::now();
    return $carbon_now->diffInMinutes($carbon_now->copy()->endOfDay());
  }

  /**
   * End of month is used in analytics module.
   * We are ignoring this method from code coverage
   * because testing this method would involve the exact code
   * used to build it.
   *
   * @since  0.0.1
   * @codeConverageIgnore
   * @return int
   */
  public static function end_of_month_minutes() {
    $carbon_now = Carbon::now();
    return $carbon_now->diffInMinutes($carbon_now->copy()->endOfMonth());
  }

  /**
   * Gives an array of Carbon instances.
   * This is initially used for the Analytics module
   * to generate a range of dates for a given month.
   * If you enter a month higher than 12, it simply adds a month.
   * January 1971 is the 13th month of 1970.
   * The zero-ith month is december from the year before.
   * IMPORTANT NOTE - We are disallowing all non-natural numbers and zero.
   *                  We aren't disallowing months higher than 12...yet.
   *
   * @since  0.0.1
   * @param  int   $year
   * @param  int   $month
   * @return array
   */
  public static function get_carbon__month(int $year, int $month) {
    if ( $month < 1 || $year < 1000 ) {
      return [];
    }

    $month         = Carbon::createFromDate($year, $month, 1);
    $days_in_month = $month->daysInMonth;
    $month_array   = [];

    for ( $i = 0; $i < $days_in_month; $i++ ) {
      // Array append.
      $month_array[] = $month->copy();
      $month->addDay();
    }

    return $month_array;
  }

  /**
   * Monday to Sunday.
   *
   * @since  0.0.1
   * @param  int   $year
   * @param  int   $month
   * @param  int   $day
   * @return array
   */
  public static function get_carbon__week(int $year, int $month, int $day) {
    if ( $month < 1 || $year < 1000 || $day < 1 || $day > 31 ) {
      return [];
    }

    $day        = Carbon::createFromDate($year, $month, $day);
    $this_week  = $day->startOfWeek();
    $week_array = [];

    for ( $i = 0; $i < 7; $i++ ) {
      $week_array[] = $this_week->copy();
      $day->addDay();
    }

    return $week_array;
  }

  /**
   * Night is the fall-through amount since
   * it falls on both sides of 12.
   *
   * @since  1.0.0 flipped logic. Moving into unit testable function.
   * @since  0.0.1
   * @return string
   */
  public static function get_phase_of_current_day() {
    return static::get_phase_of_day(Carbon::now());
  }

  /**
   * Testable and reusuable unit.
   * Given a carbon instance,
   * what "time" of day is it?
   *
   * - Morning
   * - Afternoon
   * - Evening
   * - Night
   *
   * @since  1.0.0 moved core logic from get_phase_of_current_day.
   * @param  Carbon\Carbon $moment the moment in question
   * @return string
   */
  public static function get_phase_of_day(Carbon $moment) {
    $morning__end   = Carbon::create($moment->year, $moment->month, $moment->day, 12,  0, 0);
    $afternoon__end = Carbon::create($moment->year, $moment->month, $moment->day, 17,  0, 0);
    $evening__end   = Carbon::create($moment->year, $moment->month, $moment->day, 21,  0, 0);

    $result = 'night';

    if ( $moment->lt($evening__end) ) {
      $result = 'evening';
    }

    if ( $moment->lt($afternoon__end) ) {
      $result = 'afternoon';
    }

    if ( $moment->lt($morning__end) ) {
      $result = 'morning';
    }

    return $result;
  }
}
