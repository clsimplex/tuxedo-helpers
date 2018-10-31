<?php

namespace CLSimplex\Tuxedo\Helpers;

use Carbon\Carbon;

/**
 * @author Levon Zadravec-Powell levon@clsimplex.com
 * @since  1.6.0
 */
class TestHelper {

  /**s
   * Constructs URIs for named and un-named routes.
   * Depending on the test, we fuzz the parameters differently,
   * so we can't do it here.
   *
   * @since  1.6.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @param  $route
   * @param  array  $parameters
   * @return string
   */
  public static function construct_uri($route, array $parameters ) {
    $name            = $route->getName();
    $constructed_uri = route($name, $parameters);

    if (empty($name)) {
      // If the route is not named, then we have to construct it manually.
      if ($route->hasParameters()) {
        foreach ($parameters as $key => $value) {
          $route->setParameter($key, $value);
        }
      }

      $constructed_uri = $route->uri();
    }

    return $constructed_uri;
  }

  /**
   * Gets you a junky array filled with total nonsense.
   *
   * @since  1.6.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @return array
   */
  public static function get_fuzzy_array() {
    $results = [];

    for ( $i = 0; $i < mt_rand(1,25); $i++ ) {
      $type = array_rand([
        'numeric'      => 'numeric',
        'date'         => 'date',
        'string'       => 'string',
        'bad_string'   => 'bad_string',
        'null'         => 'null',
        'empty_string' => 'empty_string',
        'one'          => 'one',
        'zero'         => 'zero',
        'bool'         => 'bool',
      ]);

      switch($type) {
        case 'numeric':
          $value = mt_rand(-5000,5000);
          break;
        case 'date':
          $value = static::get_random_date_string();
          break;
        case 'string':
          $value = str_random(mt_rand(0,50));
          break;
        case 'bad_string':
          $bad_strings = json_decode(file_get_contents(dirname(__DIR__) . '/Resources/bad_strings.json', true));
          $index       = array_rand($bad_strings);
          $value       = $bad_strings[$index];
          break;
        case 'null':
          $value = null;
          break;
        case 'empty_string':
          $value = '';
          break;
        case 'one':
          $value = 1;
        case 'zero':
          $value = 0;
          break;
        case 'bool':
          $value = (bool)rand(0,1);
          break;
      }

      $key = str_random(mt_rand(1,50));

      $results[$key] = $value;
    }

    return $results;
  }

  /**
   * @since  1.6.0
   * @return string
   */
  public static function get_random_date_string() {
    return Carbon::today()->subDays(rand(0, 100))->format('Y-m-d');
  }

  /**
   * Creates a dataProvider
   *
   * @since  1.6.0
   * @author Levon Zadravec-Powell levon@clsimplex.com
   * @see    \Tests\Integration\RoutesTest::dataprovider__routes
   * @param  $routes
   * @return array
   */
  public static function get_route_provider($routes) {
    $results = [];

    foreach ($routes as $route) {
      $name           = $route->getName();
      $path           = $route->uri();
      $methods        = $route->methods();
      $parameters     = $route->parameterNames();
      $parameter_data = [];

      foreach ($parameters as $param) {
        $parameter_data[$param] = str_random(rand(0,10));

        if (in_array($param, ['id', 'year', 'month', 'day'])) {
          $parameter_data[$param] = rand(-50,50);
        }

        if ($param === 'date') {
          $parameter_data[$param] = static::get_random_date_string();
        }
      }
      /*
       * We test each verb here.
       */
      foreach ($methods as $verb) {
        $post_data = [];

        if ($verb === 'POST') {
          $post_data = static::get_fuzzy_array();
        }

        $constructed_uri = static::construct_uri($route, $parameter_data);

        $results[$name . ' ' . $verb] = [$verb, $constructed_uri, $post_data];
      }
    }

    return $results;
  }
}
