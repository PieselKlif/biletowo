<?php define("ABSPATH", true);
session_start();
if(isset($_SESSION['login'])){ header("Location: /admin/home.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Biletowo - Admin</title>
	<link rel="stylesheet" href="/src/css/admin-login.min.css">
</head>
<body>
	<form action="/admin/login.php" method="post">
		<label for="username">Nazwa użytkownika</label>
		<input type="text" name="username" id="username">
		<label for="password">Hasło</label>
		<input type="password" name="password" id="password">
		<input type="submit" value="Zaloguj">
	</form>
</body>
</html>