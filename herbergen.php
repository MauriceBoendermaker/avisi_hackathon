<?php include "include/nav.php"; ?>
<?php include "include/tabs_beheer.php"; ?>
<!-- debug print database Herbergen -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$herbergen = $db->getHerbergen();

// herbergen
// ID INT
// Naam VARCHAR(50)
// Adres VARCHAR(50)
// Email VARCHAR(100)
// Telefoon VARCHAR(20)
// Coordinaten VARCHAR(20)
// Gewijzigd TIMESTAMP

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
	$db->deleteHerberg($_POST['id']);
	home();
}

if (isset($_POST['add'])) {
	$db->setHerberg(null, $_POST['naam'], $_POST['adres'], $_POST['email'], $_POST['telefoon'], $_POST['coordinaten']);
	home();
}

if (isset($_POST['save'])) {
	$db->setHerberg($_POST['id'], $_POST['naam'], $_POST['adres'], $_POST['email'], $_POST['telefoon'], $_POST['coordinaten']);
	home();
}

function home()
{
	header('Location: herbergen');
	exit();
}

switch ($view) {
	case 'edit':
		$herberg = $db->getHerbergByID($id);
?>
		<h3>Herberg gegevens wijzigen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $herberg->getID(); ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' name='naam' value='<?php echo $herberg->getNaam(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="adres">Adres:</label>
				<input type='text' class='form-control' id='adres' name='adres' value='<?php echo $herberg->getAdres(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' name='email' value='<?php echo $herberg->getEmail(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="telefoon">Mobiele telefoonnummer:</label>
				<input type='text' class='form-control' id='telefoon' name='telefoon' value='<?php echo $herberg->getTelefoon(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="coordinaten">Coördinaten:</label>
				<input type='text' class='form-control' id='coordinaten' name='coordinaten' value='<?php echo $herberg->getCoordinaten(); ?>'>
			</div>
			<br />
			<button type="submit" name="save" class="btn btn-success">Bewaren</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'delete':
		$herberg = $db->getHerbergByID($id);
	?>
		<h3>Herberg verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' value='<?php echo $herberg->getNaam(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="adres">Adres:</label>
				<input type='text' class='form-control' id='adres' value='<?php echo $herberg->getAdres(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' value='<?php echo $herberg->getEmail(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="telefoon">Mobiele telefoonnummer:</label>
				<input type='text' class='form-control' id='telefoon' value='<?php echo $herberg->getTelefoon(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="coordinaten">Coördinaten:</label>
				<input type='text' class='form-control' id='coordinaten' value='<?php echo $herberg->getCoordinaten(); ?>' disabled>
			</div>
			<br />
			<button name="delete" type="submit" class="btn btn-danger">Verwijderen</button>
			<button name="cancel" type="submit" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'add':
	?>
		<h3>Nieuwe herberg</h3>
		<form action="" method="post">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' name='naam' placeholder='Naam'>
			</div>
			<div class="form-group mt-2">
				<label for="adres">Adres:</label>
				<input type='text' class='form-control' id='adres' name='adres' placeholder='Adres'>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' name='email' placeholder='Emailadres'>
			</div>
			<div class="form-group mt-2">
				<label for="telefoon">Mobiele telefoonnummer:</label>
				<input type='text' class='form-control' id='telefoon' name='telefoon' placeholder='Telefoonnummer'>
			</div>
			<div class="form-group mt-2">
				<label for="coordinaten">Coördinaten:</label>
				<input type='text' class='form-control' id='coordinaten' name='coordinaten' placeholder='Coordinaten N??.????? E??.?????'>
			</div>
			<br />
			<button type="submit" name="add" class="btn btn-success">Toevoegen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	default:
	?>
		<h3>Herbergen</h3>
		<table>
			<tr>
				<th>Naam</th>
				<th>Adres</th>
				<th>Email</th>
				<th>Telefoon</th>
				<th>Coördinaten</th>
				<th class="d-flex justify-content-center"><a class='mx-1' href='?view=add'><button class='btn btn-primary min-height-0 btn-sm'><i class="fa-solid fa-plus"></i></button></a></th>
			</tr>
	<?php
		foreach ($herbergen as $herberg) {

			$output = "./view?f=her," . $herberg->getID();

			echo "<tr>";
			echo "<td>" . $herberg->getNaam() . "</td>";
			echo "<td>" . $herberg->getAdres() . "</td>";
			echo "<td>" . $herberg->getEmail() . "</td>";
			echo "<td>" . $herberg->getTelefoon() . "</td>";
			echo "<td><a target='_blank' href='" . $output . "'>" . $herberg->getCoordinaten() . "</td>";
			echo "<td class='px-0 d-flex justify-content-center'>
				<a class='mx-1' href='?id={$herberg->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
				<a class='mx-1' href='?id={$herberg->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
			</td>";
			echo "</tr>";
		}
		echo "</table>";
}
	?>
	<?php include "include/footer.php"; ?>