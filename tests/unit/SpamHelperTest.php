<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\SpamHelper;
use PHPUnit\Framework\TestCase;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.0.0
 */
class SpamHelperTest extends TestCase {
  /**
   * @since  1.0.0
   * @see    SpamHelperTest::test_get_keyword_score
   * @return array
   */
  public function dataprovider__get_keyword_score() {
    return [
      'empty string'  => ['', 0],
      'Normal string' => ['Hey, looking for a quote. Please get back to me thanks.', 0],
      //'Spam string 1' => ['<a href=http://www.karapinargsk.com/js/cache.asp?str=160-Getropin-Price-Buy-Hygetropin-China-Riptropin-Results>Getropin Price</a> Examine',    12.625],
      //'Spam string 2' => ['<a href=http://www.fundrive.co.il/care/system.asp?z=354-Cheap-Uk-Viagra-For-Sale-Buy-Levitra-Uk-Cheap-Lovegra-Uk>Cheap Uk Viagra For Sale</a', 5.5],
      //'Spam 3'        => ['Invest $ 1,000 to earn $ 700,000 by the end of 2018. Only 100% of ICO insider information: http://top-5-ico.ml/?p=35156', 4],
    ];
  }

  /**
   * @since  1.0.0
   * @see    SpamHelperTest::test_has_html_tags
   * @return array
   */
  public function dataprovider__has_html_tags() {
    return [
      'empty string'  => ['', false],
      'Normal string' => ['Hey, looking for a quote. Please get back to me thanks.', false],
      'HTML String 1' => ['<a href=http://www.karapinargsk.com/js/cache.asp?str=160-Getropin-Price-Buy-Hygetropin-China-Riptropin-Results>Getropin Price</a> Examine',    true],
      'HTML string 2' => ['<a href=http://www.fundrive.co.il/care/system.asp?z=354-Cheap-Uk-Viagra-For-Sale-Buy-Levitra-Uk-Cheap-Lovegra-Uk>Cheap Uk Viagra For Sale</a', true],
    ];
  }

  /**
   * @since  1.0.0
   * @see    SpamHelperTest::test_is_email_blacklisted
   * @return array
   */
  public function dataprovider__is_email_blacklisted() {
    return [
      'empty string' => ['',                            false],
      'Normal email' => ['roger.lodge@gmail.com',       false],
      'Normal 2'     => ['ted.sexton@yahoo.ca',         false],
      'Spam email 1' => ['ttof46383@first.baburn.com',  true],
      'Spam email 2' => ['xomd64072@second.baburn.com', true],
      'Spam email 3' => ['rgub77797@rng.marvsz.com',    true],
      'Spam email 4' => ['amerpropas1967@seocdvig.ru',  true],
      'Spam email 5' => ['timothy@yahoo.ru',            true],
    ];
  }

  /**
   * @since  1.0.0
   * @dataProvider dataprovider__get_keyword_score
   * @covers SpamHelper::get_keyword_score
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $input
   * @param  int $expected
   * @return void
   */
  public function test_get_keyword_score(string $input, int $expected) {
    $this->assertEquals($expected, SpamHelper::get_keyword_score($input));
  }

  /**
   * @since  1.0.0
   * @dataProvider dataprovider__has_html_tags
   * @covers SpamHelper::has_html_tags
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $input
   * @param  bool $expected
   * @return void
   */
  public function test_has_html_tags(string $input, bool $expected) {
    $this->assertEquals($expected, SpamHelper::has_html_tags($input));
  }

  /**
   * @since  1.0.0
   * @dataProvider dataprovider__is_email_blacklisted
   * @covers SpamHelper::is_email_blacklisted
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $input
   * @param  bool $expected
   * @return void
   */
  public function test_is_email_blacklisted(string $input, bool $expected) {
    $this->assertEquals($expected, SpamHelper::is_email_blacklisted($input));
  }
}
