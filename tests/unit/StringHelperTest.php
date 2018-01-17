<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\Classes\StringHelper;
use PHPUnit\Framework\TestCase;

/**
 * None of these tests catch invalid emails.
 * Email validity is handled by the Laravel framework,
 * which explodes when trying to use here.
 *
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  0.0.1
 */
class StringHelperTest extends TestCase {

  /**
   * dataprovider pattern.
   * Final item in array is expected value.
   *
   * @since  0.0.1
   * @see    StringHelperTest::test_slug_to_words
   * @return array
   */
  public function dataprovider__slug_to_words() {
    return [
      'empty string'  => ['', ''],
      'simple slug'   => ['hello-world', 'Hello World'],
      'underscore'    => ['hello_world', 'Hello World'],
      'simple url'    => ['/about-us', 'About Us'],
      'multipart url' => ['/blog/gym-video', 'Blog Gym Video'],
    ];
  }

  /**
   * dataprovider pattern.
   * Final item in array is expected value.
   *
   * @since  0.0.1
   * @see    StringHelperTest::test_slugify
   * @return array
   */
  public function dataprovider__slugify() {
    return [
      'empty string'                        => ['', ''],
      'simple word'                         => ['Hello World', 'hello-world'],
      'underscore'                          => ['hello_world', 'hello-world'],
      'spaces and non-title words'          => ['Planet Of The Apes', 'planet-apes'],
      'spaces and non-title words case two' => ['battle of the bands', 'battle-bands'],
      'url'                                 => ['/capabilities', 'capabilities'],
      'plus character'                      => ['slug+this', 'slug-this'],
    ];
  }

  /**
   * dataprovider pattern.
   *
   * @since  0.0.1
   * @see    StringHelperTest::test_advanced_string_replace
   * @return array
   */
  public function dataprovider__advanced_string_replace() {
    return [
      'Empty values 1' => [[], [], '', ''],
      'Empty values 2' => [['' => ''], ['' => ''], '', ''],

      'Simple string replace' => [[
        'search' => 'replace',
      ], [], 'search', 'replace'],

      'str_replace miss' => [[
        'search1' => 'replace2',
      ], [], 'stay the same', 'stay the same'],

      'Simple Regex'     => [[], [
        '~object\?slug=([\a-zA-Z]+)~i' => 'object/$1'
      ], 'object?slug=10', 'object/10'],

      'Chained replacement' => [[
        'search' => 'replace/path?id=15'
      ], [
        '~replace/path\?id=([\a-zA-Z]+)~i' => 'result/$1/correct'
      ], 'search', 'result/15/correct'],

      'Deep chained replacement' => [[
        'one'   => 'two',
        'two'   => 'three',
        'three' => 'four',
      ], [
        '~four\?number=([\a-zA-Z]+)~i' => 'four = $1'
      ], 'one?number=4', 'four = 4'],
    ];
  }

  /**
   * dataprovider pattern.
   * Final item in array is expected value.
   *
   * @since  0.0.1
   * @see    StringHelperTest::bleach_string
   * @return array
   */
  public function dataprovider__bleach_string() {
    $bad_string_array = json_decode(file_get_contents((dirname(dirname(__DIR__)) . '/bad_strings.json')), true);

    return [$bad_string_array];
  }

  /**
   * @since  0.0.1
   * @see    StringHelperTest::test_minify_string
   * @return void
   */
  public function dataprovider__minify_string() {
    return [
      'empty string'            => ['', ''],
      'empty string with space' => ['    ', ' '],
      'simple html'             => ['<a    href="http://website.com">Link</a>', '<a href="http://website.com">Link</a>'],
      'html divs'               => ['<div></div>      <div>        </div>', '<div></div> <div> </div>'],
    ];
  }

  /**
   * @since  0.0.1
   * @see    StringHelperTest::get_spaces_removed
   * @return void
   */
  public function dataprovider__get_spaces_removed() {
    return [
      'empty string'            => ['', ''],
      'empty string with space' => [' ', ''],
    ];
  }

