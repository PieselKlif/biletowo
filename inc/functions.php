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
}