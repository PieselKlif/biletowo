<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}

define("ABSPATH", true);

require $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";
?>

<table>
	<tr>
		<th>Nazwa</th>
		<th>Zdjęcie</th>
		<th>Slug</th>
	</tr>
	<tr>
		<td>
			<input type="text" name="name" id="name" placeholder="Nazwa artysty" require>
		</td>
		<td>
			<input type="text" name="image" id="image" placeholder="Nazwa zdjęcia" require>
		</td>
		<td>
			<input type="text" name="slug" id="slug" placeholder="Slug" require>
		</td>
		<td>
			<button onclick="addVenue()">Dodaj</button>
		</td>
	</tr>
	<?php
	$res = DB::query("SELECT * FROM venues");

	foreach($res as $row) {
		?>
		<tr>
			<td><?= $row['name'] ?></td>
			<td><?= $row['image'] ?></td>
			<td><?= $row['slug'] ?></td>
			<td><button onclick="removeVenue(<?= $row['id'] ?>)" class="remove">Usuń</button></td>
		</tr>
		<?php
	}
	?>
</table>