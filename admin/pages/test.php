<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}
?>
<h1>To jesst testowa strona 1</h1>