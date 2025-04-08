<?php
function category($name, $content) {
	?>
	<main id="category">
		<h2><?= $name ?></h2>
	
		<section class="content">
			<div>
				<?= $content ?>
			</div>
		</section>
	</main>
	<?php
}