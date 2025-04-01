<?php
if (!defined("ABSPATH")) die("Brak dostępu");

$routes = [];

route('/', function () {
  home();
});

route('/404', function () {
  is404();
});