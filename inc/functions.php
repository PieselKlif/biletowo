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

function get_event_site($slug){
	$res = DB::query("SELECT e.id, e.name, e.image, e.description, e.event_time_info, e.event_time, v.name AS venue_name, v.city, a.name AS artist_name FROM events e JOIN venues v ON e.venue_id = v.id JOIN artists a ON e.artist_id = a.id WHERE e.slug = :s", ["s" => $slug]);
	
	if (!empty($res)) {
		require VIEWS_DIR . "event_page.php";
		
		$row = $res[0];
		
		$id = $row['id'];
		$img = $row['image'];
		$name = $row['name'];
		$desc = $row['description'];
		$event_time_info = $row['event_time_info'];
		$event_time = $row['event_time'];
		$venue_name = $row['venue_name'];
		$city = $row['city'];
		$artist_name = $row['artist_name'];

		$date = dateFormat(explode(" ", $event_time)[0]);

		$res = DB::query("SELECT MIN(price) AS lowest_price FROM ticket_prices WHERE event_id = :i;", ["i" => $id]);

		$lowest_price = $res[0]['lowest_price'];

		$location = $venue_name . ", " . $city;
		get_event_page($img, $name, $artist_name, $date, $location, $event_time_info, $desc, $lowest_price, $slug);
	} else {
		is404();
	}
}

function dateFormat($data) {
	$months = [
		'01' => 'stycznia',
		'02' => 'lutego',
		'03' => 'marca',
		'04' => 'kwietnia',
		'05' => 'maja',
		'06' => 'czerwca',
		'07' => 'lipca',
		'08' => 'sierpnia',
		'09' => 'września',
		'10' => 'października',
		'11' => 'listopada',
		'12' => 'grudnia'
	];

	$dataObj = DateTime::createFromFormat('d.m.Y', $data);
	
	if (!$dataObj) {
		return 'Niepoprawny format daty';
	}

	$d = $dataObj->format('d');
	$m = $dataObj->format('m');
	$y = $dataObj->format('Y');

	return intval($d) . ' ' . $months[$m] . ' ' . $y;
}

function get_buy_page($slug) {
	$eres = DB::query("SELECT * FROM events WHERE slug = :s;", ["s" => $slug]);
	
	if (!empty($eres)){
		require VIEWS_DIR . "buy_event.php";
		$vres = DB::query("SELECT * FROM venues WHERE id = :i;", ["i" => $eres[0]['venue_id']]);
		$ares = DB::query("SELECT * FROM artists WHERE id = :i;", ["i" => $eres[0]['artist_id']]);

		get_buy_site($eres[0], $vres[0], $ares[0]);
	} else {
		is404();
	}

}
