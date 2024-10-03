<?php include "include/nav.php"; ?>
<?php include "include/tabs_beheer.php"; ?>
<!-- debug print database Tochten -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$tochten = $db->getTochten();

// tochten
// ID INT
// Omschrijving VARCHAR(40)
// Route VARCHAR(50)
// AantalDagen INT

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
	$db->deleteTocht($_POST['id']);
	home();
}

if (isset($_POST['add'])) {
	$db->setTocht(null, $_POST['omschrijving'], $_POST['routeNaam'], $_POST['aantalDagen']);
	home();
}

if (isset($_POST['save'])) {
	$db->setTocht($_POST['id'], $_POST['omschrijving'], $_POST['routeNaam'], $_POST['aantalDagen']);
	home();
}

function home()
{
	header('Location: tochten');
	exit();
}

switch ($view) {
	case 'edit':
		$tocht = $db->getTochtByID($id);
?>
		<h3>Tocht gegevens wijzigen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $tocht->getID(); ?>">
			<div class="form-group mt-2">
				<label for="omschrijving">Omschrijving:</label>
				<input type='text' class='form-control' id='omschrijving' name='omschrijving' value='<?php echo $tocht->getOmschrijving(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="routeNaam">Route naam:</label>
				<input type='text' class='form-control' id='routeNaam' name='routeNaam' value='<?php echo $tocht->getRoute(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="aantalDagen">Aantal dagen:</label>
				<input type='number' class='form-control' id='aantalDagen' name='aantalDagen' value='<?php echo $tocht->getAantaldagen(); ?>'>
			</div>
			<br />
			<button type="submit" name="save" class="btn btn-success">Bewaren</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'delete':
		$tocht = $db->getTochtByID($id);
	?>
		<h3>Tocht verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="omschrijving">Omschrijving:</label>
				<input type='text' class='form-control' id='omschrijving' value='<?php echo $tocht->getOmschrijving(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="routeNaam">Route naam:</label>
				<input type='text' class='form-control' id='routeNaam' value='<?php echo $tocht->getRoute(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="aantalDagen">Aantal dagen:</label>
				<input type='number' class='form-control' id='aantalDagen' value='<?php echo $tocht->getAantaldagen(); ?>' disabled>
			</div>
			<br />
			<button type="submit" name="delete" class="btn btn-danger">Verwijderen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'add':
	?>
		<h3>Nieuwe tocht</h3>
		<form action="" method="post">
			<div class="form-group mt-2">
				<label for="omschrijving">Omschrijving:</label>
				<input type='text' class='form-control' id='omschrijving' name='omschrijving' placeholder='Omschrijving'>
			</div>
			<div class="form-group mt-2">
				<label for="routeNaam">Route naam:</label>
				<input type='text' class='form-control' id='routeNaam' name='routeNaam' placeholder='Route naam'>
			</div>
			<div class="form-group mt-2">
				<label for="aantalDagen">Aantal dagen:</label>
				<input type='number' class='form-control' id='aantalDagen' name='aantalDagen' placeholder='Aantal dagen'>
			</div>
			<br />
			<button type="submit" name="add" class="btn btn-success">Toevoegen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	default:
	?>
		<h3>Tochten</h3>
		<table>
			<tr>
				<th>Omschrijving</th>
				<th>Route naam</th>
				<th>Aantal dagen</th>
				<th class="d-flex justify-content-center"><a class='mx-1' href='?view=add'><button class='btn btn-primary min-height-0 btn-sm'><i class="fa-solid fa-plus"></i></button></a></th>
			</tr>
	<?php
		foreach ($tochten as $tocht) {
			echo "<tr>";
			echo "<td>" . $tocht->getOmschrijving() . "</td>";
			echo "<td>" . $tocht->getRoute() . "</td>";
			echo "<td>" . $tocht->getAantalDagen() . "</td>";
			echo "<td class='px-0 d-flex justify-content-center'>
				<a class='mx-1' href='?id={$tocht->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
				<a class='mx-1' href='?id={$tocht->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
			</td>";
			echo "</tr>";
		}
		echo "</table>";
}
	?>
	<?php include "include/footer.php"; ?>