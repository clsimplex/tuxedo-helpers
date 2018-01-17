<?php

namespace CLSimplex\Tuxedo\Helpers\Classes;

/**
 * These are functions that help us manipulate arrays.
 *
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  0.0.1
 */
class ArrayHelper {

  /**
   * As part of the move toward unifying the components
   * we can use this as another testable unit.
   *
   * If we add quotes here, the browser will add a second set
   * ruining errythang.
   *
   * @since  0.0.1
   * @link   http://php.net/http_build_query
   * @link   http://php.net/manual/en/function.str-pad.php
   * @param  array $attributes
   * @param  array $defaults
   * @return string
   */
  public static function get_attribute_string(array $attributes, array $defaults = []) {
    $data       = array_merge($defaults, $attributes);
    $http_query = http_build_query($data, '', '&amp;', PHP_QUERY_RFC3986); // spaces are %20 encoded.

    // Remove URL encoding.
    $unencoded_string = urldecode($http_query);
    $quote_update     = str_replace(['=', '&amp;'], ['="', '" '], $unencoded_string);

    if ( ! empty($quote_update) ) {
      $quote_update .= '"';
    }

    return $quote_update;
  }

  /**
   * If there is no mapping, the value is KEPT.
   *
   * A simple mapping function.
   * Essentially this allows us to swap keys - if a swapping exists.
   * Key map is in the format of [$old_key => $new_key].
   *
   *
   * @since  0.0.1
   * @param  array $original_array
   * @param  array $key_map
   * @return array
   */
  public static function with_swapped_keys(array $original_array, array $key_map = []) {
    $result = [];

    if ( empty($key_map) ) {
      return $original_array;
    }

    array_walk($original_array, function($value, $old_key) use(&$result, $key_map) {
      if ( array_key_exists($old_key, $key_map) ) {
        $result[ $key_map[$old_key] ] = $value;
      } else {
        $result[ $old_key ] = $value;
      }
    });

    return $result;
  }

  /**
   * If there is no mapping, the value is removed.
   *
   * A simple mapping function.
   * Essentially this allows us to swap keys - if a swapping exists.
   * Key map is in the format of [$old_key => $new_key].
   *
   *
   * @since  0.0.1
   * @param  array $original_array
   * @param  array $key_map
   * @return array
   */
  public static function only_swapped_keys(array $original_array, array $key_map = []) {
    $result = [];

    if ( empty($key_map) ) {
      return $original_array;
    }

    // Pass result as a reference, we want to modify it.
    array_walk($original_array, function($value, $old_key) use(&$result, $key_map) {
      if ( array_key_exists( $old_key, $key_map ) ) {
        $result[ $key_map[$old_key] ] = $value;
      }
    });

    return $result;
  }

  /**
   * Checking if keys exists in arrays that may or
   * may not exist happens a lot.
   * If you pass an array to $keys - all keys
   * must exists for this to be true.
   *
   * @since  0.0.1
   * @param  mixed $keys
   * @param  array $array
   * @return bool
   */
  public static function custom_key_exists($keys, array $array = []) {
    if ( empty($array) ) {
      return false;
    }

    // Convert to array.
    if ( is_string($keys) ) {
      $keys = [$keys];
    }

    /*
     * How this works:
     * We remove keys that dont exists in the array.
     * If the array has less keys than when it started,
     * not all of the keys matched. - therefore FALSE!
     */

    $original_count = count($keys);

    $check_keys = array_filter($keys, function($key_string) use($array) {
      return array_key_exists($key_string, $array);
    });

    return $original_count === count($check_keys);
  }

  /**
   * PHP doesn't have a true array difference.
   * This does not dive deeper into the set.
   * Keys are destroyed and the result is sorted.
   *
   * @since  0.0.1
   * @link   http://php.net/manual/en/function.array-diff.php
   * @param  array $array_one
   * @param  array $array_two
   * @return array
   */
  public static function array_difference(array $array_one, array $array_two) {
    $intersection = array_intersect($array_one, $array_two);
    $result       = array_values(array_merge(array_diff($array_one, $intersection), array_diff($array_two, $intersection)));

    sort($result); // sorts in place. No return value.

    return $result;
  }

  /**
   * This does NOT preserve keys.
   * All it does is remove duplicate values.
   *
   * @since  0.0.1
   * @link   http://php.net/manual/en/function.array-unique.php
   * @param  array $array
   * @return array
   */
  public static function get_unique(array $array) {
    return array_values(array_unique($array, SORT_REGULAR));
  }

  /**
   * Removes values with only whitespace or are empty.
   * This also removes trailing and leading whitespace from entries.
   * Preserves array keys.
   *
   * @since  0.0.1
   * @link   https://stackoverflow.com/questions/3654295/remove-empty-array-elements#3654309
   * @param  array $array
   * @return array
   */
  public static function remove_empty_strings(array $array) {
    return array_filter(array_map('trim', $array), 'strlen');
  }
}
