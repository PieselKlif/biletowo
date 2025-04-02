<?php function artist($slug, $img, $name) { ?>
<a href="<?= webConf::$artusci . $slug ?>" class="artist">
	<div style="background-image: url('<?= webConf::$uploadDir . $img ?>');"></div>
	<p><?= $name ?></p>
</a>
<?php } ?>