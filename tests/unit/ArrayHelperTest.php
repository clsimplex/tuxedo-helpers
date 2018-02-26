<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.0.0 added test_get_attribute_string
 *               added test_with_swapped_keys
 *               added test_only_swapped_keys
 * @since  0.0.1
 */
class ArrayHelperTest extends TestCase {

  /**
   * @since  1.0.0
   * @see    ArrayHelperTest::test_get_attribute_string
   * @return array
   */
  public function dataprovider__get_attribute_string() {
    return [
      'all empty arrays' => [[], [], ''],
      'simple case'      => [['class' => 'class-one'], [], 'class="class-one"'],
      'two attributes'   => [['id' => 'id-one', 'class' => 'class-one'], [], 'id="id-one" class="class-one"'],
      'default case'     => [['class' => 'class-two'], ['class' => 'default-one'], 'class="class-two"'],
      'space case'       => [['placeholder' => 'placeholder value'], [], 'placeholder="placeholder value"'],
      'all cases'        => [['placeholder' => 'placeholder value', 'id' => 'id-one', 'class' => 'class-one'], ['class' => 'default'], 'class="class-one" placeholder="placeholder value" id="id-one"'],
      'http encoding'    => [['action' => 'http://website.com'], [], 'action="http://website.com"'],
      'http encoding brackets' => [['placeholder' => 'This value (that value)'], [], 'placeholder="This value (that value)"'],
      'form create bug'  => [[
        'action' => 'http://site.test/admin/files?parent_id=6',
        'name'   => 'form',
        'role'   => 'form',
        'target' => '_top'
      ], [], 'action="http://site.test/admin/files?parent_id=6" name="form" role="form" target="_top"'],
    ];
  }

  /**
   * @since  1.0.0 removed null array case - function only takes arrays.
   * @see    ArrayHelperTest::test_get_mapped_array
   * @return array
   */
  public function dataprovider__only_swapped_keys() {
    return [
      'all empty arrays'     => [[], [], []],
      'key_map empty'        => [[1 => 1], [], [1 => 1]],
      'simple mapping'       => [['a' => 'b'], [ 'a' => 'b' ], [ 'b' => 'b']],
      'no mapping'           => [['c' => 'b'], [ 'a' => 'b' ], []],
      'mapping keys removed' => [['c' => 'b', 'key' => 'value'], [ 'a' => 'b', 'key' => 'new_key' ], [ 'new_key' => 'value' ]],
    ];
  }

  /**
   * @since  1.0.0 Removed null array case - function only takes arrays.
   *               Renamed dataprovider.
   * @since  0.0.1
   * @see    ArrayHelperTest::test_only_swapped_keys
   * @return array
   */
  public function dataprovider__with_swapped_keys() {
    return [
      'all empty arrays'     => [[], [], []],
      'key_map empty'        => [[1 => 1], [], [1 => 1]],
      'simple mapping'       => [['a' => 'b'], [ 'a' => 'b' ], [ 'b' => 'b']],
      'no mapping'           => [['c' => 'b'], [ 'a' => 'b' ], ['c' => 'b']],
      'mapping keys removed' => [['c' => 'b', 'key' => 'value'], [ 'a' => 'b', 'key' => 'new_key' ], [ 'c' => 'b', 'new_key' => 'value' ]],
    ];
  }

  /**
   * dataprovider pattern.
   * Final item in array is expected value.
   *
   * @since  1.0.0 removed null case.
   * @since  0.0.1
   * @see    ArrayHelperTest::test_custom_key_exists
   * @return array
   */
  public function dataprovider__custom_key_exists() {
    return [
      'single key'                     => ['key', ['key' => true], true],
      'multiple keys'                  => [['key', 'second_key'], ['key' => true, 'second_key' => true], true],
      'multiple keys with one missing' => [['key', 'second_key'], ['key' => true], false],
      'null custom data'               => [[], [], false],
    ];
  }

  /**
   * dataprovider pattern.
   * Final item in array is expected value.
   *
   * @since  0.0.1
   * @see    ArrayHelperTest::test_array_difference
   * @return array
   */
  public function dataprovider__array_difference() {
    return [
      'empty arrays'              => [[],      [],      []],
      'simple difference'         => [[],      [1],     [1]],
      'simple difference 2'       => [[1],     [],      [1]],
      'empty result'              => [[1],     [1],     []],
      'array difference'          => [[1, 2, 4], [1, 2, 3], [3, 4]],
      'array difference ordering' => [[1, 4, 2], [3, 2, 1], [3, 4]],
    ];
  }

