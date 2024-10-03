<?php include "./include/nav_klant.php"; ?>
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$klanten = $db->getKlanten();

// klanten
// ID INT
// Naam VARCHAR(50)
// Email VARCHAR(100)
// Telefoon VARCHAR(20)
// Wachtwoord VARCHAR(100)
// FKgebruikersrechtenID INT (foreign key)
// Gewijzigd TIMESTAMP

$id = -1;
$view = null;


$id = $_SESSION['id'];


if (isset($_POST['cancel'])) {
	home();
}

if (isset($_POST['save'])) {
	if ($_POST['wachtwoord'] == $_POST['wachtwoord2']) {
		$password = hash('sha256', $_POST['wachtwoord']);
		$db->setKlant($id, $_POST['naam'], $_POST['email'], $_POST['telefoon'], $password, null);
		home();
	} else {
		echo "<div class='alert alert-danger' role='alert'>
				<i class='fa fa-exclamation-triangle' aria-hidden='true'></i>
				<span class='sr-only'>Error:</span>
				Wachtwoorden komen niet overeen.
			</div>";
	}
}

if (isset($_POST['delete']) && isset($id)) {
	$db->deleteKlant($id);
	session_destroy();
	home();
}

function home()
{
	header('Location: boekingen');
	exit();
}
$klant = $db->getKlantByID($id);
?>
<h3>Mijn account wijzigen</h3>
<form action="" method="post">
	<div class="form-group mt-2">
		<label for="naam">Naam:</label>
		<input type='text' class='form-control' id='naam' name='naam' value='<?php echo $klant->getNaam(); ?>'>
	</div>
	<div class="form-group mt-2">
		<label for="adres">Emailadres:</label>
		<input type='email' class='form-control' id='email' name='email' value='<?php echo $klant->getEmail(); ?>'>
	</div>
	<div class="form-group mt-2">
		<label for="telefoon">Telefoon:</label>
		<input type='tel' class='form-control' id='telefoon' name='telefoon' value='<?php echo $klant->getTelefoon(); ?>'>
	</div>
	<div class="form-group mt-2">
		<label for="wachtwoord">Wachtwoord:</label>
		<input type='password' class='form-control' id='wachtwoord' name='wachtwoord' value=''>
	</div>
	<div class="form-group mt-2">
		<label for="wachtwoord2">Bevestig wachtwoord:</label>
		<input type='password' class='form-control' id='wachtwoord2' name='wachtwoord2' value=''>
	</div>
	<br />
	<div style="width: fit-content;">
		<button type="submit" name="save" class="btn btn-success float-start">Bewaren</button>
		<button type="submit" name="cancel" class="btn btn-primary float-end">Annuleren</button>
		<br />
		<button type="button" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger mt-4 mb-3">Verwijder mijn account</button>
	</div>
</form>
<div class="modal" id="deleteModal" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Verwijder account</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p>Weet u zeker dat u uw account wilt verwijderen?</p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary float-start" data-dismiss="modal">Annuleren</button>
				<form target="account.php" method="post"><button type="submit" name="delete" class="btn btn-danger float-end">Verwijder</button></form>
			</div>
		</div>
	</div>
</div>
<?php include "./include/footer.php"; ?>