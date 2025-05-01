<?php
if (!defined("ABSPATH")) die("Brak dostępu");
function get_buy_site($erow, $vrow, $arow, $date) {
	?>
	<main id="buy">
		<h2 class="he">1. Wybór biletów</h2>
		<div class="main">
			<div class="col1">
				<img src="<?= WebConf::$uploadDir . $vrow['venue_map'] ?>" alt="Plan obiektu">
				<div class="tickets_panel">
					<label for="event-date">Wybierz datę:</label>
					<select id="event-date"></select>
					
					<label for="event-time">Wybierz godzinę:</label>
					<select id="event-time"></select>

					<div id="tickets"></div>

					<?php
					$res = DB::query("SELECT id FROM ticket_prices WHERE event_id = :i AND sector_id IS NOT NULL", ["i" => $erow['id']]);

					if (!empty($res)) {
					?>
					<div class="selected">
						<h3>Wybierz miejsca</h3>
						<label for="event-sector">Wybierz sektor</label>
						<select id="event-sector"></select>
						<label for="event-row">Wybierz rząd</label>
						<select id="event-row">
							<option value="">1</option>
							<option value="">2</option>
							<option value="">3</option>
							<option value="">4</option>
						</select>

						<h4>Wybierz typ biletu</h4>
						<div class="ticket-type">
							<button class="selected-ticket">Normalny<br>225.12 zł</button>
							<button>Ulgowy<br>183.51 zł</button>
						</div>

						<h4>Wybierz miejsca</h4>

						<div class="seat-selector">
							<div class="seat available">1</div>
							<div class="seat available">2</div>
							<div class="seat occupied">3</div>
							<div class="seat available">4</div>
							<div class="seat available">5</div>
							<div class="seat available">6</div>
							<div class="seat occupied">7</div>
							<div class="seat occupied">8</div>
							<div class="seat available">9</div>
							<div class="seat available">10</div>
							<div class="seat available">11</div>
							<div class="seat available">12</div>
							<div class="seat available">13</div>
							<div class="seat available">14</div>
							<div class="seat available">15</div>
							<div class="seat available">16</div>
							<div class="seat available">17</div>
							<div class="seat available">18</div>
						</div>
					</div>
					<?php
					}
					?>
			</div>
			</div>
			<div class="col2">
				<div>
					<img src="<?= WebConf::$uploadDir . $erow['image'] ?>">
					<div>					
						<h1><?= $erow['name'] ?></h1>
						<h2><?= $arow['name'] ?></h2>
						<h3><?= $date ?></h3>
						<h4><?= $vrow['name'] . ", " . $vrow['city'] ?></h4>
						<h5><?= $erow['event_time_info'] ?></h5>
					</div>
				</div>
			</div>
		</div>
		<script>
			eid = <?= $erow['id'] ?>;
			vid = <?= $vrow['id'] ?>;
		</script>
		<script src="/src/js/buy.js"></script>
	</main>
	<?php
}
?>