<?php
if (!defined("ABSPATH")) die("Brak dostępu");

function api_get_event_time($id){
	$res = DB::query("SELECT id, event_date, event_time FROM event_dates WHERE event_id = :i", ["i" => $id]);

	if (!empty($res)) {
		$grouped = [];

		foreach ($res as $row) {
			$date = $row['event_date'];
			$time = [
				"id" => $row['id'],
				"time" => substr($row['event_time'], 0, 5)
			];

			if (!isset($grouped[$date])) {
				$grouped[$date] = [
					'event_date' => $date,
					'event_times' => []
				];
			}
			$grouped[$date]['event_times'][] = $time;
		}

		$grouped = array_values($grouped);

		header('Content-Type: application/json');
		echo json_encode($grouped);
	} else {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode([
			'error' => 'Event not found'
		]);
	}
}

function api_get_event_tickets($id){
	$res = DB::query("SELECT id FROM ticket_prices WHERE event_id = :i", ["i" => $id]);

	if (!empty($res)) {
		$json = array("seats" => false, "tickets" => []);

		$res = DB::query("SELECT id, price, display_name FROM ticket_prices WHERE event_id = :i AND sector_id IS NULL", ["i" => $id]);

		if (!empty($res)){
			foreach ($res as $row) {
				$arra = array("id" => $row['id'], "price" => $row['price'], "name" => $row['display_name']);

				array_push($json['tickets'], $arra);
			}
		}

		$res = DB::query("SELECT id FROM ticket_prices WHERE event_id = :i AND sector_id IS NOT NULL", ["i" => $id]);

		if (!empty($res)) {
			$json['seats'] = true;
		}

		header('Content-Type: application/json');
		echo json_encode($json);
	} else {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode([
			'error' => 'Event not found'
		]);
	}
}

function api_get_venue_sectors($id) {
	$res = DB::query("SELECT id, name FROM sectors WHERE venue_id = :i", ["i" => $id]);

	if (!empty($res)) {
		$json = [];

		foreach ($res as $row) {
			$arra = array("id" => $row['id'], "name" => $row['name']);

			array_push($json, $arra);
		}

		header('Content-Type: application/json');
		echo json_encode($json);
	} else {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode([
			'error' => 'Venue not found'
		]);
	}
}

function api_get_venue_sector_rows($id) {
	$res = DB::query("SELECT id, number FROM `rows` WHERE sector_id = :i", ["i" => $id]);

	if (!empty($res)) {
		$json = [];

		foreach ($res as $row) {
			$arra = array("id" => $row['id'], "name" => $row['number']);

			array_push($json, $arra);
		} 

		header('Content-Type: application/json');
		echo json_encode($json);
	} else {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode([
			'error' => 'Sector not found'
		]);
	}
}

function api_get_seats($rid, $tid) {
	$res = DB::query("SELECT id, number FROM seats WHERE row_id = :i", ["i" => $rid]);

	if (!empty($res)) {
		$occupied = DB::query("SELECT ts.seat_id FROM ticket_seats ts JOIN tickets t ON ts.ticket_id = t.id WHERE t.date_id = :tid", ["tid" => $tid]);		

		$taken_ids = array_column($occupied, 'seat_id');

		$json = [];
	
		foreach ($res as $seat) {
			$json[] = [
				"id" => $seat['id'],
				"name" => $seat['number'],
				"aviable" => !in_array($seat['id'], $taken_ids)
			];
		}
	
		header('Content-Type: application/json');
		echo json_encode($json);
	} else {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode([
			'error' => 'Row not found'
		]);
	}
}

function api_get_ticket_price($eid, $sid) {
	$res = DB::query("SELECT ticket_prices.id, ticket_prices.price, ticket_types.name, ticket_types.color_hex FROM ticket_prices INNER JOIN ticket_types ON ticket_prices.ticket_type_id=ticket_types.id WHERE ticket_prices.event_id = :eid AND ticket_prices.sector_id = :sid;", ["eid" => $eid, "sid" => $sid]);

	if (!empty($res)) {
		header('Content-Type: application/json');
		echo json_encode($res);
	} else {
		http_response_code(404);
		header('Content-Type: application/json');
		echo json_encode([
			'error' => 'Ticket not found'
		]);
	}
}

