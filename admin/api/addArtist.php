<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}

define("ABSPATH", true);

require $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['name']) && isset($_GET['image']) && isset($_GET['slug'])) {
	DB::query("INSERT INTO artists (name, image, slug) VALUES (:n, :i, :s)", ["n" => $_GET['name'], "i" => $_GET['image'], "s" => $_GET['slug']]);
}