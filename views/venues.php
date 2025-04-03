<?php if (!defined("ABSPATH")) die("Brak dostępu"); ?>
<?php function venues($slug = "pge-narodowy", $img = "narodowy.jpeg", $name = "PGE Narodowy", $city = "Warszawa") { ?>
<a href="<?= WebConf::$miejsca . $slug ?>" class="venues">
	<div style="background-image: url('<?= WebConf::$uploadDir . $img ?>');"></div>
	<p><?= $name ?></p>
	<p><?= $city ?></p>
</a>
<?php } ?>