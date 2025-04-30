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
					<div class="panel">
						<h3>Wybierz miejsca</h3>
						<label for="event-sector">Wybierz sektor:</label>
						<select id="event-sector">
							<option value="">C2</option>
							<option value="">C3</option>
							<option value="">C4</option>
							<option value="">D11</option>
						</select>
						<label for="event-sector">Wybierz sektor:</label>
						<select id="event-sector">
							<option value="">C2</option>
							<option value="">C3</option>
							<option value="">C4</option>
							<option value="">D11</option>
						</select>
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
		</script>
		<script src="/src/js/buy.js"></script>
	</main>
	<?php
}
?>