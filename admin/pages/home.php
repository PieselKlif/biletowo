<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}
?>
<h1>Witaj na stronie głównej</h1>