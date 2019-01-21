<?php

namespace CLSimplex\Tuxedo\Helpers\tests\unit;

error_reporting(E_ALL);

use CLSimplex\Tuxedo\Helpers\SpamHelper;
use PHPUnit\Framework\TestCase;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.3.1 fixed float being cast to int via parameter typing issue
 * @since  1.0.0
 */
class SpamHelperTest extends TestCase {

  public function dataprovider__is_spam() {
    return [
      'empty fields'    => [['name' => '', 'message' => ''], ['name', 'message'], false], // ideally these never get submitted.
      'empty whitelist' => [['name' => '', 'message' => ''], [], false], // ideally the whitelist isn't messed up

      'real example 1'  => [[
        'name'    => 'RonnieRuine',
        'email'   => 'jamessnowden@yahoo.com',
        'subject' => 'Dating Hot Girls in your city',
        'message' => 'Find yourself a girl for an evening for sex in your city: https://xxxxxxx.com/hotgirls86332'
      ], ['name', 'email', 'subject', 'message'], true],

      'real example 2'  => [[
        'name'    => 'Emmettjix',
        'email'   => 'jonathanrainey14@gmail.com',
        'message' => 'Hot women for good sex every day: http://xxx.xx/bestadultdating17848',
      ], ['name', 'email', 'message'], true],

      'real example 3'  => [[
        'name'    => 'Matthewrix',
        'email'   => 'maxymiv@ua.fm',
        'message' => 'J\'ai 23 000 в‚¬. Comment l\'utiliser au mieux pour gagner plus d\'argent: http://xxx.io/bestinvestsystem13578',
      ], ['name', 'email', 'message'], true],

      'real example 4'  => [[
        'name'    => 'Joshuaamets',
        'email'   => 'denis47450@love.fr',
        'message' => 'Top cryptocurrencies to invest in 2019: http://wntdco.mx/5500094910',
      ], ['name', 'email', 'message'], true],
    ];
  }

  /**
   * @since  1.3.2 added new testcase.
   * @since  1.3.1 removing dickhead spammer urls
   * @since  1.0.0
   * @see    SpamHelperTest::test_get_keyword_score
   * @return array
   */
  public function dataprovider__get_keyword_score() {
    return [
      'empty string'  => ['', 0],
      'Normal string' => ['Hey, looking for a quote. Please get back to me thanks.', 0],
      'simple score'  => ['Check out this viagra', 2.5],
      'Spam string 1' => ['<a href=http://www.xxxxx.com/js/cache.asp?str=160-Getropin-Price-Buy-Hygetropin-China-Riptropin-Results>Getropin Price</a> Examine',    12.625],
      'Spam string 2' => ['<a href=http://www.xxxxx.il/care/system.asp?z=354-Cheap-Uk-Viagra-For-Sale-Buy-Levitra-Uk-Cheap-Lovegra-Uk>Cheap Uk Viagra For Sale</a', 5.5],
      'Spam 3'        => ['Invest $ 1,000 to earn $ 700,000 by the end of 2018. Only 100% of ICO insider information: http://top-5-ico.ml/?p=35156', 4.5],
      'Spam 4'        => ['#1 Online РЎasino: http://xx-xx.ru/xx/url=https://xx.cc/xxxxxx', 0], // To implement later.
      'Spam 5' => ['How To Make Money $200 Per Day (Payment Proof): http://shop.bsigroup.com/AffiliateRedirect.aspx?url=https://xx.cc/7pBiIJ', 0],
      'Spam 6' => ['Hello Downloads music club Dj\'s, mp3 private server. http://0daymusic.org/premium.php Best Regards, Robert', 0],
      'Spam 7' => ['J\'ai 23 000 в‚¬. Comment l\'utiliser au mieux pour gagner plus d\'argent: http://xxx.io/bestinvestsystem12579', 0.5],
    ];
  }

  /**
   * @since  1.3.1
   * @see    SpamHelperTest::test_get_russian_word_count
   * @return array
   */
  public function dataprovider__get_russian_word_count() {
    return [
      'Empty string'   => ['', 0],
      'English string' => ['This is a simple string in english. Check it out, yo.', 0],
      'Normal string'  => ['Hey, looking for a quote. Please get back to me thanks.', 0],
      'Obvious 1'      => ['Привет всем! Нашел удивительную информацию на этом сайте: http://xxxxx.ru : http://xxxxx.ru/xxxxx.html [b] Реконструкция стадиона "Лужники" [/b] [url=http://xxxxx.ru/xxxxx.html] Демотиваторы (30 фото) [/url] http://xxxxx.ru/xxxxx.html', 13],
      'Obvious 1.2'    => ['Woah Привет всем! Нашел удивительную информацию на этом сайте: http://xxxxx.ru : http://xxxxx.ru/xxxxx.html [b] Реконструкция стадиона "Лужники" [/b] [url=http://xxxxx.ru/xxxxx.html] Демотиваторы (30 фото) [/url] http://xxxxx.ru/xxxxx.html', 13],
      'Obvious 2'      => ['Привет всем участникам! Класный у вас сайт! Что скажете по поводу этих новостей?: http://xxxxx.ru/news/xxxxx.html [b] НАТО заявляет об активности ВВС РФ в воздушном пространстве Европы [/b] [url=http://xxxxx.ru/xxxxx.html] Сухопутные войска России получили первый дивизион зенитного ракетного комплекса "Бук-М3', 34],
    ];
  }

  /**
   * @since  1.3.1 removing dickhead spammer urls
   * @since  1.0.0
   * @see    SpamHelperTest::test_has_html_tags
   * @return array
   */
  public function dataprovider__has_html_tags() {
    return [
      'empty string'  => ['', false],
      'Normal string' => ['Hey, looking for a quote. Please get back to me thanks.', false],
      'HTML String 1' => ['<a href=http://www.xxxxx.com/js/cache.asp>Getropin Price</a> Examine',    true],
      'HTML string 2' => ['<a href=http://www.fxxxxx.co.il/care/system.asp>Cheap Uk Viagra For Sale</a', true],
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
   * @since  1.5.0
   * @dataProvider dataprovider__is_spam
   * @covers SpamHelper::is_spam
   * @group  helpers
   * @group  unit
   * @small
   * @param  array $input
   * @param  array $whitelist
   * @param  bool  $expected
   * @return void
   */
  public function test_is_spam(array $input, array $whitelist, bool $expected) {
    $this->assertEquals($expected, SpamHelper::is_spam($input, $whitelist));
  }

  /**
   * @since  1.3.1 removed parameter typing - fixing casting issue.
   * @since  1.0.0
   * @dataProvider dataprovider__get_keyword_score
   * @covers SpamHelper::get_keyword_score
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $input
   * @param  mixed  $expected
   * @return void
   */
  public function test_get_keyword_score(string $input, $expected) {
    $this->assertEquals($expected, SpamHelper::get_keyword_score($input));
  }

  /**
   * @since  1.3.1
   * @dataProvider dataprovider__get_russian_word_count
   * @covers SpamHelper::get_russian_word_count
   * @group  helpers
   * @group  unit
   * @small
   * @param  string $input
   * @param  mixed  $expected
   * @return void
   */
  public function test_get_russian_word_count(string $input, int $expected) {
    $this->assertEquals($expected, SpamHelper::get_russian_word_count($input));
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
