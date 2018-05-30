<?php

namespace CLSimplex\Tuxedo\Helpers;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.2.0 Updated get_keyword_score().
 * @since  1.1.0 updated get_keyword_score().
 * @since  1.0.0
 */
class SpamHelper {

  const SCORE_THRESHOLD = 3;

  public static function has_html_tags(string $message) {
    return strip_tags($message) !== $message;
  }

  /**
   * Do not spend a lot of time on this. Merely a simple
   * catch for the time being.
   *
   * Keyword scores are currently between [0.125, 2.5]
   *
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @since  1.2.0  Updated word list and scores.
   * @since  1.1.0  Updated word list and scores.
   * @since  1.0.0
   * @param  string $message
   * @return int
   */
  public static function get_keyword_score(string $message) {
    $score = 0;

    $keywords = [
      'buy'        => 0.125,
      'tablets'    => 0.125,
      'pills'      => 0.125,
      'cheap'      => 0.125,
      'traffic'    => 0.5,
      'invest'     => 0.5,
      'casino'     => 1,
      'bitcoin'    => 1,
      'babes'      => 1,
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

    foreach ( $keywords as $word => $word_score ) {
      $occurances = substr_count($message, $word);

      $score += $word_score * $occurances;
    }

    return $score;
  }

  /**
   * @author Levon Zadravec-Powell levon@clsimplex.com
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
      'probusinessfunding', 'fastfundingadvisors',
      'businessloansfundednow', 'noreply', 'mobileyell',
    ];

    if ( ends_with($email, '.ru') ) { // Block ALL russian emails.
      return true;
    }

    foreach ( $domains as $bad_domain ) {
      if ( stripos($email, $bad_domain) > 0 ) {
        return true;
      }
    }

    return false;
  }

  /**
   * @since  1.0.0
   * @param  string $email
   * @param  string $message
   * @return int
   */
  public static function get_spam_score(string $email, string $message) {
    $base_score = 0.5; // No one is born innocent.

    if ( static::is_email_blacklisted($email) ) {
      $base_score += 5; // Game over!
    }

    if ( static::has_html_tags($message) ) {
      $base_score += 2; // Heavily suspicious!
    }

    return $base_score + static::get_keyword_score($message);
  }
}
