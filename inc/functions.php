<?php
if (!defined("ABSPATH")) die("Brak dostępu");

function get_header() {
	require VIEWS_DIR . "/header.php";
}

function get_footer() {
	require VIEWS_DIR . "/footer.php";
}

function home() {
	require VIEWS_DIR . "/home.php";
}

function is404() {
	require VIEWS_DIR . "/404.php";
}