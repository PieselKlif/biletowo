<?php
require __DIR__ . "/inc/autoload.php";

$routes = [];

route('/', function () {
  home();
});

route('/404', function () {
  is404();
});

function route(string $path, callable $callback) {
  global $routes;
  $routes[$path] = $callback;
}

function run() {
  global $routes;
  $uri = $_SERVER['REQUEST_URI'];
  $found = false;
  foreach ($routes as $path => $callback) {
    if ($path !== $uri) continue;

    $found = true;
    $callback();
  }

  if (!$found) {
    $notFoundCallback = $routes['/404'];
    $notFoundCallback();
  }
}

get_header();

run();

get_footer();