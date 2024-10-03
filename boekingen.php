<?php include "include/nav.php"; ?>
<!-- debug print database Boekingen -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$boekingen = $db->getBoekingen();

// boekingen
// ID INT
// StartDatum DATE
// PINCode INT
// FKtochtenID INT (foreign key)
// FKklantenID INT (foreign key)
// FKstatussenID INT (foreign key)

$id = -1;
$view = null;
if (isset($_GET['id']))
	$id = $_GET['id'];
if (isset($_GET['view']))
	$view = $_GET['view'];


if (isset($_POST['cancel'])) {
	home();
}

if (isset($_POST['delete']) && isset($_POST['id'])) {
	$db->deleteBoeking($_POST['id']);
	home();
}

if (isset($_POST['save'])) {
	$db->setBoeking($_POST['id'], $_POST['startDatum'], null, $_POST['tochtID'], $_POST['klantID'], $_POST['statusID'], null);
	home();
}

function home()
{
	header('Location: boekingen');
	exit();
}

switch ($view) {
	case 'edit':
		$boeking = $db->getBoekingByID($id);
?>
		<h3>Boeking wijzigen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $boeking->getID(); ?>">
			<div class="form-group mt-2">
				<label for="startdatum">Startdatum:</label>
				<input value="<?php echo $boeking->getStartdatum(); ?>" name="startDatum" type="date" class="form-control" id="startdatum" required>
			</div>
			<div class="form-group mt-2">
				<label for="status">Status:</label>
				<select class="form-select" aria-label="Select status" name="statusID">
					<?php foreach ($db->getStatussen() as $status) { ?>
						<option value="
							<?php echo $status->getID(); ?>" <?php if ($status->getID() == $boeking->getStatus()->getID()) echo "selected"; ?>>
							<?php echo $status->getStatus(); ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group mt-2">
				<input type="hidden" name="pinCode" value="<?php echo $boeking->getPinCode(); ?>">
				<label for="pincode">PIN code:</label>
				<?php
				if ($boeking->getPincode() == null) {
					echo "<input type='text' class='form-control' id='pincode' value='Geen PIN code uitgegeven.' disabled>";
				} else {
					echo "<input type='text' class='form-control' id='pincode' value='" . $boeking->getPincode() . "' disabled>";
				}
				?>
			</div>
			<div class="form-group mt-2">
				<label for="klant">Klant:</label>
				<select name="klantID" class="form-select" aria-label="Select klant">
					<?php foreach ($db->getKlanten() as $klant) {
						if ($klant->getNaam() == "admin") continue;
					?>
						<option value="
								<?php echo $klant->getID(); ?>" <?php if ($klant->getID() == $boeking->getKlant()->getID()) echo "selected"; ?>>
							<?php echo $klant->getNaam() . " - " . $klant->getEmail() . " - " . $klant->getTelefoon(); ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<div class="form-group mt-2">
				<label for="tocht">Tocht:</label>
				<select name="tochtID" class="form-select" aria-label="Select tocht">
					<?php foreach ($db->getTochten() as $tocht) { ?>
						<option value="
								<?php echo $tocht->getID(); ?>" <?php if ($tocht->getID() == $boeking->getTocht()->getID()) echo "selected"; ?>>
							<?php echo $tocht->getOmschrijving() . " (" . $tocht->getAantalDagen() . " dagen)"; ?>
						</option>
					<?php } ?>
				</select>
			</div>
			<br />
			<button name="save" type="submit" class="btn btn-success">Bewaren</button>
			<button name="cancel" type="submit" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'delete':
		$boeking = $db->getBoekingByID($id);
	?>
		<h3>Boeking verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" id="ID" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="startdatum">Startdatum:</label>
				<input value="<?php echo $boeking->getStartdatum(); ?>" type="date" class="form-control" id="startdatum" disabled required>
			</div>
			<div class="form-group mt-2">
				<label for="status">Status:</label>
				<input value="<?php echo $boeking->getStatus()->getStatus(); ?>" type="text" class="form-control" id="status" disabled>
			</div>
			<div class="form-group mt-2">
				<label for="klant">Klant:</label>
				<input value="<?php echo $boeking->getKlant()->getNaam(); ?>" type="text" class="form-control" id="klant" disabled>
			</div>
			<div class="form-group mt-2">
				<label for="emailTelefoon">Email/Telefoon:</label>
				<input value="<?php echo $boeking->getKlant()->getEmail() . " - " . $boeking->getKlant()->getTelefoon(); ?>" type="text" class="form-control" id="emailTelefoon" disabled>
			</div>
			<div class="form-group mt-2">
				<label for="tocht">Tocht:</label>
				<input value="<?php echo $boeking->getTocht()->getOmschrijving(); ?>" type="text" class="form-control" id="tocht" disabled>
			</div>
			<br />
			<button name="delete" type="submit" class="btn btn-danger">Verwijderen</button>
			<button name="cancel" type="submit" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	default:
	?>
		<h3>Database Boekingen</h3>
		<table>
			<tr>
				<th>Startdatum</th>
				<th>Einddatum</th>
				<th>Status</th>
				<th>Pincode</th>
				<th>Klantnaam</th>
				<th>Tocht</th>
				<th>Email</th>
				<th>Telefoon</th>
				<th class="d-flex justify-content-center"><button class="btn btn-primary min-height-0 btn-sm" onClick='window.location.reload();'><i class='fa-solid fa-arrow-rotate-right'></i></button></th>
			</tr>
	<?php
		foreach ($boekingen as $boeking) {
			echo "<tr>";
			echo "<td>" . $boeking->getStartdatum() . "</td>";
			echo "<td>" . date('Y-m-d', strtotime($boeking->getStartdatum() . ' + ' . $boeking->getTocht()->getAantalDagen() . ' days')) . "</td>";
			echo "<td>" . $boeking->getStatus()->getStatus() . "</td>";
			echo "<td>" . $boeking->getPINCode() . "</td>";
			echo "<td>" . $boeking->getKlant()->getNaam() . "</td>";
			echo "<td>" . $boeking->getTocht()->getOmschrijving() . "</td>";
			echo "<td>" . $boeking->getKlant()->getEmail() . "</td>";
			echo "<td>" . $boeking->getKlant()->getTelefoon() . "</td>";
			echo "<td class='px-0 d-flex justify-content-center'>
				<a class='mx-1' href='pauzeplaatsen_beheer?id={$boeking->getID()}'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pause'></i></button></a>
				<a class='mx-1' href='overnachtingsplaatsen_beheer?id={$boeking->getID()}'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-bed'></i></button></a>";
			if ($_SESSION['rechten']['update']) echo "<a class='mx-1' href='?id={$boeking->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>";
			if ($_SESSION['rechten']['delete']) echo "<a class='mx-1' href='?id={$boeking->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>";
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
}
	?>
	<?php include "include/footer.php"; ?>