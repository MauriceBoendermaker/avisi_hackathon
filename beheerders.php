<?php include "include/nav.php"; ?>
<?php include "include/tabs_beheer.php"; ?>
<!-- debug print database Beheerders -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$beheerders = $db->getBeheerders();

// beheerders
// ID INT
// Naam VARCHAR(50)
// Adres VARCHAR(50)
// Email VARCHAR(50)
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
	$db->deleteBeheerder($_POST['id']);
	home();
}

if (isset($_POST['add'])) {
	$db->setBeheerder(null, $_POST['naam'], $_POST['adres'], $_POST['email'], $_POST['telefoon'], $_POST['coordinaten']);
	home();
}

if (isset($_POST['save'])) {
	$db->setBeheerder($_POST['id'], $_POST['naam'], $_POST['adres'], $_POST['email'], $_POST['telefoon'], $_POST['coordinaten']);
	home();
}

function home()
{
	header('Location: beheerders');
	exit();
}

switch ($view) {
	case 'edit':
		$beheerder = $db->getBeheerderByID($id);
?>
		<h3>Beheerder gegevens wijzigen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $beheerder->getID(); ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' name='naam' value='<?php echo $beheerder->getNaam(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="adres">Adres:</label>
				<input type='text' class='form-control' id='adres' name='adres' value='<?php echo $beheerder->getAdres(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' name='email' value='<?php echo $beheerder->getEmail(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="telefoon">Mobiele telefoonnummer:</label>
				<input type='text' class='form-control' id='telefoon' name='telefoon' value='<?php echo $beheerder->getTelefoon(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="coordinaten">Coördinaten:</label>
				<input type='text' class='form-control' id='coordinaten' name='coordinaten' value='<?php echo $beheerder->getCoordinaten(); ?>'>
			</div>
			<br />
			<button type="submit" name="save" class="btn btn-success">Bewaren</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'delete':
		$beheerder = $db->getBeheerderByID($id);
	?>
		<h3>Beheerder verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' value='<?php echo $beheerder->getNaam(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="adres">Adres:</label>
				<input type='text' class='form-control' id='adres' value='<?php echo $beheerder->getAdres(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' value='<?php echo $beheerder->getEmail(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="telefoon">Mobiele telefoonnummer:</label>
				<input type='text' class='form-control' id='telefoon' value='<?php echo $beheerder->getTelefoon(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="coordinaten">Coördinaten:</label>
				<input type='text' class='form-control' id='coordinaten' value='<?php echo $beheerder->getCoordinaten(); ?>' disabled>
			</div>
			<br />
			<button type="submit" name="delete" class="btn btn-danger">Verwijderen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'add':
	?>
		<h3>Nieuwe beheerder</h3>
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
		<h3>Beheerders</h3>
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
		foreach ($beheerders as $beheerder) {

			$output = "./view?f=res," . $beheerder->getID();
			
			echo "<tr>";
			echo "<td>" . $beheerder->getNaam() . "</td>";
			echo "<td>" . $beheerder->getAdres() . "</td>";
			echo "<td>" . $beheerder->getEmail() . "</td>";
			echo "<td>" . $beheerder->getTelefoon() . "</td>";
			echo "<td><a target='_blank' href='" . $output . "'>" . $beheerder->getCoordinaten() . "</td>";
			echo "<td class='px-0 d-flex justify-content-center'>
				<a class='mx-1' href='?id={$beheerder->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
				<a class='mx-1' href='?id={$beheerder->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
			</td>";
			echo "</tr>";
		}
		echo "</table>";
}
	?>
	<?php include "include/footer.php"; ?>