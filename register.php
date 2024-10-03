<?php

use database\Klant;

include "include/head.php";

?>

<body style="width: 100vw; height: 87vh;">

	<?php

	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
		header('Location: ./');
		exit;
	}

	// php code for registration form with bootstrap use input name, email, password, password confirmation and phone number fields

	// klanten
	// ID INT
	// Naam VARCHAR(50)
	// Email VARCHAR(100)
	// Telefoon VARCHAR(20)
	// Wachtwoord VARCHAR(100)
	// Gewijzigd TIMESTAMP


	// if form is submitted
	if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['wachtwoord']) && isset($_POST['phone'])) {
		if ($_POST['wachtwoord'] == $_POST['wachtwoord2']) {
			// create a new klant object
			$password = hash('sha256', $_POST['wachtwoord']);
			$klant = new Klant(null, $_POST['name'], $_POST['email'], $_POST['phone'], $password, 0, null);
			// save to database
			$klant->save();
			// show success message
			echo '<div class="alert alert-success" role="alert">
			Account aangemaakt
			</div>';

			// set session variables
			$_SESSION['loggedin'] = true;
			$_SESSION['id'] = $klant->getID();
			$_SESSION['naam'] = $klant->getNaam();
			$_SESSION['email'] = $klant->getEmail();
			$_SESSION['telefoon'] = $klant->getTelefoon();
			$_SESSION['rechten'] = $klant->getGebruikersrechten()->getPermissions();

			// redirect to index_page
			header('Location: ./');
			exit;
		} else {
			$passError = true;
		}
	} else if (isset($_POST['register'])) {
		$error = true;
	}

	?>

	<div class="w-100 h-100 d-flex align-items-center justify-content-center">
		<!-- register register form with bootstrap use input name, email, password, password confirmation and phone number fields -->
		<div class="container">
			<div class="row">
				<div class="col-md-4 offset-md-4">
					<div class="card">
						<div class="card-body">
							<h3 class="card-title text-center">Donkey Travel account aanvragen</h3>
							<form action="register" name="register" method="post">
								<?php
								if (isset($error)) {
									echo '<div class="alert alert-info mt-3" role="alert">
										Vul uw gegevens in
									</div>';
								}
								else if (isset($passError)) {
									echo "<div class='alert alert-danger mt-3' role='alert'>
									<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>
									<span class='sr-only'>Error:</span>
									Wachtwoorden komen niet overeen.
								</div>";
								}
								?>
								<div class="form-group mt-2">
									<label for="name">Naam:</label>
									<input type="text" class="form-control" id="name" name="name" placeholder="Naam" required>
								</div>
								<div class="form-group mt-2">
									<label for="email">Email:</label>
									<input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
								</div>
								<div class="form-group mt-2">
									<label for="wachtwoord">Wachtwoord:</label>
									<input type='password' class='form-control' id='wachtwoord' name='wachtwoord' placeholder='Wachtwoord' value=''>
								</div>
								<div class="form-group mt-2">
									<label for="wachtwoord2">Bevestig wachtwoord:</label>
									<input type='password' class='form-control' id='wachtwoord2' name='wachtwoord2' placeholder='Bevestig wachtwoord' value=''>
								</div>
								<div class="form-group mt-2">
									<label for="phone">Telefoonnummer:</label>
									<input type="text" class="form-control" id="phone" name="phone" placeholder="Telefoonnummer" required>
								</div>
								<div class="form-group mt-3">
									<button name="register" type="submit" class="btn btn-success btn-block">Aanvragen</button>
									<a href="login"><button type="button" class="btn btn-primary btn-block">Annuleren</button></a>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>