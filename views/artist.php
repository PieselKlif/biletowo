<?php function artist($slug, $img, $name) { ?>
<a href="<?= WebConf::$artusci . $slug ?>" class="artist">
	<div style="background-image: url('<?= WebConf::$uploadDir . $img ?>');"></div>
	<p><?= $name ?></p>
</a>
<?php } ?>