<?php

namespace CLSimplex\Tuxedo\Helpers;

/**
 * These are functions that help us manipulate arrays.
 *
 * @since  1.8.0 deprecated array_cycle
 *               cycle added
 *               added return typing
 * @since  1.3.2 fixed class overwrite issue with get_attribute_string
 * @since  1.3.0 array_cycle()
 * @since  0.0.1
 * @author Levon Zadravec-Powell levon@clsimplex.com
 */
class ArrayHelper {
  /**
   * Creates a string of HTML attributes you can insert into a tag.
   * Uses key -> value pairings. key="value"
   *
   * @since  1.4.0 Removed unused variable.
   * @since  1.3.2
   * @since  0.0.2 Fixed bug where a URL was one of the values.
   *               Reimplemented.
   * @since  0.0.1
   * @link   http://php.net/http_build_query
   * @link   http://php.net/manual/en/function.str-pad.php
   * @param  array  $attributes
   * @param  array  $defaults
   * @return string
   */
  public static function get_attribute_string(array $attributes, array $defaults = []): string {
    $result = [];

    foreach ($defaults as $key => $value) {
      $string = $value;

      if (array_key_exists($key, $attributes)) { // ADD the class if it's there.
        $string .= ' ' . $attributes[$key];
        unset($attributes[$key]); // So we don't add it twice.
      }

      $result[] = $key . '="' . $string . '"';
    }

    /*
     * Now we add non-default attributes.
     */
    foreach ($attributes as $key => $value) {
      $result[] = $key . '="' . $value . '"';
    }

    return implode(' ', $result);
  }

  /**
   * If there is no mapping, the value is KEPT.
   * A simple mapping function.
   * Essentially this allows us to swap keys - if a swapping exists.
   * Key map is in the format of [$old_key => $new_key].
   *
   * Previously known as get_mapped_array().
   *
   * @since  1.3.1 removing else expression.
   * @since  0.0.1
   * @param  array $original_array
   * @param  array $key_map
   * @return array
   */
  public static function with_swapped_keys(array $original_array, array $key_map = []): array {
    $result = [];

    if (empty($key_map)) {
      return $original_array;
    }

    array_walk($original_array, function($value, $old_key) use(&$result, $key_map) {
      $result[$old_key] = $value; // We first assume the mapping does not exist.

      if (array_key_exists($old_key, $key_map)) {
        $result[$key_map[$old_key]] = $value;
        unset($result[$old_key]);
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
  public static function only_swapped_keys(array $original_array, array $key_map = []): array {
    $result = [];

    if (empty($key_map)) {
      return $original_array;
    }

    // Pass result as a reference, we want to modify it.
    array_walk($original_array, function($value, $old_key) use(&$result, $key_map) {
      if (array_key_exists( $old_key, $key_map)) {
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
   * @link   https://secure.php.net/manual/en/function.array-key-exists.php
   * @param  mixed $keys
   * @param  array $array
   * @return bool
   */
  public static function custom_key_exists($keys, array $array = []): bool {
    if (empty($array)) {
      return FALSE;
    }

    // Convert to array.
    if (is_string($keys)) {
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
   * @TODO   ArrayHelper::array_difference -> ArrayHelper::difference?
   * @since  0.0.1
   * @link   http://php.net/manual/en/function.array-diff.php
   * @param  array $array_one
   * @param  array $array_two
   * @return array
   */
  public static function array_difference(array $array_one, array $array_two): array {
    $intersection = array_intersect($array_one, $array_two);
    $result       = array_values(array_merge(array_diff($array_one, $intersection), array_diff($array_two, $intersection)));

    sort($result); // sorts in place. No return value.

    return $result;
  }

  /**
   * Array cycle iterates through an array each time it is called,
   * moving the internal point back to the beginning if the end is reached.
   *
   * @since  1.8.0 renamed array_cycle -> cycle
   * @since  1.3.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @link   https://secure.php.net/manual/en/function.next.php
   * @link   https://secure.php.net/manual/en/function.reset.php
   * @link   https://secure.php.net/manual/en/function.key.php
   * @param  $array
   * @return mixed
   */
  public static function cycle(array &$array) {
    if (empty($array)) { // Deals with empty array edge case: reset().
      return null;
    }

    $current_value = current($array);

    $next = next($array);
    $key  = key($array);
    if ($next === false || $key === false) {
      reset($array);
    }

    return $current_value;
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
  public static function get_unique(array $array): array {
    return array_values(array_unique($array, SORT_REGULAR));
  }

  /**
   * Removes values with only whitespace or are empty.
   * This also removes trailing and leading whitespace from entries.
   * Preserves array keys.
   * strlen returns zero for empty strings, which are falsy when running
   * array_filter - hence the removal of empty strings here.
   * Don't we love the order of parameters for array_filter and array_map?
   *
   * @since  1.2.0 updated phpdoc
   * @since  0.0.1
   * @link   https://stackoverflow.com/questions/3654295/remove-empty-array-elements#3654309
   * @link   https://secure.php.net/manual/en/function.array-filter.php
   * @link   https://secure.php.net/manual/en/function.array-map.php
   * @param  array $array
   * @return array
   */
  public static function remove_empty_strings(array $array): array {
    return array_filter(array_map('trim', $array), 'strlen');
  }

  // Deprecated functions

  /**
   * Array cycle iterates through an array each time it is called,
   * moving the internal point back to the beginning if the end is reached.
   *
   * @TODO   remove in 2.0.0
   * @deprecated 2.0.0
   * @since  1.8.0 deprecated
   * @since  1.3.0
   * @param  $array
   * @return mixed
   */
  public static function array_cycle(array &$array) {
    return static::cycle($array);
  }

}
