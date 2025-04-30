<?php
if (!defined("ABSPATH")) die("Brak dostępu");

function api_get_event_time($id){
	$res = DB::query("SELECT event_date, event_time FROM event_dates WHERE event_id = :i", ["i" => $id]);

	if (!empty($res)) {
		$grouped = [];

		foreach ($res as $row) {
			$date = $row['event_date'];
			$time = $row['event_time'];

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