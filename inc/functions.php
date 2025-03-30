<?php
define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT']);

function get_header() {
	require ROOT_DIR . "/views/header.php";
}

function get_footer() {
	require ROOT_DIR . "/views/footer.php";
}

function home() {
	require ROOT_DIR . "/views/home.php";
}

function is404() {
	require ROOT_DIR . "/views/404.php";
}