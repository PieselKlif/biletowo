<?php
if (!defined("ABSPATH")) die("Brak dostępu");

function get_event_page($img, $name, $artist, $event_time, $location, $event_time_info, $description, $lowest_price, $slug){
	$Parsedown = new Parsedown();
	?>
	<main id="event_page">
		<div class="head">
			<img src="<?= WebConf::$uploadDir . $img ?>" alt="Event image">
			<div>
				<h1><?= $name ?></h1>
				<h2><?= $artist ?></h2>
				<h3><?= $event_time ?></h3>
				<h4><?= $location ?></h4>
				<h5><?= $event_time_info ?></h5>
				<h6>od <?= $lowest_price ?> zł</h6>
				<div>
					<a href="/wydarzenia/<?= $slug ?>/buy">Kup Bilet</a>
				</div>
			</div>
		</div>
		<p><?= $Parsedown->text($description) ?></p>
	</main>

	<?php
}
?>