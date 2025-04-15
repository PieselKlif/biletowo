<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}

define("ABSPATH", true);

require $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
	DB::query("DELETE FROM artists WHERE id = :i", ["i" => $_GET['id']]);
}