<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biletowo - admin</title>
	<link rel="stylesheet" href="/src/css/admin.min.css">
	<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
	<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
	<script src="/src/js/admin.js" defer></script>
</head>
<body>
	<div class="sidebar collapsed">
    <div class="toggle-btn" onclick="toggleSidebar()">☰</div>
    <ul>
			<li>
				<a data-target="home" class="active">
					<ion-icon name="home-outline"></ion-icon> <span>Home</span>
				</a>
			</li>
			<li>
				<a data-target="media">
				<ion-icon name="images-outline"></ion-icon> <span>Media</span>
				</a>
			</li>
			<li>
				<a data-target="test2">
					<ion-icon name="home-outline"></ion-icon> <span>Test2</span>
				</a>
			</li>
			<li>
				<a href="/admin/logout.php">
					<ion-icon name="log-out-outline"></ion-icon> <span>Wyloguj</span>
				</a>
			</li>
    </ul>
	</div>
	<main>
	</main>
</body>
</html>