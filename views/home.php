<?php
if (!defined("ABSPATH")) die("Brak dostępu");
?>

<main>
	<?php
	$response = DB::query("SELECT `value`, `name` FROM `site_config` WHERE `name` IN ('baner_image', 'baner_url')");

	$baner_image = "";
	$baner_url = "";

	if (!empty($response)) {
		foreach ($response as $row) {
			if ($row['name'] == 'baner_image') {
				$baner_image = $row['value'];
			} elseif ($row['name'] == 'baner_url') {
				$baner_url = $row['value'];
			}
		}
	}

	if (!empty($baner_image) && !empty($baner_url)) {
		?>
		<a class="banner" href="<?= WebConf::$wydarzenia . htmlspecialchars($baner_url); ?>">
			<img src="<?= WebConf::$uploadDir . htmlspecialchars($baner_image); ?>" alt="Baner">
		</a>
		<?php
	}
	?>

	<section>
		<h2>Polecane Wydarzenia</h2>
		<div>

		</div>
	</section>
	<section>
		<h2>Polecane Artyści</h2>
		<div>
			<?php get_artist(6); ?>
		</div>
	</section>
	<section>
		<h2>Polecane Miejsca</h2>
		<div>
			<?php get_venues(4); ?>
		</div>
	</section>
</main>