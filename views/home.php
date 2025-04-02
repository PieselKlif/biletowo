<?php
if (!defined("ABSPATH")) die("Brak dostępu");
?>

<main>
	<?php
	$result = DB::query("SELECT `value`, `name` FROM `site_config` WHERE `name` IN ('baner_image', 'baner_url')");

	$baner_image = "";
	$baner_url = "";

	if (!empty($result)) {
		foreach ($result as $row) {
			if ($row['name'] == 'baner_image') {
				$baner_image = $row['value'];
			} elseif ($row['name'] == 'baner_url') {
				$baner_url = $row['value'];
			}
		}
	}

	if (!empty($baner_image) && !empty($baner_url)) {
		?>
		<a href="<?= webConf::$wydarzenia . htmlspecialchars($baner_url); ?>">
			<img src="<?= webConf::$uploadDir . htmlspecialchars($baner_image); ?>" alt="Baner">
		</a>
		<?php
	}
	?>

	<section>
		<h2>Polecane Wydarzenia</h2>
	</section>
	<section>
		<h2>Polecane Artyści</h2>
	</section>
	<section>
		<h2>Polecane Miejsca</h2>
	</section>
</main>