  /**
   * dataprovider pattern.
   *
   * @since  0.0.1
   * @see    StringHelperTest::test_get_slug_from_url
   * @return array
   */
  public function dataprovider__get_slug_from_url() {
    return [
      'Empty strings'             => ['', '', '/'],
      'empty string with base'    => ['', 'http://website.com', '/'],
      'Base url'                  => ['http://website.com/', 'http://website.com', '/'],
      'Base url trailing slash'   => ['http://website.com', 'http://website.com', '/'],
      'Simple URL'                => ['http://website.com/path/to/asset', 'http://website.com', '/path/to/asset'],
      'Empty with query'          => ['https://website.com/?123', 'https://website.com', '/'],
      'Query parameter'           => ['http://website.com/sweet-slug?query=parameter', 'http://website.com', '/sweet-slug'],
      'Multiple query parameters' => ['https://website.com/only-this-please?query=parameter&second=thingy', 'https://website.com', '/only-this-please'],
    ];
  }

  /**
   * Handles both dashes and underscores.
   *
   * @since  0.0.1
   * @dataProvider dataprovider__slug_to_words
   * @covers StringHelper::slug_to_words
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $input
   * @param  string $expected
   * @return void
   */
  public function test_slug_to_words(string $input, string $expected) {
    $this->assertEquals($expected, StringHelper::slug_to_words($input));
  }

  /**
   * Tests removal of small, intermediate words.
   *
   * @since  0.0.1
   * @dataProvider dataprovider__slugify
   * @covers StringHelper::slugify
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $input
   * @param  string $expected
   * @return void
   */
  public function test_slugify(string $input, string $expected) {
    $this->assertEquals($expected, StringHelper::slugify($input));
  }

  /**
   * How do bad_strings.json fare in our string sanitizer?
   * Roughly ~507 bad strings as of 7.0.1
   *
   * @since  0.0.1
   * @dataProvider dataprovider__bleach_string
   * @covers StringHelper::bleach_string
   * @group  helpers
   * @group  unit
   * @small
   * @param  $input
   * @return void
   */
  public function test_bleach_string($input) {
    $this->assertInternalType('string', StringHelper::bleach_string($input));
  }

  /**
   * @since  0.0.1
   * @dataProvider dataprovider__minify_string
   * @covers StringHelper::minify_string
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $original
   * @param  string $expected
   * @return void
   */
  public function test_minify_string(string $original, string $expected) {
    $this->assertEquals($expected, StringHelper::minify_string($original));
  }

  /**
   * @since  0.0.1
   * @dataProvider dataprovider__get_spaces_removed
   * @covers StringHelper::get_spaces_removed
   * @group  helpers
   * @group  unit
   * @small
   * @return void
   */
  public function test_get_spaces_removed(string $original, string $expected) {
    $this->assertEquals($expected, StringHelper::get_spaces_removed($original));
  }

  /**
   * @since  0.0.1
   * @covers StringHelper::get_slug_from_url
   * @dataProvider dataprovider__get_slug_from_url
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $canonical
   * @param  string $url_root
   * @param  string $expected
   * @return void
   */
  public function test_get_slug_from_url(string $canonical, string $url_root, string $expected) {
    $this->assertEquals($expected, StringHelper::get_slug_from_url($canonical, $url_root));
  }

  /**
   * @since  0.0.1
   * @covers StringHelper::advanced_string_replace
   * @dataProvider dataprovider__advanced_string_replace
   * @group  helpers
   * @group  unit
   * @small
   * @param  array  $regular
   * @param  array  $regex
   * @param  string $original_string
   * @param  string $expected
   * @return void
   */
  public function test_advanced_string_replace(array $regular, array $regex, string $original_string, string $expected) {
    $this->assertEquals($expected, StringHelper::advanced_string_replace($regular, $regex, $original_string));
  }
}