function api_post_get_table_data($data) {
	usort($data['seats'], function($a, $b) {
		return $a['id'] - $b['id'];
	});

	$json = array("sum" => 0, "tickets" => [], "seats" => []);
	
	foreach($data['seats'] as $row){
		$res = DB::query("SELECT s.number AS seat, r.number AS row, sec.name AS sector, tp.price, tt.name AS type FROM seats s JOIN `rows` r ON s.row_id = r.id JOIN sectors sec ON r.sector_id = sec.id INNER JOIN ticket_prices tp ON tp.id = :tpid AND tp.sector_id = sec.id INNER JOIN ticket_types tt ON tt.id = tp.ticket_type_id WHERE s.id = :sid LIMIT 1;", ['tpid' => $row['type'], 'sid' => $row['id']]);
	
		if (!empty($res)){
			$json['sum'] += (float)$res[0]['price'];
			$json['seats'][] = $res[0];
		}
	}

	foreach($data['tickets'] as $row){
		if ($row['quantity'] != 0){
			$res = DB::query("SELECT display_name, price FROM ticket_prices WHERE id = :id", ["id" => $row['id']]);
	
			if (!empty($res)){
				$json['sum'] += (float)$res[0]['price'] * $row['quantity'];
				$fjs = array("name" => $res[0]["display_name"], "price" => $res[0]["price"], "sum" => number_format((float)$res[0]["price"] * $row['quantity'], 2, '.', ''), "quantity" => $row['quantity']);
				$json['tickets'][] = $fjs;
			}
		}
	}

	$json['sum'] = number_format($json['sum'], 2, '.', '');

	header('Content-Type: application/json');
	return json_encode($json);
}

function api_post_buy_ticket($data) {
	$json = $data;
	header('Content-Type: application/json');

	$res = DB::query("SELECT id FROM events WHERE id = :i", ["i" => $data['eid']]);

	if (empty($res)) {
		$json = array("success" => false, "message" => "Wydarzenie nie istnieje");
		echo json_encode($json);
		return;
	}

	$res = DB::query("SELECT id FROM event_dates WHERE id = :di AND event_id = :ei", ["di" => $data['time'], "ei" => $data['eid']]);

	if (empty($res)) {
		$json = array("success" => false, "message" => "Godzina nie istnieje");
		echo json_encode($json);
		return;
	}

	foreach ($data['seats'] as $row){
		$res = DB::query("SELECT ts.id FROM ticket_seats ts JOIN tickets t ON ts.ticket_id = t.id WHERE ts.seat_id = :sid AND t.event_id = :eid AND t.date_id = :did;", ["sid" => $row['id'], "eid" => $data['eid'], "did" => $data['time']]);
		
		if (!empty($res)) {
			$json = array("success" => false, "message" => "Jedno z miejsc jest zajęte");
			echo json_encode($json);
			return;
		}
	}
	
	// TODO nie sprawdza poprawności czy ticket_price istnieje i jest poprawny

	$ticket_number = "TICKET_" . base_convert(sha1(time() . $data['fname'] . $data['lname'] . $data['email']), 10, 36);

	$ticket_id = DB::insert("INSERT INTO tickets (ticket_number, email, first_name, last_name, event_id, date_id) VALUES (:ticketNum, :email, :fname, :lname, :eid, :tid)", ["ticketNum" => $ticket_number, "email" => $data['email'], "fname" => $data['fname'], "lname" => $data['lname'], "eid" => $data['eid'], "tid" => $data['time']]);

	foreach ($data['tickets'] as $row) {
		if ($row['quantity'] != 0) {
			DB::query("INSERT INTO ticket (ticket_price_id, ticket_id, quantity) VALUES (:tpi, :ti, :q)", ["tpi" => $row['id'], "ti" => $ticket_id, "q" => $row['quantity']]);
		}
	}

	foreach ($data['seats'] as $row) {
		DB::query("INSERT INTO ticket_seats (seat_id, ticket_id, ticket_price_id) VALUES (:si, :ti, :tpi)", ["si" => $row['id'], "ti" => $ticket_id, "tpi" => $row['type']]);
	}

	send_email($data);

	$json = array("success" => true);
	echo json_encode($json);
}