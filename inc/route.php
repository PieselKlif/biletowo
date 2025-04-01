<?php
if (!defined("ABSPATH")) die("Brak dostępu");

function route(string $path, callable $callback) {
  global $routes;
  $routes[$path] = $callback;
}

function run_route() {
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