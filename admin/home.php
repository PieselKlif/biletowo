<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}
