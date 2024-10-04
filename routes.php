<?php
session_start();

// In case one is using PHP 5.4's built-in server
$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
	return false;
}

// Include the Router class
// @note: it's recommended to just use the composer autoloader when working with other packages too
//require_once __DIR__ . '/../src/Bramus/Router/Router.php';

require_once 'vendor/autoload.php';

function base_url()
{
	$url = 'https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
	return $url;
}

function startWith($haystack, $needle)
{
	return substr($haystack, 0, strlen($needle)) === $needle;
}

// Create a Router
$router = new \Bramus\Router\Router();

// Custom 404 Handler
$router->set404(function () {
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
	echo '404, route not found!';
});

// custom 404
$router->set404('/test(/.*)?', function () {
	header($_SERVER['SERVER_PROTOCOL'] . ' 404 Not Found');
	echo '<h1><mark>404, route not found!</mark></h1>';
});

$router->before('GET|POST|PUT|DELETE', '/(.*)', function($page) {
	if ($page == 'login' || $page == 'register' || $page == 'reset-password' || $page == 'view' || startWith($page, 'api/') || startWith($page, 'gpx/')) {
		return;
	}
	if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] == false) {
		header('Location: ' . base_url() . 'login');
		exit;
	}
	if (startWith($page, 'docent/') || startWith($page, 'student/') || $page == 'logout') return;
	if ($_SESSION['rechten']['read'] == false) {
		if ($_SESSION['role'] == 'docent') {
			header('Location: docent/welkom');
			exit;
		} else {
			header('Location: student/welkom');
			exit;
		}
	}
});

$router->all('/boekingen', function () {
	include 'boekingen.php';
});

$router->all('/gasten', function () {
	include 'gasten.php';
});

$router->all('/studenten', function () {
	include 'studenten.php';
});

$router->all('/', function () {
	if ($_SESSION['rechten']['read'] == false) {
		if ($_SESSION['role'] == 'docent') {
			header('Location: docent/welkom');
			exit;
		} else {
			header('Location: student/welkom');
			exit;
		}
	}
	include 'index.php';
});

$router->all('/kaart', function () {
	include 'kaart.php';
});

$router->all('/login', function () {
	include 'login.php';
});

$router->all('/logout', function () {
	include 'logout.php';
});

$router->all('/reset-password', function () {
	// matrix looking thingy
	echo "<pre>";
	for ($i = 0; $i < 63; $i++) {
		for ($j = 0; $j < 230; $j++) {
			echo "*";
		}
		echo "\n";
	}
	echo "</pre>";
});

$router->all('/overnachtingsplaatsen_beheer', function () {
	include 'overnachtingsplaatsen_beheer.php';
});

$router->all('/pauzeplaatsen_beheer', function () {
	include 'pauzeplaatsen_beheer.php';
});

$router->all('/register', function () {
	include 'register.php';
});

$router->all('/beheerders', function () {
	include 'beheerders.php';
});

$router->all('/statussen', function () {
	include 'statussen.php';
});

$router->all('/tochten', function () {
	include 'tochten.php';
});

$router->all('/view', function () {
	include 'view.php';
});

$router->all('/docent/boekingen', function () {
	include 'docent/boekingen.php';
});

$router->all('/docent/account', function () {
	include 'docent/account.php';
});

$router->all('/docent/reserveren', function () {
	include 'docent/reserveren.php';
});

$router->all('/docent/welkom', function () {
	include 'docent/welkom.php';
});

$router->all('/docent/about', function () {
	include 'docent/about.php';
});

$router->all('/docent/contact', function () {
	include 'docent/contact.php';
});

$router->all('/student/welkom', function () {
	include 'student/welkom.php';
});

$router->all('/student/boekingen', function () {
	include 'docent/boekingen.php';
});

$router->get('api/markers.json', function () {
	include 'api/markers.php';
});

// Thunderbirds are go!
$router->run();

?>