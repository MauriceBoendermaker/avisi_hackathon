<?php

use database\Boeking;

include "./include/nav_klant.php"; ?>
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);

$id = -1;
$view = null;
if (isset($_GET['id']))
	$id = $_GET['id'];
if (isset($_GET['view']))
	$view = $_GET['view'];

if (isset($_POST['cancel']))
	home();

$boeking = $db->getBoekingByID($id);
if (isset($_POST['save']) || isset($_POST['delete']) || isset($_POST['reset']) || isset($_POST['setPin'])) {
	if (isset($boeking) && $boeking->getKlant()->getID() != $_SESSION['id']) home();
	if (isset($boeking)) {
		if (isset($_POST['save'])) {
			if ($_POST['startDatum'] < date("Y-m-d")) $error = true;
			$db->setBoeking($boeking->getID(), $_POST['startDatum'], $boeking->getPincode(), $_POST['tochtID'], $boeking->getKlant()->getID(), $boeking->getStatus()->getID(), null);
		} else if (isset($_POST['delete'])) {
			$tracker = $boeking->getTracker();
			if (!is_null($tracker))
				$db->deleteTracker($tracker->getID());
			$db->deleteBoeking($boeking->getID());
		} else {
			if (!is_null($boeking->getTracker()))
				$db->deleteTracker($boeking->getTracker()->getID());
		}
	} else if (isset($_POST['id'])) {
		$boeking = $db->getBoekingByID($_POST['id']);
		if (!is_null($boeking) && $boeking->getKlant()->getID() == $_SESSION['id'] && $boeking->getStatus()->getStatusCode() == 20 && is_null($boeking->getPINCode())) {
			$trackerID = $db->setTracker(null, intval($_POST['pin']), 0, 0, 0);
			$db->setBoeking($boeking->getID(), $boeking->getStartDatum(), intval($_POST['pin']), $boeking->getTocht()->getID(), $boeking->getKlant()->getID(), $boeking->getStatus()->getID(), $trackerID);
		}
	}
	home();
}

function home()
{
	header('Location: boekingen');
	exit();
}

switch ($view) {
	case "edit":
		if ($boeking->getKlant()->getID() != $_SESSION['id']) home();
?>
		<h3>Boeking wijzigen</h3>
		<form action="" method="post">
			<?php if (isset($error)) {
				echo '
					<div class="alert alert-danger mt-4" role="alert">
						<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
						<span class="sr-only">Error:</span>
						Datum moet nieuwer zijn dan ' . date("d-m-Y") .
					'</div>
				';
			} ?>
			<div class="form-group mt-2">
				<label for="startdatum">Startdatum</label>
				<input value="<?php echo $boeking->getStartdatum(); ?>" name="startDatum" type="date" class="form-control" id="startdatum" required>
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
	case "delete":
		if ($boeking->getKlant()->getID() != $_SESSION['id']) home();
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
		<h3>Boeking Klant</h3>
		<?php
		$boekingen = $db->getBoekingenByKlantID($_SESSION['id']); //$_SESSION['klant_id']
		if (count($boekingen) == 0) {
		?>
			<div class="alert alert-info mt-2" role="alert">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				<span class="sr-only">Error:</span>
				U heeft nog geen boekingen.
			</div>
		<?php } else { ?>
			<table>
				<tr>
					<th>Startdatum</th>
					<th>Einddatum</th>
					<th>Pincode</th>
					<th>Tocht</th>
					<th>Status</th>
					<th>Bijwerken</th>
				</tr>
			<?php
			foreach ($boekingen as $boeking) {
				echo "<tr>";
				echo "<td>" . $boeking->getStartdatum() . "</td>";
				echo "<td>" . date('Y-m-d', strtotime($boeking->getStartdatum() . ' + ' . $boeking->getTocht()->getAantalDagen() . ' days')) . "</td>";
				if ($boeking->getStatus()->getStatusCode() == 20) {
					if (!is_null($boeking->getPINCode())) {
						echo "<td><a class='btn btn-primary min-height-0 btn-sm' href='../view?RouteName=". $boeking->getTocht()->getRoute() . "&PinCode=" . $boeking->getTracker()->getID() . "," .  $boeking->getPINCode() . "' target='_blank'>" . str_pad($boeking->getPINCode(), 4, '0', STR_PAD_LEFT) . "</a>" .
							"<a class='btn btn-danger min-height-0 btn-sm' href='?reset=".$boeking->getID()."'><i class='fa-solid fa-trash-can fa-lg'></i></a></td>";
					} else {
						echo "<td>" . "<a class='btn btn-primary min-height-0 btn-sm' href='?setPin=" . $boeking->getID() . "'>PIN Code aanvragen</a>" . "</td>";
					}
				} else echo "<td><i>Geen Pincode<i></td>";
				echo "<td>" . $boeking->getTocht()->getOmschrijving() . "</td>";
				echo "<td>" . $boeking->getStatus()->getStatus() . "</td>";
				echo "<td class='px-0 d-flex justify-content-center'>
					<a class='mx-1' href='?id={$boeking->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
					<a class='mx-1' href='?id={$boeking->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
				</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
			?>
			<a href="reserveren" class="w-100 mt-3"><button class="w-100 btn btn-primary">Boeking toevoegen</button></a>
		<?php
		break;
}
if (isset($_GET['setPin'])) {
	$id = $_GET['setPin'];
	$boeking = $db->getBoekingByID($id);
	if (!is_null($boeking) && $boeking->getKlant()->getID() == $_SESSION['id'] && is_null($boeking->getPincode())) {
		?>
			<!-- Modal -->
			<div class="modal fade" id="pinModal" tabindex="-1" role="dialog" aria-labelledby="pinModalLabel" aria-hidden="true">
				<div class="modal-dialog modal-dialog-centered" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Zet PIN Code</h5>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<form action="" method="POST">
							<div class="modal-body">
								<input type="hidden" id="ID" name="id" value="<?php echo $boeking->getID(); ?>">
								<div class="form-group mt-2">
									<label for="pin">PIN:</label>
									<input class="w-100" type="text" pattern="[0-9]*" id="pin" name="pin" value="" placeholder="0000">
								</div>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" name="setPin" class="btn btn-primary">Save changes</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<script type="text/javascript">
				$(window).on('load', function() {
					$('#pinModal').modal('show');
				});
				$("#pin").on('input', function(e) {
					var str = $("#pin").val();
					$("#pin").val(str.match("[0-9]*"));
				});
			</script>
	<?php }
} ?>
	<?php include "./include/footer.php"; ?>