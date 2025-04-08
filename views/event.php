<?php if (!defined("ABSPATH")) die("Brak dostępu"); ?>
<?php function event($slug, $img, $name, $artist, $time, $venue) { ?>
<a href="<?= WebConf::$wydarzenia . $slug ?>" class="event">
	<div style="background-image: url('<?= WebConf::$uploadDir . $img ?>');"></div>
	<p><?= $name ?></p>
	<p><?= $artist ?></p>
	<p><?= $time ?></p>
	<p><?= $venue ?></p>
</a>
<?php } ?>