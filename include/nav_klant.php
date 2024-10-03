<?php include "./include/head_klant.php"; ?>
<div class="container">
	<div class="crud-container">
		<div class="row">
			<h2 class="col-4">Mijn Donkey Travel</h2>
			<div class="col-8">
				<div class="float-end">
					<p class="my-0">Ingelogd als:</p>
					<p class="my-0 text-end fw-light"><?php echo $_SESSION['naam'] . " [" . $_SESSION['email'] . "]" . " (" . ($_SESSION['telefoon']) . ")"; ?></p>
				</div>
			</div>
		</div>
		<ul class="nav nav-tabs">
			<li class="nav-item">
				<a id="nav-klant-welkom" class="nav-link <?php if (endsWith(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "welkom")) echo "active\" aria-current=\"page"; ?>" href="welkom">Welkom</a>
			</li>
			<li class="nav-item">
				<a id="nav-klant-beheer" class="nav-link <?php if (endsWith(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "boekingen", "reserveren")) echo "active\" aria-current=\"page"; ?>" href="boekingen">Boekingen</a>
			</li>
			<li class="nav-item">
				<a id="nav-klant-account" class="nav-link <?php if (endsWith(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "account")) echo "active\" aria-current=\"page"; ?>" href="account">Account</a>
			</li>
			<li class="nav-item ms-auto">
				<a id="nav-klant-about" class="nav-link <?php if (endsWith(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "about")) echo "active\" aria-current=\"page"; ?>" href="about">About</a>
			</li>
			<li class="nav-item">
				<a id="nav-klant-contact" class="nav-link <?php if (endsWith(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "contact")) echo "active\" aria-current=\"page"; ?>" href="contact">Contact</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-danger" href="../logout">Logout</a>
			</li>
		</ul>
		<div class="crud-form row mx-0">