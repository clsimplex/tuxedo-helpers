<?php

namespace CLSimplex\Tuxedo\Helpers;

/**
 * @since  1.8.0 pennies_to_dollars
 * @since  1.6.0 added is_bad_password
 * @since  0.0.1
 * @author Levon Zadravec-Powell levon@clsimplex.com
 */
class StringHelper {

  /**
   * @since  1.6.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @link   https://secure.php.net/manual/en/function.file.php
   * @link   https://stackoverflow.com/a/6159705
   * @param  string $password_attempt
   * @return bool
   */
  public static function is_bad_password(string $password_attempt) {
    $bad_passwords = file(dirname(__DIR__) . '/Resources/bad_passwords.txt', FILE_IGNORE_NEW_LINES);

    return in_array($password_attempt, $bad_passwords);
  }

  /**
   * @since  0.0.1
   * @param  string $slug
   * @return string
   */
  public static function slug_to_words(string $slug) {
    return static::bleach_string(ucwords(str_replace(['_', '-', '/'], ' ', $slug)));
  }

  /**
   * This takes a string and gives us a slug.
   * More than simply formatting, it also removes
   * common words that aren't useful anyways.
   * This depends on the Laravel str_slug() function.
   * Note - if you want an 'exact' phrase, use Laravel's
   * str_slug() method.
   * 'battle-of-the-bands' shouldn't be an issue if 'of' + 'the'
   * get removed. You talk about about this topic on your page, right?
   *
   * @since  0.0.1
   * @param  string $string
   * @return string
   */
  public static function slugify(string $string) {
    $remove_these = [
      ',', '.', '!', '?', '/', '+',
      ' of ', ' the ', ' with ', ' are ',
      ' and ', ' or ', ' as ', ' a ', ' I '
    ];

    // Case insensitive replacement.
    $result = str_ireplace($remove_these, ' ', $string);

    return static::bleach_string(str_slug($result));
  }

  /**
   * Simple string sanitation.
   * Removed parameter type.
   *
   * @since  0.0.1
   * @param  mixed  $untrusted_string
   * @return string
   */
  public static function bleach_string($untrusted_string) {
    if (is_null($untrusted_string)) {
      return '';
    }

    return trim(htmlspecialchars($untrusted_string));
  }

  /**
   * @TODO   handle non-contained comment strings.
   * @since  0.0.1
   * @see    CLSimplex\Monocle\Middlware\MinifyResponse::handle
   * @see    StringHelper::advanced_string_replace
   * @param  string $buffer_string
   * @return string
   */
  public static function minify_string(string $buffer_string) {
    $regex_replacement = [
      '/\>[^\S ]+/s' => '>',
      '/[^\S ]+\</s' => '<',
      '/(\s)+/s'     => '\\1'
    ];

    return static::advanced_string_replace([], $regex_replacement, $buffer_string);
  }

  /**
   * @since  0.0.1
   * @param  string $input_string
   * @return string
   */
  public static function get_spaces_removed(string $input_string) {
    $input_string = str_replace(' ', '', $input_string);

    return static::bleach_string($input_string);
  }

  /**
   * Uses fall through pattern.
   *
   * @since  0.0.1
   * @param  string $canonical
   * @param  string $url_root
   * @return string
   */
  public static function get_slug_from_url(string $canonical, string $url_root) {
    $slug = static::bleach_string(substr($canonical, strlen($url_root)));

    if (empty($slug)) {
      return '/';
    }

    $query_position = strpos($slug, '?');

    if ($query_position === false) {
      return $slug;
    }

    return substr($slug, 0, $query_position);
  }

  /**
   * Popular formatted string helper.
   * This is used within BasePaymentModel and beyond.
   *
   * @since  1.8.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @param  int $pennies
   * @return string
   */
  public static function pennies_to_dollars(int $pennies): string {
    return money_format('%.2n', round($pennies / 100, 2));
  }

  /**
   * This performs two string replacements.
   * 1. normal string replace
   * 2. preg_replace
   * The main catch is being aware of the chained
   * nature of the whole thing. Unintended replacements
   * can occur if subpatterns exist.
   *
   * @TODO   parameter ordering unification
   * @since  0.0.1
   * @link   http://php.net/manual/en/function.str-replace.php
   * @link   http://php.net/manual/en/function.preg-replace.php
   * @param  array   $string_replace
   * @param  array   $regex_replace
   * @param  array   $original_string
   * @return string
   */
  public static function advanced_string_replace(array $string_replace, array $regex_replace, string $original_string) {
    try {
      $string_result = str_replace(array_keys($string_replace), array_values($string_replace), $original_string);

      return preg_replace(array_keys($regex_replace), array_values($regex_replace), $string_result);

    } catch (\Exception $exception) {
      return $original_string;
    }
  }

}
