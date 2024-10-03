<?php
//using the Database class
include_once './config.php';
include_once './database/database.php';

function endsWith($string, ...$endString)
{
	foreach ($endString as $end) {
		$len = strlen($end);
		if ($len == 0) {
			return true;
		}
		if (substr($string, -$len) === $end) {
			return true;
		}
	}
	return false;
}

//// check if user is on login page
//if (!endsWith($_SERVER['REQUEST_URI'], 'login.php') && !endsWith($_SERVER['REQUEST_URI'], 'register.php')) {
//	// check if the user is logged in
//	if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
//		// redirect to index_page
//		header('Location: ../login.php');
//		exit;
//	}
//	if ($_SESSION['rechten']['read'] == true) {
//		header('Location: ../boekingen');
//		exit;
//	}
//}

?>
<!-- crud+s main page styled by bootstrap -->
<!DOCTYPE html>
<html lang="nl">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Donkey Travel Klant</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
	<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/leaflet.css" />

	<link rel="stylesheet" href="../css/style.css">

</head>