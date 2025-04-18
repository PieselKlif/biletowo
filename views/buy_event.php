<?php
if (!defined("ABSPATH")) die("Brak dostępu");
function get_buy_site($erow, $vrow, $arow) {
	// echo $erow['name'];
	// var_dump($erow);
	// var_dump($vrow);
	// var_dump($arow);
	?>
	<main id="buy">
		<p class="he">1. Wybór biletów</p>
		<div>
			<div class="col1">
				<img src="<?= WebConf::$uploadDir . $vrow['venue_map'] ?>" alt="Plan obiektu">
			</div>
			<div class="col2">
				
			</div>
		</div>
	</main>
	<?php
}
?>