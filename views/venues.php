<?php if (!defined("ABSPATH")) die("Brak dostępu"); ?>
<?php function venues($slug, $img, $name, $city) { ?>
<a href="<?= WebConf::$miejsca . $slug ?>" class="venues">
	<div style="background-image: url('<?= WebConf::$uploadDir . $img ?>');"></div>
	<p><?= $name ?></p>
	<p><?= $city ?></p>
</a>
<?php } ?>