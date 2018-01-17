<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\Classes\MailHelper;
use PHPUnit\Framework\TestCase;

/**
 * None of these tests catch invalid emails.
 * Email validity is handled by the Laravel framework.
 * MailTest captures the data structure qualities we use.
 *
 * @since 0.0.1
 */
class MailHelperTest extends TestCase {

  /**
   * dataprovider pattern.
   * Final item in array is expected value.
   *
   * We have string, array, and empty test cases in here.
   *
   * @since  0.0.1
   * @see    MailHelperTest::test_mixed_to_list
   * @return array
   */
  public function dataprovider__mixed_to_list() {
    return [
      'string: csv list'   => ['levon@levon.com,levon@levon.com,     bob@bob.com  , rob@rob.com  ,levon@levon.com ',    ['levon@levon.com', 'bob@bob.com', 'rob@rob.com']],
      'array: simple list' => [['levon@levon.com', 'levon@levon.com', 'bob@bob.com', 'rob@rob.com', 'levon@levon.com'], ['levon@levon.com', 'bob@bob.com', 'rob@rob.com']],
      'empty: null value'                                          => [null, []],
      'empty: empty string'                                        => ['', []],
      'empty: empty string with whitespace'                        => ['       ', []],
      'empty: empty array'                                         => [[], []],
      'empty: array with empty string'                             => [[''], []],
      'empty: array with multiple empty string values'             => [['', ''], []],
      'empty: array with multiple empty string with spaces values' => [[' ', '  ', '   '], []],
      'empty: array with multiple null values'                     => [[null, null], []],
      'empty: array with mixed empty values'                       => [['', null, ' '], []],
    ];
  }

  /**
   * CAREFUL API LIMITS ARE IN EFFECT.
   * Testing this costs money.
   *
   * @since  0.0.1
   * @see    MailHelperTest::test_is_valid_email
   * @return array
   */
  public function dataprovider__is_valid_email() {
    if ( env('TEST_PAID_API', false) ) {
      return [
        'empty email'           => ['',                                    false],
        'malformed email'       => ['malformed.whoops',                    false],
        'valid, exists'         => ['info@clsimplex.com',                  true],
        'valid, does not exist' => ['foobar@super-invalid-email-1853.com', false]
      ];
    }

    return [];
  }

  /**
   * Using dataprovider pattern, we can consolidate all test cases
   * to within the dataprovider array.
   * This also reduces the number of test dependencies we have.
   *
   * @since  0.0.1
   * @dataProvider dataprovider__mixed_to_list
   * @covers Mail::mixed_to_list
   * @group  helpers
   * @group  unit
   * @small
   * @param  mixed $input
   * @param  array $expected
   * @return void
   */
  public function test_mixed_to_list( $input, array $expected ) {
    $this->assertEquals( $expected, MailHelper::mixed_to_list($input) );
  }

  /**
   * This tests a number of cases where emails are in different lists, spaced out, unsubscribed,
   * and duplicated.
   *
   * @since   0.0.1
   * @depends test_mixed_to_list
   * @covers  Mail::get_mailing_list
   * @group   helpers
   * @group   unit
   * @small
   * @return void
   */
  public function test_get_mailing_list() {
    $to           = 'a@a.com, b@b.com,c@c.com,   c@c.com,d@d.com';
    $cc           = ['b@b.com', 'e@e.com', 'f@f.com', 'g@g.com', 'k@k.com', 'k@k.com'];
    $bcc          = ['d@d.com', 'e@e.com', 'h@h.com', 'i@i.com', 'b@b.com', 'd@d.com', 'j@j.com'];
    $unsubscribes = ['a@a.com', 'j@j.com', 'k@k.com'];

    $expected = [
      'to'  => ['b@b.com', 'c@c.com', 'd@d.com'],
      'cc'  => ['e@e.com', 'f@f.com', 'g@g.com'],
      'bcc' => ['h@h.com', 'i@i.com'],
    ];

    $result = MailHelper::get_mailing_list($to, $cc, $bcc, $unsubscribes);

    $this->assertEquals($expected, MailHelper::get_mailing_list($to, $cc, $bcc, $unsubscribes));
  }

  /**
   * This test addresses a 'corner' case in the original mailing
   * list generation function.
   * The issue seems to be that the cc array gets populated with an empty string.
   *
   * @since  0.0.1
   * @depends test_mixed_to_list
   * @covers Mail::get_mailing_list
   * @group  helpers
   * @group  unit
   * @group
   * @small
   * @return void
   */
  public function test_get_mailing_list__empty() {
    $to  = 'levon@dumb.com';
    $cc  = '';
    $bcc = '';

    $expected = [
      'to'  => ['levon@dumb.com'],
      'cc'  => [],
      'bcc' => [],
    ];

    $this->assertEquals($expected, MailHelper::get_mailing_list($to, $cc, $bcc));
  }

  /**
   * Tests the ability to create a flat list.
   *
   * @since   0.0.1
   * @depends test_mixed_to_list
   * @covers  Mail::get_flat_mailing_list
   * @group   helpers
   * @group   unit
   * @small
   * @return void
   */
  public function test_get_flat_mailing_list() {
    $to           = 'a@a.com, b@b.com,c@c.com,   c@c.com,d@d.com';
    $cc           = ['b@b.com', 'e@e.com', 'f@f.com', 'g@g.com', 'k@k.com', 'k@k.com'];
    $bcc          = ['d@d.com', 'e@e.com', 'h@h.com', 'i@i.com', 'b@b.com', 'd@d.com', 'j@j.com'];
    $unsubscribes = ['a@a.com', 'j@j.com', 'k@k.com'];

    $expected = ['b@b.com', 'c@c.com', 'd@d.com', 'e@e.com', 'f@f.com', 'g@g.com', 'h@h.com', 'i@i.com'];

    $this->assertEquals($expected, MailHelper::get_flat_mailing_list($to, $cc, $bcc, $unsubscribes));
  }

  /**
   * @since  0.0.1
   * @dataProvider dataprovider__is_valid_email
   * @covers MailHelper::is_valid_email
   * $group  helpers
   * @group  unit
   * @small
   * @param  string $original
   * @param  bool   $expected
   * @return void
   */
  public function test_is_valid_email(string $original, bool $expected) {
    return $this->assertEquals($expected, MailHelper::is_valid_email($original));
  }

  /**
   * @since  0.0.1
   * @depend test_get_flat_mailing_list
   * @covers Mail::get_flat_mailing_list
   * @group  helpers
   * @group  unit
   * @small
   * @return void
   */
  public function test_get_flat_mailing_list__empty() {
    $to           = '        ';
    $cc           = ['   '];
    $bcc          = [];
    $unsubscribes = [' ', '', '    '];

    $this->assertEmpty(MailHelper::get_flat_mailing_list($to, $cc, $bcc, $unsubscribes));
  }
}
