<?php
if (!defined("ABSPATH")) die("Brak dostępu");

use Dompdf\Dompdf;
use Dompdf\Options;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

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

		$date = dateFormat(explode(" ", $eres[0]['event_time'])[0]);

		get_buy_site($eres[0], $vres[0], $ares[0], $date);
	} else {
		is404();
	}
}

function get_final_page() {
	require VIEWS_DIR . "final.php";
	final_page();
}

function send_email($data, $ticketID) {
	$res = DB::query("SELECT events.event_time_info, events.name AS event_name, venues.name AS venue_name, venues.city AS venue_city, artists.name AS artist_name FROM events INNER JOIN venues ON events.venue_id = venues.id INNER JOIN artists ON events.artist_id = artists.id WHERE events.id = :eid;", ["eid" => $data['eid']])[0];
	$tres = DB::query("SELECT event_date, event_time FROM event_dates WHERE id = :i", ["i" => $data['time']])[0];

	$timePart = explode(":", $tres['event_time']);
	$time = $timePart[0].":".$timePart[1];

	$datePart = explode("-", $tres['event_date']);
	$date = $datePart[2].".".$datePart[1].".".$datePart[0];

	$table_data = json_decode(api_post_get_table_data($data), true);

	$color = true;
	$table = "";

	foreach ($table_data['seats'] as $row) {
		if ($color) {
			$table .= '<tr style="background-color: #d9d9d9;">';
			$color = !$color;
		} else {
			$table .= '<tr style="background-color: #cdcdcd;">';
			$color = !$color;
		}

		$table .= '<td>Sektor <strong>'.$row['sector'].'</strong>, Rząd <strong>'.$row['row'].'</strong>, Miejsce <strong>'.$row['seat'].'</strong></td>
							<td style="text-align: center;"><strong>'.$row['type'].'</strong></td>
							<td style="text-align: right;"><strong>'.$row['price'].' zł</strong></td></tr>';
	}

	foreach ($table_data['tickets'] as $row) {
		if ($color) {
			$table .= '<tr style="background-color: #d9d9d9;">';
			$color = !$color;
		} else {
			$table .= '<tr style="background-color: #cdcdcd;">';
			$color = !$color;
		}

		$table .= '<td><strong>'.$row['name'].'</strong></td>
							<td style="text-align: center;"><strong>'.$row['quantity'].'x '.$row['price'].' zł</strong></td>
							<td style="text-align: right;"><strong>'.$row['sum'].' zł</strong></td></tr>';
	}

	if ($color) {
		$table .= '<tr style="background-color: #d9d9d9; border-top: 2px solid black;">';
		$color = !$color;
	} else {
		$table .= '<tr style="background-color: #cdcdcd; border-top: 2px solid black;">';
		$color = !$color;
	}

	$table .= '<tr style="background-color: #cdcdcd; border-top: 2px solid black;">
							<td colspan="2"><strong>Suma</strong></td>
							<td style="text-align: right;"><strong>'.$table_data['sum'].' zł</strong></td>
						</tr>';

	$html = <<<HTML
	<!DOCTYPE html>
	<html lang="pl">
	<head>
		<meta charset="UTF-8">
		<title>Bilet – {$res['event_name']}</title>
	</head>
	<body style="font-family: Arial, sans-serif; color: #000; background-color: #ffffff; padding: 20px; margin: 0;">
		<table width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; margin: auto;">
			<tr>
				<td style="padding-bottom: 20px;">
					<p style="font-size: 20px; margin-bottom: 5px;">Cześć <strong>{$data['fname']} {$data['lname']}</strong>!</p>
					<p style="font-size: 16px;">Oto twój bilet na <strong>{$res['event_name']}</strong></p>
				</td>
			</tr>
			<tr>
				<td style="font-size: 14px; padding-bottom: 15px;">
					<p>📅 Data: <strong>{$date}</strong></p>
					<p>⏰ Godzina: <strong>{$time}</strong></p>
					<p>📌 Miejsce: <strong>{$res['venue_name']}, {$res['venue_city']}</strong></p>
				</td>
			</tr>
			<tr>
				<td style="padding-bottom: 10px; font-size: 14px;">
					<p><strong>Podsumowanie Twojego zakupu:</strong></p>
					<table width="100%" border="0" cellpadding="8" cellspacing="0" style="border-collapse: collapse; font-size: 14px;">
						{$table}
					</table>
				</td>
			</tr>
			<tr>
				<td style="padding: 30px 0; font-size: 20px; text-align: center;">
					<p>Do zobaczenia!</p>
				</td>
			</tr>
			<tr>
				<td style="text-align: center; padding: 20px; border: 1px solid #000; border-radius: 12px; font-size: 18px;">
					<p>Zapisz załączony <strong>plik PDF z biletem</strong><br>
					na urządzeniu mobilnym lub go wydrukuj.</p>
				</td>
			</tr>
			<tr>
				<td style="text-align: center; font-size: 12px; color: #7a7252; padding-top: 30px;">
					<p>Jeśli masz pytania, skontaktuj się z nami: <a href="mailto:kontakt@biletowo.pl" style="color: #7a7252;">kontakt@biletowo.pl</a></p>
					<p style="margin-top: 10px;">
						<img src="http://{$_SERVER['HTTP_HOST']}/media/Logo.svg" alt="BILETOWO" width="34" style="vertical-align: middle; margin-right: 5px;">
						<span style="font-weight: bold; color: #000;">BILETOWO</span>
					</p>
				</td>
			</tr>
		</table>
	</body>
	</html>
	HTML;

	$qr = new QrCode($ticketID);
	$writer = new PngWriter();
	$result = $writer->write($qr);
	$qrBase64 = base64_encode($result->getString());
	$qrHtml = '<img src="data:image/png;base64,' . $qrBase64 . '" alt="Kod QR" />';

	$table = "";

	foreach($table_data['seats'] as $row) {
		$table .= '<tr>
					<td>Sektor <strong>'.$row['sector'].'</strong>, Rząd <strong>'.$row['row'].'</strong>, Miejsce <strong>'.$row['seat'].'</strong></td>
					<td><strong>'.$row['type'].'</strong></td></tr>';
	}

	foreach($table_data['tickets'] as $row) {
		$table .= '<tr>
					<td><strong>'.$row['name'].'</strong></td>
					<td><strong>'.$row['quantity'].'x</strong></td></tr>';
	}

	$pdf = <<<HTML
	<!DOCTYPE html>
	<html lang="pl">
	<head>
		<meta charset="UTF-8">
		<style>
			* {
				margin: 0;
				padding: 0;
				box-sizing: border-box;
			}

			body {
				font-family: DejaVu Sans;
				margin: 16px;
			}

			.info {
				margin-top: 10px;
			}

			.che {
				color: #7a7252;
				font-weight: bold;
			}

			td>span {
				margin-left: 10px;
			}

			footer {
				margin-top: 40px;
			}

			footer>strong {
				margin-top: 15px;
				display: block;
			}

			h1 {
				font-size: 2.4rem;
			}

			h2 {
				color: #7a7252;
				font-style: italic;
			}

			h3 {
				margin-top: 10px;
				font-weight: normal;
				font-size: 1.3rem;
			}
			
			h4 {
				font-weight: normal;
				font-size: 0.95rem;
			}

			h5 {
				font-weight: normal;
				margin-top: 15px;
				line-height: 1.25rem;
			}

			.table {
				margin-top: 25px;
			}

			.table>table {
				margin-top: 5px;
				width: 100%;
				background-color: #d9d9d9;
				border-collapse: collapse;
			}

			tr>td:last-child {
				float: right;
			}

			.table td {
				padding: 8px 10px;
			}

			.table>table tr:nth-child(2n) {
				background-color: #cdcdcd;
			}
		</style>
	</head>
	<body>
		<table style="width: 100%; margin-bottom: 10px;">
			<tr>
				<td>{$qrHtml}</td>
				<td>
					<h1>{$res['event_name']}</h1>
					<h2>{$res['artist_name']}</h2>
					<h3>{$date}, godz. {$time}</h3>
					<h4>{$res['venue_name']}, {$res['venue_city']}</h4>
					<h5>{$res['event_time_info']}</h5>
					<table class="info">
						<tr>
							<td>
								<strong>BILET NR</strong>
							</td>
							<td>
								<span class="che">{$ticketID}</span>
							</td>
						</tr>
						<tr>
							<td>
								<strong>IMIĘ</strong>
							</td>
							<td>
								<span class="che">{$data['fname']} {$data['lname']}</span>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>

		<div class="table">
			<strong>MIEJSCA</strong>
			<table>
				{$table}
			</table>
		</div>

		<footer>
			<p>Bilet należy przedstawić na bramie wejściowej. Bilet przedstawia osoba, na którą jest bilet przypisany. W przypadku biletów <strong>z ulgą</strong> osoba z takim biletem ma obowiązek przedstawienia dokumentu upoważniającego ją do takiego biletu.</p>
			<strong>UWAGA!</strong>
			<p>Pracownik może poprosić o przedstawienie biletu jeszcze raz na wejściu na <strong>sektor</strong>.</p>
		</footer>
	</body>
	</html>
	HTML;

	$options = new Options();
	$options->set('defaultFont', 'DejaVu Sans');

	$dompdf = new Dompdf($options);
	$dompdf->loadHtml($pdf);
	$dompdf->setPaper('A4', 'portrait');
	$dompdf->render();
	$pdfContent = $dompdf->output();

	$boundary = md5(time());

	$email = $data['email'];
	$subject = "Twój bilet na {$res['event_name']}";
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: Biletowo <no-reply@biletowo.pl>\r\n";
	$headers .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n";

	$message = "--{$boundary}\r\n";
	$message .= "Content-Type: text/html; charset=UTF-8\r\n";
	$message .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
	$message .= $html . "\r\n";

	$pdfEncoded = chunk_split(base64_encode($pdfContent));
	$message .= "--$boundary\r\n";
	$message .= "Content-Type: application/pdf; name=\"bilet.pdf\"\r\n";
	$message .= "Content-Transfer-Encoding: base64\r\n";
	$message .= "Content-Disposition: attachment; filename=\"bilet.pdf\"\r\n";
	$message .= "\r\n";
	$message .= $pdfEncoded . "\r\n";
	$message .= "--$boundary--";

	$send = mail($email, $subject, $message, $headers);

	if (!$send){
		echo "Błąd podczas wysyłania";
	}
}