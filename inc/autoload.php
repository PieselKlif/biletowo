<?php
if (!defined("ABSPATH")) die("Brak dostępu");

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);
define('VIEWS_DIR', $_SERVER['DOCUMENT_ROOT'] . "/views/");
define('INC_DIR', $_SERVER['DOCUMENT_ROOT'] . "/inc/");
define('CONFIG_DIR', $_SERVER['DOCUMENT_ROOT'] . "/config/");

require INC_DIR . "/functions.php";
require INC_DIR . "/route.php";

require CONFIG_DIR . "/route-conf.php";