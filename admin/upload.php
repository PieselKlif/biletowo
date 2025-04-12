<?php
session_start();

if (!isset($_SESSION['login'])) {
	die("Brak dostępu");
}

function convertToWebP($source, $destination, $quality = 80)
{
	$imageInfo = getimagesize($source);
	if ($imageInfo === false) return false;

	switch ($imageInfo['mime']) {
		case 'image/jpeg':
			$image = imagecreatefromjpeg($source);
			break;
		case 'image/png':
			$image = imagecreatefrompng($source);
			imagepalettetotruecolor($image);
			imagealphablending($image, false);
			imagesavealpha($image, true);
			break;
		case 'image/gif':
			$image = imagecreatefromgif($source);
			break;
		default:
			return false;
	}

	$success = imagewebp($image, $destination, $quality);
	imagedestroy($image);
	return $success;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
	$uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/media/upload/';
	if (!file_exists($uploadDir)) {
		mkdir($uploadDir, 0777, true);
	}

	$fileTmpPath = $_FILES['image']['tmp_name'];
	$originalName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
	$safeName = preg_replace('/[^a-zA-Z0-9-_]/', '_', $originalName);

	$randomHash = substr(md5(microtime()), 0, 5);

	$webpFileName = $safeName . '_' . $randomHash . '.webp';
	$webpFilePath = $uploadDir . $webpFileName;

	if (convertToWebP($fileTmpPath, $webpFilePath)) {
		header("Location: /admin/");
	} else {
		echo "Błąd konwersji pliku.";
	}
} else {
	echo "Nie wybrano pliku.";
}
