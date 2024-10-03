<?php include "include/head.php";

// php code for login form with bootstrap use input email and password fields

// klanten
// ID INT
// Naam VARCHAR(50)
// Email VARCHAR(100)
// Telefoon VARCHAR(20)
// Wachtwoord VARCHAR(100)
// Gewijzigd TIMESTAMP

// check for login submit
if (isset($_POST['email']) && isset($_POST['password'])) {
	// get email and password from form
	$email = $_POST['email'];
	// hash password
	$password = hash('sha256', $_POST['password']);

	// connect to database
	$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);

	// get klant by email
	$klant = $db->getKlantByEmail($email);

	// check if klant exists
	if ($klant) {
		// check if password is correct
		if ($klant->getWachtwoord() == $password) {
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
			$error = true;
		}
	} else {
		$error = true;
	}
}

// if user is logged in, redirect to index_page

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
	header('Location: ./');
	exit;
}

?>

<body style="width: 100vw; height: 100vh;">
	<!-- Login form with bootstrap use input email and password fields -->
	<div class="w-100 h-100 d-flex align-items-center justify-content-center">
		<div class="container">
			<div class="row">
				<div class="col-md-4 offset-md-4">
					<div class="card">
						<div class="card-body">
							<h3 class="card-title">Mijn Donkey Travel inloggen</h3>
							<form action="login" method="post">
								<!-- error -->
								<?php if (isset($error)) {
									echo '
									<div class="alert alert-danger mt-4" role="alert">
										<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
										<span class="sr-only">Error:</span>
										Wrong email or password
									</div>
									';
								} ?>
								<div class="form-group mt-2">
									<label for="email">Email:</label>
									<input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
								</div>
								<div class="form-group mt-2">
									<label for="password">Wachtwoord:</label>
									<input type="password" class="form-control" id="password" name="password" placeholder="Wachtwoord" required>
								</div>
								<div class="form-group mt-3">
									<button type="submit" class="btn btn-primary btn-block">Login</button>
								</div>
							</form>
							<a href="reset-password"><p class="mt-2">Wachtwoord vergeten?</p></a>
							<div class='alert alert-info mt-2' role='alert'>
								<i class='fa fa-exclamation-circle' aria-hidden='true'></i>
								<span class='sr-only'>Error:</span>
								<span>Nog geen account?</span>
								<a href="register"><button type="button" class="btn btn-link btn-block">Maak er hier eentje aan!</button></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>