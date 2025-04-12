<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}

$path = $_SERVER['DOCUMENT_ROOT'] . '/media/upload/';
$files = array_diff(scandir($path), array('.', '..'));
?>

<form action="upload.php" method="post" enctype="multipart/form-data">
	<label>Wybierz obraz do przesłania:</label>
	<input type="file" name="image" accept="image/*" required>
	<input type="submit" value="Prześlij obraz">
</form>

<div class="content">
	<?php 
	foreach($files as $img){
		?>
		<section>
			<img src="/media/upload/<?= $img ?>">
			<p><?= $img ?></p>
			<button onclick="removeImg('<?= $img ?>')">Usuń zdjęcie</button>
		</section>
		<?php
	}
	?>
</div>