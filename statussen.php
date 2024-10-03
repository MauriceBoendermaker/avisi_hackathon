<?php include "include/nav.php"; ?>
<?php include "include/tabs_beheer.php"; ?>
<!-- debug print database Statussen -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$statussen = $db->getStatussen();

// statussen
// ID INT
// StatusCode TINYINT(4)
// Status VARCHAR(40)
// Verwijderbaar BIT
// PINtoekennen BIT

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
	$db->deleteStatus($_POST['id']);
	home();
}

if (isset($_POST['add'])) {
	$db->setStatus(null, $_POST['statusCode'], $_POST['status'], $_POST['verwijderbaar'], $_POST['pinToekennen']);
	home();
}

if (isset($_POST['save'])) {
	$db->setStatus($_POST['id'], $_POST['statusCode'], $_POST['status'], $_POST['verwijderbaar'], $_POST['pinToekennen']);
	home();
}

function home()
{
	header('Location: statussen');
	exit();
}

switch ($view) {
	case 'edit':
		$status = $db->getStatusByID($id);
?>
		<h3>Status gegevens wijzigen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $status->getID(); ?>">
			<div class="form-group mt-2">
				<label for="statusCode">Statuscode:</label>
				<input type='number' class='form-control' id='statusCode' name='statusCode' value='<?php echo $status->getStatusCode(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="status">Status:</label>
				<input type='text' class='form-control' id='status' name='status' value='<?php echo $status->getStatus(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="verwijderbaar">Verwijderbaar:</label>
				<input type='checkbox' class='' id='verwijderbaar' name='verwijderbaar' <?php if ($status->getVerwijderbaar() == 1) {
																							echo "checked";
																						} else if ($status->getVerwijderbaar() == 0) {
																							echo "";
																						} ?>>
			</div>
			<div class="form-group mt-2">
				<label for="pinToekennen">PIN toekennen:</label>
				<input type='checkbox' class='' id='pinToekennen' name='pinToekennen' <?php if ($status->getPintoekennen() == 1) {
																							echo "checked";
																						} else if ($status->getPintoekennen() == 0) {
																							echo "";
																						} ?>>
			</div>
			<br />
			<button type="submit" name="save" class="btn btn-success">Bewaren</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'delete':
		$status = $db->getStatusByID($id);
	?>
		<h3>Status gegevens verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="statusCode">Statuscode:</label>
				<input type='number' class='form-control' id='statusCode' value='<?php echo $status->getStatusCode(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="status">Status:</label>
				<input type='text' class='form-control' id='status' value='<?php echo $status->getStatus(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="verwijderbaar">Verwijderbaar:</label>
				<input type='text' class='form-control' id='verwijderbaar' value='<?php if ($status->getVerwijderbaar() == 1) {
																						echo "Ja";
																					} else if ($status->getVerwijderbaar() == 0) {
																						echo "Nee";
																					} ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="pinToekennen">PIN toekennen:</label>
				<input type='text' class='form-control' id='pinToekennen' value='<?php if ($status->getPintoekennen() == 1) {
																						echo "Ja";
																					} else if ($status->getPintoekennen() == 0) {
																						echo "Nee";
																					} ?>' disabled>
			</div>
			<br />
			<button type="submit" name="delete" class="btn btn-danger">Verwijderen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'add':
	?>
		<h3>Nieuwe status</h3>
		<form action="" method="post">
			<div class="form-group mt-2">
				<label for="statusCode">Statuscode:</label>
				<input type='number' class='form-control' id='statusCode' name='statusCode' placeholder='Statuscode'>
			</div>
			<div class="form-group mt-2">
				<label for="status">Status:</label>
				<input type='text' class='form-control' id='status' name='status' placeholder='Status omschrijving'>
			</div>
			<div class="form-group mt-2">
				<label for="verwijderbaar">Verwijderbaar:</label>
				<input type='checkbox' class='' id='verwijderbaar' name='verwijderbaar'>
			</div>
			<div class="form-group mt-2">
				<label for="pinToekennen">Pin toekennen:</label>
				<input type='checkbox' class='' id='pinToekennen' name='pinToekennen'>
			</div>
			<br />
			<button type="submit" name="add" class="btn btn-success">Toevoegen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	default:
	?>
		<h3>Statussen</h3>
		<table>
			<tr>
				<th>Code</th>
				<th>Status</th>
				<th>Verwijderbaar</th>
				<th>PIN toekennen</th>
				<th class="d-flex justify-content-center"><a class='mx-1' href='?view=add'><button class='btn btn-primary min-height-0 btn-sm'><i class="fa-solid fa-plus"></i></button></a></th>
			</tr>
	<?php
		foreach ($statussen as $status) {
			echo "<tr>";
			echo "<td>" . $status->getStatusCode() . "</td>";
			echo "<td>" . $status->getStatus() . "</td>";
			echo "<td>" . ($status->getVerwijderbaar() ? "Ja" : "Nee") . "</td>";
			echo "<td>" . ($status->getPintoekennen() ? "Ja" : "Nee") . "</td>";
			echo "<td class='px-0 d-flex justify-content-center'>
			<a class='mx-1' href='?id={$status->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
			<a class='mx-1' href='?id={$status->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
		</td>";
			echo "</tr>";
		}
		echo "</table>";
}
	?>
	<?php include "include/footer.php"; ?>