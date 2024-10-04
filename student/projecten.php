<?php

use database\Project;

include "./include/nav_student.php"; ?>
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

$project = $db->getProjectByID($id);
if (isset($_POST['save']) || isset($_POST['delete']) || isset($_POST['reset']) || isset($_POST['setPin'])) {
	if (isset($project) && $project->getDocent()->getID() != $_SESSION['id']) home();
	if (isset($project)) {
		if (isset($_POST['save'])) {
			if ($_POST['startDatum'] < date("Y-m-d")) $error = true;
			$db->setProject($project->getID(), $_POST['startDatum'], $project->getPincode(), $_POST['tochtID'], $project->getDocent()->getID(), $project->getStatus()->getID(), null);
		} else if (isset($_POST['delete'])) {
			$tracker = $project->getTracker();
			if (!is_null($tracker))
				$db->deleteTracker($tracker->getID());
			$db->deleteProject($project->getID());
		} else {
			if (!is_null($project->getTracker()))
				$db->deleteTracker($project->getTracker()->getID());
		}
	} else if (isset($_POST['id'])) {
		$project = $db->getProjectByID($_POST['id']);
		if (!is_null($project) && $project->getDocent()->getID() == $_SESSION['id'] && $project->getStatus()->getStatusCode() == 20 && is_null($project->getPINCode())) {
			$trackerID = $db->setTracker(null, intval($_POST['pin']), 0, 0, 0);
			$db->setProject($project->getID(), $project->getStartDatum(), intval($_POST['pin']), $project->getCriterium()->getID(), $project->getDocent()->getID(), $project->getStatus()->getID(), $trackerID);
		}
	}
	home();
}

function home()
{
	header('Location: projecten');
	exit();
}

switch ($view) {
	case "edit":
		if ($project->getDocent()->getID() != $_SESSION['id']) home();
?>
		<h3>Project wijzigen</h3>
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
				<input value="<?php echo $project->getStartdatum(); ?>" name="startDatum" type="date" class="form-control" id="startdatum" required>
			</div>
			<div class="form-group mt-2">
				<label for="tocht">Tocht:</label>
				<select name="tochtID" class="form-select" aria-label="Select tocht">
					<?php foreach ($db->getCriteria() as $criterium) { ?>
						<option value="
								<?php echo $criterium->getID(); ?>" <?php if ($criterium->getID() == $project->getCriteria()->getID()) echo "selected"; ?>>
							<?php echo $criterium->getBeschrijving(); ?>
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
		if ($project->getDocent()->getID() != $_SESSION['id']) home();
	?>
		<h3>Project verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" id="ID" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="startdatum">Startdatum:</label>
				<input value="<?php echo $project->getStartdatum(); ?>" type="date" class="form-control" id="startdatum" disabled required>
			</div>
			<div class="form-group mt-2">
				<label for="status">Status:</label>
				<input value="<?php echo $project->getStatus()->getStatus(); ?>" type="text" class="form-control" id="status" disabled>
			</div>
			<div class="form-group mt-2">
				<label for="docent">Docent:</label>
				<input value="<?php echo $project->getDocent()->getNaam(); ?>" type="text" class="form-control" id="docent" disabled>
			</div>
			<div class="form-group mt-2">
				<label for="emailTelefoon">Email:</label>
				<input value="<?php echo $project->getDocent()->getEmail(); ?>" type="text" class="form-control" id="emailTelefoon" disabled>
			</div>
			<div class="form-group mt-2">
				<label for="tocht">Tocht:</label>
				<input value="<?php echo $project->getCriterium()->getBeschrijving(); ?>" type="text" class="form-control" id="tocht" disabled>
			</div>
			<br />
			<button name="delete" type="submit" class="btn btn-danger">Verwijderen</button>
			<button name="cancel" type="submit" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	default:
	?>
		<h3>Project starten</h3>
		<?php
		$projecten = $db->getProjectenByDocentID($_SESSION['id']); //$_SESSION['docent_id']
		if (count($projecten) == 0) {
		?>
			<div class="alert alert-info mt-2" role="alert">
				<i class="fa fa-info-circle" aria-hidden="true"></i>
				<span class="sr-only">Error:</span>
				Je bent nog niet met een project bezig.
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
			foreach ($projecten as $project) {
				echo "<tr>";
				echo "<td>" . $project->getStartdatum() . "</td>";
				echo "<td>" . date('Y-m-d', strtotime($project->getStartdatum())) . "</td>";
				if ($project->getStatus()->getStatusCode() == 20) {
					if (!is_null($project->getPINCode())) {
						echo "<td><a class='btn btn-primary min-height-0 btn-sm' href='../view?RouteName=". $project->getCriterium()->getRoute() . "&PinCode=" . $project->getTracker()->getID() . "," .  $project->getPINCode() . "' target='_blank'>" . str_pad($project->getPINCode(), 4, '0', STR_PAD_LEFT) . "</a>" .
							"<a class='btn btn-danger min-height-0 btn-sm' href='?reset=".$project->getID()."'><i class='fa-solid fa-trash-can fa-lg'></i></a></td>";
					} else {
						echo "<td>" . "<a class='btn btn-primary min-height-0 btn-sm' href='?setPin=" . $project->getID() . "'>PIN Code aanvragen</a>" . "</td>";
					}
				} else echo "<td><i>Geen Pincode<i></td>";
				echo "<td>" . $project->getCriterium()->getBeschrijving() . "</td>";
				echo "<td>" . $project->getStatus()->getStatus() . "</td>";
				echo "<td class='px-0 d-flex justify-content-center'>
					<a class='mx-1' href='?id={$project->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
					<a class='mx-1' href='?id={$project->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
				</td>";
				echo "</tr>";
			}
			echo "</table>";
		}
			?>
			<a href="reserveren" class="w-100 mt-3"><button class="w-100 btn btn-primary">Project toevoegen</button></a>
		<?php
		break;
}
if (isset($_GET['setPin'])) {
	$id = $_GET['setPin'];
	$project = $db->getProjectByID($id);
	if (!is_null($project) && $project->getDocent()->getID() == $_SESSION['id'] && is_null($project->getPincode())) {
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
								<input type="hidden" id="ID" name="id" value="<?php echo $project->getID(); ?>">
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