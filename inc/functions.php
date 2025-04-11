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

function get_event($amount = 0) {
	require VIEWS_DIR . "event.php";

	ob_start();

	if ($amount == 0){
		$query = "SELECT e.name, e.slug, e.image, e.event_time, v.name AS venue_name, v.city, a.name AS artist_name FROM events e JOIN venues v ON e.venue_id = v.id JOIN artists a ON e.artist_id = a.id ORDER BY e.id DESC";
	} else {
		$query = "SELECT e.name, e.slug, e.image, e.event_time, v.name AS venue_name, v.city, a.name AS artist_name FROM events e JOIN venues v ON e.venue_id = v.id JOIN artists a ON e.artist_id = a.id ORDER BY e.id DESC LIMIT $amount";
	}

	$response = DB::query($query);

	if (!empty($response)) {
		foreach ($response as $row) {
			event($row['slug'], $row['image'], $row['name'], $row['artist_name'], $row['event_time'], $row['venue_name'] . ", " . $row['city']);
		}
	}

	return ob_get_clean();
}

function get_event_by_artist($slug) {
	require VIEWS_DIR . "event.php";
	$res = DB::query("SELECT id, name FROM artists WHERE slug = :s", ["s" => $slug]);

	if (empty($res)) {
		category("", "Brak danych :(");
	} else {
		$venue = $res[0];

		$resp = DB::query("SELECT e.name, e.slug, e.image, e.event_time, v.name AS venue_name, v.city, a.name AS artist_name FROM events e JOIN venues v ON e.venue_id = v.id JOIN artists a ON e.artist_id = a.id WHERE e.artist_id = :i ORDER BY e.id DESC", ["i" => $venue['id']]);
		
		ob_start();

		foreach ($resp as $row) {
			event($row['slug'], $row['image'], $row['name'], $row['artist_name'], $row['event_time'], $row['venue_name'] . ", " . $row['city']);
		}

		$html = ob_get_clean();

		category($venue['name'], $html);
	}
}

function get_event_by_venues($slug) {
	require VIEWS_DIR . "event.php";
	$res = DB::query("SELECT id, name FROM venues WHERE slug = :s", ["s" => $slug]);

	if (empty($res)) {
		category("", "Brak danych :(");
	} else {
		$venue = $res[0];

		$resp = DB::query("SELECT e.name, e.slug, e.image, e.event_time, v.name AS venue_name, v.city, a.name AS artist_name FROM events e JOIN venues v ON e.venue_id = v.id JOIN artists a ON e.artist_id = a.id WHERE e.venue_id = :i ORDER BY e.id DESC", ["i" => $venue['id']]);
		
		ob_start();

		foreach ($resp as $row) {
			event($row['slug'], $row['image'], $row['name'], $row['artist_name'], $row['event_time'], $row['venue_name'] . ", " . $row['city']);
		}

		$html = ob_get_clean();

		category($venue['name'], $html);
	}
}

function get_page($name) {
	require VIEWS_DIR . "page.php";

	$response = DB::query("SELECT * FROM `site_config` WHERE `name` = :n", ['n' => $name]);

	if (!empty($response)) {
		page($response[0]['value']);
	}
}