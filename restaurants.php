<?php include "include/nav.php"; ?>
<?php include "include/tabs_beheer.php"; ?>
<!-- debug print database Restaurants -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$restaurants = $db->getRestaurants();

// restaurants
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
	$db->deleteRestaurant($_POST['id']);
	home();
}

if (isset($_POST['add'])) {
	$db->setRestaurant(null, $_POST['naam'], $_POST['adres'], $_POST['email'], $_POST['telefoon'], $_POST['coordinaten']);
	home();
}

if (isset($_POST['save'])) {
	$db->setRestaurant($_POST['id'], $_POST['naam'], $_POST['adres'], $_POST['email'], $_POST['telefoon'], $_POST['coordinaten']);
	home();
}

function home()
{
	header('Location: restaurants');
	exit();
}

switch ($view) {
	case 'edit':
		$restaurant = $db->getRestaurantByID($id);
?>
		<h3>Restaurant gegevens wijzigen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $restaurant->getID(); ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' name='naam' value='<?php echo $restaurant->getNaam(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="adres">Adres:</label>
				<input type='text' class='form-control' id='adres' name='adres' value='<?php echo $restaurant->getAdres(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' name='email' value='<?php echo $restaurant->getEmail(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="telefoon">Mobiele telefoonnummer:</label>
				<input type='text' class='form-control' id='telefoon' name='telefoon' value='<?php echo $restaurant->getTelefoon(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="coordinaten">Coördinaten:</label>
				<input type='text' class='form-control' id='coordinaten' name='coordinaten' value='<?php echo $restaurant->getCoordinaten(); ?>'>
			</div>
			<br />
			<button type="submit" name="save" class="btn btn-success">Bewaren</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'delete':
		$restaurant = $db->getRestaurantByID($id);
	?>
		<h3>Restaurant verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' value='<?php echo $restaurant->getNaam(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="adres">Adres:</label>
				<input type='text' class='form-control' id='adres' value='<?php echo $restaurant->getAdres(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' value='<?php echo $restaurant->getEmail(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="telefoon">Mobiele telefoonnummer:</label>
				<input type='text' class='form-control' id='telefoon' value='<?php echo $restaurant->getTelefoon(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="coordinaten">Coördinaten:</label>
				<input type='text' class='form-control' id='coordinaten' value='<?php echo $restaurant->getCoordinaten(); ?>' disabled>
			</div>
			<br />
			<button type="submit" name="delete" class="btn btn-danger">Verwijderen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'add':
	?>
		<h3>Nieuw restaurant</h3>
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
		<h3>Restaurants</h3>
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
		foreach ($restaurants as $restaurant) {

			$output = "./view?f=res," . $restaurant->getID();
			
			echo "<tr>";
			echo "<td>" . $restaurant->getNaam() . "</td>";
			echo "<td>" . $restaurant->getAdres() . "</td>";
			echo "<td>" . $restaurant->getEmail() . "</td>";
			echo "<td>" . $restaurant->getTelefoon() . "</td>";
			echo "<td><a target='_blank' href='" . $output . "'>" . $restaurant->getCoordinaten() . "</td>";
			echo "<td class='px-0 d-flex justify-content-center'>
				<a class='mx-1' href='?id={$restaurant->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
				<a class='mx-1' href='?id={$restaurant->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
			</td>";
			echo "</tr>";
		}
		echo "</table>";
}
	?>
	<?php include "include/footer.php"; ?>