<?php
if (!defined("ABSPATH")) die("Brak dostępu");

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

require __DIR__ . "/functions.php";
require __DIR__ . "/route.php";

require ROOT_DIR . "/config/route-conf.php";