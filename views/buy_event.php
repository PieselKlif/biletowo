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
					<br><br>
					<label for="event-time">Wybierz godzinę:</label>
					<select id="event-time"></select>

					<div id="tickets"></div>

					<?php
					$res = DB::query("SELECT id FROM ticket_prices WHERE event_id = :i AND sector_id IS NOT NULL", ["i" => $erow['id']]);

					if (!empty($res)) {
					?>
					<div class="select-panel">
						<h3>Wybierz miejsca</h3>
						<label for="event-sector">Wybierz sektor</label>
						<select id="event-sector"></select>
						<label for="event-row">Wybierz rząd</label>
						<select id="event-row"></select>

						<h4>Wybierz typ biletu</h4>
						<div id="ticket-type"></div>

						<h4>Wybierz miejsca</h4>

						<div id="seat-selector"></div>
					</div>
					<?php
					}
					?>
				</div>

				<div class="ticket_details">
					<h2 class="he">2. Dane do biletów</h2>
					<p>Bilety zostaną wysłane na podany adres. <b>Upewnij się, że e-mail jest poprawny</b></p>
					<input type="email" name="email" id="email" placeholder="Adres e-mail">
					<p>Podaj prawdziwe dane. Mogą one być zweryfikowane podczas ukazywania biletu.</p>
					<input type="text" name="fname" id="fname" placeholder="Imię">
					<input type="text" name="lname" id="lname" placeholder="Nazwisko">
				</div>
				<div class="summary_panel">
					<h2 class="he">3. Podsumowanie</h2>
					<table id="summaryTable"></table>
					<p><b>IMIĘ</b> <span id="name"></span></p>
					<p>Data: <span id="date"></span></p>
					<p>Godzina: <span id="time"></span></p>
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