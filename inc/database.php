<?php
if (!defined("ABSPATH")) die("Brak dostępu");

// CODE FROM https://github.com/litys/php-rose/blob/main/config/database.php

class DB {
	private static $pdo = null;

	private static function connect() {
		if (!self::$pdo) {
			$host = DBConf::$host;
			$db = DBConf::$db;
			$user = DBConf::$user;
			$password = DBConf::$password;

			self::$pdo = new PDO('mysql:host='.$host.';dbname='.$db, $user, $password);
			self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}

		return self::$pdo;
	}

	public static function query($query, $params = array()) {
		$stmt = self::connect()->prepare($query);
		$stmt->execute($params);

		if (strtoupper(explode(' ', trim($query))[0]) === 'SELECT') {
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		return $stmt;
	}

	public static function insert($query, $params = array()) {
		self::query($query, $params);
		return self::connect()->lastInsertId();
	}
}
