<?php
if ($_SERVER['REQUEST_METHOD'] != "POST"){
	die("Brak dostępu");
}

define("ABSPATH", true);

require $_SERVER['DOCUMENT_ROOT'] . "/inc/autoload.php";

$username = $_POST['username'];
$password = $_POST['password'];

$res = DB::query("SELECT password FROM users WHERE username = :u", ["u" => $username]);

if (empty($res)){
	die("Brak dostępu");
}

if (password_verify($password, $res[0]['password'])) {
	session_start();

	$_SESSION['login'] = true;
	header("Location: /admin/home.php");
	exit();
} else {
	die("Brak dostępu");
}