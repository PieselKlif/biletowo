<?php if (!defined("ABSPATH")) die("Brak dostępu"); ?>
<?php function artist($slug, $img, $name) { ?>
<a href="<?= WebConf::$artysci . $slug ?>" class="artist">
	<div style="background-image: url('<?= WebConf::$uploadDir . $img ?>');"></div>
	<p><?= $name ?></p>
</a>
<?php } ?>