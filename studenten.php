<?php include "include/nav.php"; ?>
<?php include "include/tabs_beheer.php"; ?>
<!-- debug print database Studenten -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$studenten = $db->getStudenten();

// studenten
// ID INT
// Naam VARCHAR(50)
// Adres VARCHAR(50)
// Email VARCHAR(100)
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
	$db->deleteStudent($_POST['id']);
	home();
}

if (isset($_POST['add'])) {
	$db->setStudent(null, $_POST['naam'], $_POST['email']);
	home();
}

if (isset($_POST['save'])) {
	$db->setStudent($_POST['id'], $_POST['naam'], $_POST['email']);
	home();
}

function home()
{
	header('Location: studenten');
	exit();
}

switch ($view) {
	case 'edit':
		$student = $db->getStudentByID($id);
?>
		<h3>Student gegevens wijzigen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $student->getID(); ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' name='naam' value='<?php echo $student->getNaam(); ?>'>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' name='email' value='<?php echo $student->getEmail(); ?>'>
			</div>
			<br />
			<button type="submit" name="save" class="btn btn-success">Bewaren</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'delete':
        $student = $db->getStudentByID($id);
	?>
		<h3>Student verwijderen</h3>
		<form action="" method="post">
			<input type="hidden" name="id" value="<?php echo $id; ?>">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' value='<?php echo $student->getNaam(); ?>' disabled>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' value='<?php echo $student->getEmail(); ?>' disabled>
			</div>
			<br />
			<button name="delete" type="submit" class="btn btn-danger">Verwijderen</button>
			<button name="cancel" type="submit" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	case 'add':
	?>
		<h3>Nieuwe student</h3>
		<form action="" method="post">
			<div class="form-group mt-2">
				<label for="naam">Naam:</label>
				<input type='text' class='form-control' id='naam' name='naam' placeholder='Naam'>
			</div>
			<div class="form-group mt-2">
				<label for="email">Emailadres:</label>
				<input type='email' class='form-control' id='email' name='email' placeholder='Emailadres'>
			</div>
			<br />
			<button type="submit" name="add" class="btn btn-success">Toevoegen</button>
			<button type="submit" name="cancel" class="btn btn-primary">Annuleren</button>
		</form>
	<?php
		break;
	default:
	?>
		<h3>Studenten</h3>
		<table>
			<tr>
				<th>Naam</th>
				<th>Email</th>
				<th class="d-flex justify-content-center"><a class='mx-1' href='?view=add'><button class='btn btn-primary min-height-0 btn-sm'><i class="fa-solid fa-plus"></i></button></a></th>
			</tr>
	<?php
		foreach ($studenten as $student) {

			$output = "./view?f=her," . $student->getID();

			echo "<tr>";
			echo "<td>" . $student->getNaam() . "</td>";
			echo "<td>" . $student->getEmail() . "</td>";
			echo "<td class='px-0 d-flex justify-content-center'>
				<a class='mx-1' href='?id={$student->getID()}&view=edit'><button class='btn btn-primary min-height-0 btn-sm'><i class='fa-solid fa-pen-to-square'></i></button></a>
				<a class='mx-1' href='?id={$student->getID()}&view=delete'><button class='btn btn-danger min-height-0 btn-sm'><i class='fa-solid fa-trash-can'></i></button></a>
			</td>";
			echo "</tr>";
		}
		echo "</table>";
}
	?>
	<?php include "include/footer.php"; ?>