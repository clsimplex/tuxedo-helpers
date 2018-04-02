<?php

namespace CLSimplex\Tuxedo\Helpers;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.0.0
 */
class SpamHelper {

  public static function has_html_tags(string $message) {
    return strip_tags($message) !== $message;
  }

  /**
   * Do not spend a lot of time on this. Merely a simple
   * catch for the time being.
   *
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @since  1.0.0
   * @param  string $message
   * @return int
   */
  public static function get_keyword_score(string $message) {
    $score = 0;

    $keywords = [
      'babes'      => 1,
      'fuck'       => 1,
      'sex'        => 1,
      'porn'       => 2,
      'getropin'   => 3,
      'riptropin'  => 3,
      'viagra'     => 3,
      'hygetropin' => 3,
      'cialis'     => 3,
      'milf'       => 2,
    ];

    $message = strtolower($message);

    foreach ( $keywords as $word => $word_score ) {
      $occurances = substr_count( $message, $word );

      $score += $word_score * $occurances;
    }

    return $score;
  }

  /**
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @since  1.0.0
   * @see    http://php.net/manual/en/function.stripos.php
   * @param  string $domain
   * @return bool
   */
  public static function is_email_blacklisted(string $email) {
    $domains = [
      'baburn', 'marvsz', 'zxcvbnmy', 'getabusinessfunded',
      'probusinessfunding', 'fastfundingadvisors',
      'businessloansfundednow'
    ];

    foreach ( $domains as $bad_domain ) {
      if ( stripos($email, $bad_domain) > 0 ) {
        return true;
      }
    }

    return false;
  }
}
