<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['name'])) {
	unlink($_SERVER['DOCUMENT_ROOT'] . '/media/upload/' . $_GET['name']);
}