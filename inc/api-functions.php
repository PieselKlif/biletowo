<?php
if (!defined("ABSPATH")) die("Brak dostępu");

function get_event_time($id){
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