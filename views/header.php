<?php
if (!defined("ABSPATH")) die("Brak dostępu");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biletowo</title>
	<link rel="stylesheet" href="/src/css/main.min.css">
	<link rel="shortcut icon" href="/media/biletowo-export.svg" type="image/x-icon">
</head>
<body>
	<?php	session_start(); if(isset($_SESSION['login'])){ ?>
		<div style="background: #232329; padding: 10px 20px;">
			<span style="color:white;">Jesteś zalogowany</span>
			<a style="color: #ddd; margin: 0 7px;" href="/admin/home.php">Admin</a>
			<a style="color: #ddd; margin: 0 7px;" href="/admin/logout.php">Wyloguj</a>
		</div>
	<?php } ?>
	<header>
		<a href="/">
			<img src="/media/Logo.svg" alt="Logo">
		</a>
		<nav>
			<a href="/wydarzenia">Wydarzenia</a>
			<a href="/artysci">Artyści</a>
			<a href="/miejsca">Miejsca</a>
		</nav>
	</header>