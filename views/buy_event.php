<?php
if (!defined("ABSPATH")) die("Brak dostępu");
function get_buy_site($erow, $vrow, $arow, $date) {
	?>
	<main id="buy">
		<p class="he">1. Wybór biletów</p>
		<div>
			<div class="col1">
				<img src="<?= WebConf::$uploadDir . $vrow['venue_map'] ?>" alt="Plan obiektu">
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
	</main>
	<?php
}
?>