  /**
   * @since  0.0.1
   * @see    ArrayHelperTest::test_get_unique
   * @return array
   */
  public function dataprovider__get_unique() {
    return [
      'empty array'                         => [[], []],
      'simple numbers'                      => [[1,2,2,3], [1,2,3]],
      'simple numbers multiple duplicates'  => [[1,1,2,2,2,3,3,3,3], [1,2,3]],
      'mixed types'                         => [[1, '1'], [1,]],
      'mixed types 2'                       => [[4, '4', '3', 3, '2', 2, 2, 1, 'one'], [4, '3', '2', 1, 'one']],
      'mixed types 3'                       => [[1, '1.0'], [1]],
      'mixed types 3'                       => [[1, 1.0, 1.00], [1]],
      'tiny decimal difference'             => [[1, 1.0000000000000001], [1]],
      'strings'                             => [['one', 'one'], ['one']],
    ];
  }

  /**
   * @since  0.0.1
   * @see    ArrayHelperTest::test_remove_empty_strings
   * @return array
   */
  public function dataprovider__remove_empty_strings() {
    return [
      'empty array'       => [[], []],
      'numeric elements'  => [[1,2,3], [1,2,3]],
      'zero as a number'  => [[0,1,2], [0,1,2]],
      'remove to empty'   => [['', '', ''], []],
      'null values'       => [['', null], []],
      'empty with spaces' => [['  ', ' ', null, 4], [3 => 4]],
      'strings'           => [[' string ', '1', '2', null, '', ' '], ['string', '1', '2']],
    ];
  }

  /**
   * @since  1.0.0
   * @dataProvider dataprovider__get_attribute_string
   * @covers ArrayHelper::get_attribute_string
   * @group  helpers
   * @group  unit
   * @small
   * @param  array      $a
   * @param  array|null $b
   * @param  string     $expected
   * @return void
   */
  public function test_get_attribute_string(array $a, $b, string $expected) {
    $this->assertEquals($expected, ArrayHelper::get_attribute_string($a, $b));
  }

  /**
   * @since  1.0.0
   * @dataProvider dataprovider__with_swapped_keys
   * @covers ArrayHelper::with_swapped_keys
   * @group  helpers
   * @group  unit
   * @small
   * @param  array      $a
   * @param  array|null $b
   * @param  array      $expected
   * @return void
   */
  public function test_with_swapped_keys(array $a, $b, array $expected) {
    $this->assertEquals($expected, ArrayHelper::with_swapped_keys($a, $b));
  }

  /**
   * @since  1.0.0 get_mapped_array => only_swapped_keys
   * @since  0.0.1
   * @dataProvider dataprovider__only_swapped_keys
   * @covers ArrayHelper::only_swapped_keys
   * @group  helpers
   * @group  unit
   * @small
   * @param  array      $a
   * @param  array|null $b
   * @param  array      $expected
   * @return void
   */
  public function test_only_swapped_keys(array $a, $b, array $expected) {
    $this->assertEquals($expected, ArrayHelper::only_swapped_keys($a, $b));
  }

  /**
   * @since  0.0.1
   * @dataProvider dataprovider__custom_key_exists
   * @covers ArrayHelper::custom_key_exists
   * @group  helpers
   * @group  unit
   * @small
   * @param  mixed      $key
   * @param  array|null $array
   * @param  bool       $expected
   * @return void
   */
  public function test_custom_key_exists($key, $array, bool $expected) {
    $this->assertEquals($expected, ArrayHelper::custom_key_exists($key, $array));
  }

  /**
   * @since  0.0.1
   * @dataProvider dataprovider__array_difference
   * @covers ArrayHelper::array_difference
   * @group  helpers
   * @group  unit
   * @small
   * @param  array $array_one
   * @param  array $array_two
   * @param  array $expected
   * @return void
   */
  public function test_array_difference(array $array_one, array $array_two, array $expected) {
    $this->assertEquals($expected, ArrayHelper::array_difference($array_one, $array_two) );
  }

  /**
   * @since  0.0.1
   * @dataProvider dataprovider__get_unique
   * @covers ArrayHelper::get_unique
   * @group  helpers
   * @group  unit
   * @small
   * @param  array $array_one
   * @param  array $expected
   * @return void
   */
  public function test_get_unique(array $array_one, array $expected) {
    $this->assertEquals($expected, ArrayHelper::get_unique($array_one) );
  }

  /**
   * @since  0.0.1
   * @dataProvider dataprovider__remove_empty_strings
   * @covers ArrayHelper::remove_empty_strings
   * @group  helpers
   * @group  unit
   * @small
   * @param  array $array
   * @param  array $expected
   * @return void
   */
  public function test_remove_empty_strings(array $array, array $expected) {
    $this->assertEquals($expected, ArrayHelper::remove_empty_strings($array));
  }
}
