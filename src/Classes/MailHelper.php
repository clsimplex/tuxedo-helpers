<?php

namespace CLSimplex\Tuxedo\Helpers;

use Illuminate\Support\Facades\Validator;
use CLSimplex\Tuxedo\Helpers\ArrayHelper;

/**
 * Theses are the helper functions for anything to do with Mail.
 * In essence, THIS in addition to the existing mail wrappers
 * that laravel provides is the mail module.
 *
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.2.0 removing Mailgun features.
 * @since  0.0.1
 */
class MailHelper {

  /**
   * We use an unsubscription hash for a couple reasons:
   * 1. Makes it difficult to unsubscribe unwilling users.
   * 2. We assume a proper email.
   *
   * @since  0.0.1
   * @codeCoverageIgnore
   * @param  string $email
   * @return array
   */
  public static function get_unsubscription_info(string $email) {
    return [
      'email' => $email,
      'hash'  => hash('md5', 'unsubscribed_secret_string' . $email . config('app.name'))
    ];
  }

  /**
   * Base case pattern.
   *
   * @since  0.0.1
   * @codeCoverageIgnore
   * @param  string $email
   * @param  string $hash
   * @return bool
   */
  public static function is_valid_unsubscribe(string $email, string $hash) {
    if ( ! static::is_valid_email($email) ) {
      return false;
    }

    $hash_info = static::get_unsubscription_info( $email );

    return $hash_info['email'] === $email && $hash_info['hash'] === $hash;
  }

  /**
   * Given a mixed value, return a uniquely populated array of emails.
   * Strings need to be CSVs.
   * Values are also trimmed.
   *
   * @since  0.0.1
   * @param  mixed $mixed_input
   * @return array
   */
  public static function mixed_to_list($mixed_input) {
    if ( empty($mixed_input) ) {
      return [];
    }

    $array = $mixed_input;

    if ( is_string($array) ) {
      $array = explode(',', $array);
    }

    $set_array = ArrayHelper::get_unique(array_map('trim', $array));

    return ArrayHelper::remove_empty_strings($set_array);
  }

  /**
   * This is the meat and potatoes mailing function.
   * Call this before you send any mail out.
   *
   * 1. Remove duplicates within each list.
   * 2. Remove duplicates between each list. (set diff.)
   *    Priority goes in TO > CC > BCC order.
   * 3. Remove unsubscribed addresses.
   *
   * @since  0.0.1
   * @param  array $to
   * @param  array $cc
   * @param  array $bcc
   * @param  array $unsubcribes
   * @param  mixed $to_list
   * @param  mixed $cc_list
   * @param  mixed $bcc_list
   * @return array
   */
  public static function get_mailing_list($to_list, $cc_list, $bcc_list, array $unsubscribes = []) {

    $result = [
      'to'  => [],
      'cc'  => [],
      'bcc' => [],
    ];

    $array_to  = static::mixed_to_list($to_list);
    $array_cc  = static::mixed_to_list($cc_list);
    $array_bcc = static::mixed_to_list($bcc_list);

    $result['to']  = array_values(array_diff($array_to, $unsubscribes));
    $result['cc']  = array_values(array_diff($array_cc, $array_to, $unsubscribes));
    $result['bcc'] = array_values(array_diff($array_bcc, $array_to, $array_cc, $unsubscribes));

    return $result;
  }

  /**
   * Sometimes you just want a flat list. Sometimes you just want to BCC everyone.
   *
   * @since  0.0.1
   * @param  mixed $to
   * @param  mixed $cc
   * @param  mixed $bcc
   * @param  mixed $unsubcribes
   * @param  mixed $to_list
   * @param  mixed $cc_list
   * @param  mixed $bcc_list
   * @return array
   */
  public static function get_flat_mailing_list($to_list, $cc_list, $bcc_list, array $unsubscribes = []) {
    $array_to  = static::mixed_to_list($to_list);
    $array_cc  = static::mixed_to_list($cc_list);
    $array_bcc = static::mixed_to_list($bcc_list);

    $full_list = static::mixed_to_list(array_merge($array_to, $array_cc, $array_bcc));

    return array_values(array_diff($full_list, $unsubscribes));
  }

  /**
   * Careful, this function can potentially cost us money.
   *
   * @since  1.2.0  removing Mailgun validation checks.
   * @since  0.0.1
   * @param  string $email
   * @param  bool   $mailgun_check
   * @return bool
   */
  public static function is_valid_email(string $email, bool $mailgun_check = false) {
    if ( empty($email) ) {
      return false;
    }

    /*
     * If we're in production, we have access to
     * Laravel Validator.
     * We can do the simple check here and save the API call.
     *
     * If it's obviously a bad email, we reject it.
     */
    if ( env('APP_ENV') !== 'testing' ) {
      $validator = Validator::make(['email' => $email], ['email' => 'required|email|min:5|max:254']);

      return ! $validator->fails();
    }

    return true; // We don't care at this point.
  }

  /**
   * Given an array of strings, remove invalid email strings.
   *
   * @since  0.0.1
   * @codeCoverageIgnore
   * @param  array $emails
   * @return array
   */
  public static function remove_invalid_emails(array $emails) {
    return array_filter($emails, function (string $value) {
      return static::is_valid_email($value);
    });
  }
}
