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

function get_artist($amount = 0) {
	require VIEWS_DIR . "artist.php";

	ob_start();

	if ($amount == 0){
		$query = "SELECT * FROM `artists` ORDER BY `id` DESC";
	} else {
		$query = "SELECT * FROM `artists` ORDER BY `id` DESC LIMIT $amount";
	}

	$response = DB::query($query);

	if (!empty($response)) {
		foreach ($response as $row) {
			artist($row['slug'], $row['image'], $row['name']);
		}
	}

	return ob_get_clean();
}

function get_venues($amount = 0) {
	require VIEWS_DIR . "venues.php";

	ob_start();

	if ($amount == 0){
		$query = "SELECT * FROM `venues` ORDER BY `id` DESC";
	} else {
		$query = "SELECT * FROM `venues` ORDER BY `id` DESC LIMIT $amount";
	}

	$response = DB::query($query);

	if (!empty($response)) {
		foreach ($response as $row) {
			venues($row['slug'], $row['image'], $row['name'], $row['city']);
		}
	}

	return ob_get_clean();
}

function get_page($name) {
	require VIEWS_DIR . "page.php";

	$response = DB::query("SELECT * FROM `site_config` WHERE `name` = :n", ['n' => $name]);

	if (!empty($response)) {
		page($response[0]['value']);
	}
}