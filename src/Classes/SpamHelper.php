<?php

namespace CLSimplex\Tuxedo\Helpers;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.3.2 Minor updates.
 * @since  1.2.0 Updated get_keyword_score().
 * @since  1.1.0 updated get_keyword_score().
 * @since  1.0.0
 */
class SpamHelper {

  const SCORE_THRESHOLD = 3;

  /**
   * Whitelist is counter-intuitive in this sense. Be careful.
   *
   * @since  1.5.1 added $whitelist parameter
   * @since  1.5.0
   * @param  array $input
   * @param  array $whitelist
   * @return bool
   */
  public static function is_spam(array $input, array $whitelist) {
    $score = 0;

    foreach($input as $field => $value) {
      if (in_array($field, $whitelist)) {
        $score += static::get_field_score($field, $value);
      }
    }

    return $score >= static::SCORE_THRESHOLD;
  }

  /**
   * Containing URLs/HTML is suspicious.
   * COntaining cryllic characters is suspicious.
   * single "words" that mixed numbers and letters are suspicious.
   *
   * @since  1.7.0  urls fail score is now added to total instead of being capped.
   * @since  1.5.1  urls automatically fail now.
   * @since  1.5.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @param  string $field currently unused.
   * @param  mixed  $value
   * @return int
   */
  public static function get_field_score(string $field, $value) {
    if(empty($value)) {
      return 0;
    }

    $score = 0;

    if (static::has_url($value)) {
      $score += static::SCORE_THRESHOLD;
    }

    if (static::has_html_tags($value)) {
      $score += 2; // Heavily $value!
    }

    $score += static::get_keyword_score($value);
    $score += static::get_russian_word_count($value);

    return $score;
  }

  public static function has_html_tags(string $message) {
    return strip_tags($message) !== $message;
  }

  /**
   * # signs are delimiters for the regex pattern.
   *
   * @since  1.7.0  added www.
   * @since  1.5.0
   * @link   https://stackoverflow.com/a/5968861
   * @param  string $value
   * @return bool
   */
  public static function has_url(string $value) {
    return (bool)preg_match("#https?://.+#", $value) || stripos($value, 'www.') !== false;
  }

  /**
   * Do not spend a lot of time on this. Merely a simple
   * catch for the time being.
   *
   * Keyword scores are currently between [0.125, 2.5]
   *
   * @since  1.5.0  updated.
   * @since  1.2.0  Updated word list and scores.
   * @since  1.1.0  Updated word list and scores.
   * @since  1.0.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @param  string $message
   * @return int
   */
  public static function get_keyword_score(string $message) {
    $score = 0;

    $keywords = [
      'buy'        => 0.125,
      'girls'      => 0.125,
      'tablets'    => 0.125,
      'pills'      => 0.125,
      'cheap'      => 0.125,
      'traffic'    => 0.5,
      'invest'     => 0.5,
      'dating'     => 0.5,
      'casino'     => 1,
      'bitcoin'    => 1,
      'babes'      => 1,
      'erotic'     => 1.25,
      'fuck'       => 1.5,
      'sex'        => 1.5,
      'porn'       => 2,
      'seo'        => 2,
      'ico'        => 2,
      'milf'       => 2.5,
      'getropin'   => 2.5,
      'riptropin'  => 2.5,
      'viagra'     => 2.5,
      'hygetropin' => 2.5,
      'cialis'     => 2.5,
      'tamoxifen'  => 2.5,
    ];

    $message = strtolower($message);

    foreach ($keywords as $word => $word_score) {
      $occurances = substr_count($message, $word);

      $score += $word_score * $occurances;
    }

    return $score;
  }

  /**
   * @since  1.5.0 empty $message case added.
   * @since  1.4.0 updated PHPDOC return type (bool -> int.)
   * @since  1.3.1
   * @param  string $message
   * @return int
   */
  public static function get_russian_word_count(string $message) {
    if(empty($message)) {
      return 0;
    }

    $result = [];

    preg_match_all("/[\x{0410}-\x{042F}]+/ui", $message, $result); // Cryllic characters

    if (! empty($result) && empty($result[0])) {
      return 0;
    }

    return count($result[0]);
  }

  /**
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @TODO   remove in 2.0.0
   * @deprecated
   * @since  1.5.0 deprecated
   * @since  1.1.0 updated email domain list.
   *               Blocking all russian email addresses.
   * @since  1.0.0
   * @see    http://php.net/manual/en/function.stripos.php
   * @param  string $domain
   * @return bool
   */
  public static function is_email_blacklisted(string $email) {
    $domains = [
      'baburn', 'marvsz', 'zxcvbnmy', 'getabusinessfunded',
      'profunding', 'businesscapitaladvisor',
      'probusinessfunding', 'fastfundingadvisors',
      'businessloansfundednow', 'noreply', 'mobileyell',
    ];

    if (ends_with($email, '.ru')) { // Block ALL russian emails.
      return true;
    }

    foreach ($domains as $bad_domain) {
      if (stripos($email, $bad_domain) > 0) {
        return true;
      }
    }

    return false;
  }

  /**
   * @TODO   remove in 2.0.0
   * @deprecated
   * @since  1.5.0 deprecated
   * @since  1.0.0
   * @param  string $email
   * @param  string $message
   * @return int
   */
  public static function get_spam_score(string $email, string $message) {
    $base_score = 0.5; // No one is born innocent.

    if (static::is_email_blacklisted($email)) {
      $base_score += 5; // Game over!
    }

    if (static::has_html_tags($message)) {
      $base_score += 2; // Heavily suspicious!
    }

    $base_score += static::get_russian_word_count($message);

    return $base_score + static::get_keyword_score($message);
  }

}
