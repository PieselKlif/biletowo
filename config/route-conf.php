<?php
if (!defined("ABSPATH")) die("Brak dostępu");

route('/', function () {
  home();
});

route('/404', function () {
  is404();
});