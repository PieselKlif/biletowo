<?php
if (!defined("ABSPATH")) die("Brak dostępu");

$routes = [];

function route(string $pattern, callable $callback) {
  global $routes;
  $routes[] = ['pattern' => $pattern, 'callback' => $callback];
}

function run_route() {
  global $routes;
  $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
  $found = false;

  foreach ($routes as $route) {
    $pattern = trim($route['pattern'], '/');
    $callback = $route['callback'];

    $regex = preg_replace('#:([\w]+)#', '([^/]+)', $pattern);
    if (preg_match('#^' . $regex . '$#', $uri, $matches)) {
      array_shift($matches);
      $found = true;
      $callback(...$matches);
      break;
    }
  }

  if (!$found) {
    foreach ($routes as $route) {
      if ($route['pattern'] === '/404') {
        $route['callback']();
        return;
      }
    }
    echo "404 Not Found";
  }
}
