<?php

use database\Herberg;

if (isset($_POST['back'])) {
    home();
}

if (isset($_GET['id']))
    $id = $_GET['id'];
else
    home();

if (isset($_POST['edit']))
    $edit = $_POST['edit'];

function home()
{
    header('Location: boekingen');
    exit();
}
?>
<?php include "include/nav.php"; ?>
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);

$boekingen = $db->getBoekingByID($id);
$overnachtingsplaatsen = $db->getOvernachtingenByBoekingID($id);
$herbergen = $db->getHerbergen();

if (isset($_POST['save']) && isset($_POST['statusID'])) {
    $overnachting = $db->getovernachtingByID($_POST['id']);
    $db->setovernachting($overnachting->getID(), $overnachting->getBoeking()->getID(), $overnachting->getHerberg()->getID(), $_POST['statusID']);
    header('Location: overnachtingsplaatsen_beheer?id=' . $id);
}
else if (isset($_POST['save'])) {
    array_map(function ($herberg) use ($db, $id) {
        if (isset($_POST['herbergen']) && in_array($herberg->getID(), $_POST['herbergen'], false)) {
            // check if overnachtingsplaatsen already has an entry with this herberg
            $overnachtingen = $db->getOvernachtingenByHerbergID($herberg->getID(), $id);
            if (is_null($overnachtingen) || count($overnachtingen) == 0) {
                $db->setOvernachting(null, $id, $herberg->getID(), 1);
            }
        } else {
            $overnachting = $db->getOvernachtingenByHerbergID($herberg->getID(), $id);
            if (!is_null($overnachting) && count($overnachting) > 0) {
                $db->deleteOvernachting($overnachting[0]->getID());
            }
        }
    }, $herbergen);
    header('Location: overnachtingsplaatsen_beheer?id=' . $id);
    exit();
}

?>
<?php
if (isset($edit)) {
    $overnachting = $db->getovernachtingByID($edit);
?>
    <h3>Overnachting wijzigen</h3>
    <br>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo $overnachting->getID(); ?>">
        <div class="form-group mt-2">
            <label for="herberg">Herberg:</label>
            <input type="text" class="form-control" name="" id="herberg" value="<?php echo $overnachting->getHerberg()->getNaam(); ?>" disabled>
        </div>
        <div class="form-group mt-2">
            <label for="adres">Adres:</label>
            <input type="text" class="form-control" name="" id="adres" value="<?php echo $overnachting->getHerberg()->getAdres(); ?>" disabled>
        </div>
        <div class="form-group mt-2">
            <label for="status">Status:</label>
            <select class="form-select" id="status" aria-label="Select status" name="statusID">
                <?php foreach ($db->getStatussen() as $status) { ?>
                    <option value="
							<?php echo $status->getID(); ?>" <?php if ($status->getID() == $overnachting->getStatus()->getID()) echo "selected"; ?>>
                        <?php echo $status->getStatus(); ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <br />
        <button name="save" type="submit" class="btn btn-success">Bewaren</button>
        <button name="cancel" type="submit" class="btn btn-primary">Annuleren</button>
    </form>
<?php
} else {
	$boeking = $db->getBoekingByID($id);
?>
    <h3>Overnachtingsplaatsen</h3>
	<div class="container mb-3">
		<div class="row">
			<div class="col-sm border pb-3">
				<div class="form-group mt-2">
					<label for="startdatum">Startdatum:</label>
					<input value="<?php echo $boeking->getStartdatum(); ?>" name="startDatum" type="date" class="form-control" id="startdatum" disabled>
				</div>
				<div class="form-group mt-2">
					<label for="eindDatum">Einddatum:</label>
					<input value="<?php echo $boeking->getEinddatum($boeking); ?>" name="eindDatum" type="date" class="form-control" id="eindDatum" disabled>
				</div>
			</div>
			<div class="col-sm border pb-3">
				<div class="form-group mt-2">
					<label for="klant">Klant:</label>
					<input name="klantID" class="form-control" aria-label="Select klant" value="<?php echo $boeking->getKlant()->getNaam(); ?>" disabled>
				</div>
				<div class="form-group mt-2">
					<label for="klant">Email / Telefoon:</label>
					<input name="klantID" class="form-control" aria-label="Select klant" value="<?php echo $boeking->getKlant()->getEmail() . " / " . $boeking->getKlant()->getTelefoon(); ?>" disabled>
				</div>
			</div>
			<div class="col-sm border pb-3">
				<div class="form-group mt-2">
					<label for="status">Boekingstatus:</label>
					<input class="form-control" aria-label="Select status" name="statusID" value="<?php echo $boeking->getStatus()->getStatus(); ?>" disabled>
				</div>
				<div class="form-group mt-2">
					<label for="tocht">Route:</label>
					<input name="tochtID" class="form-control" aria-label="Select tocht" value="<?php echo $boeking->getTocht()->getOmschrijving(); ?>" disabled>
				</div>
			</div>
		</div>
	</div>
    <form action="" method="post">
        <div class="row g-2">
            <div class="col-sm-6 h-fc">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>herberg</th>
                            <th>Adres</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table1" class="connectedSortable min-height">
                        <?php
                        $ids = array();
                        if (!is_null($overnachtingsplaatsen)) {
                            foreach ($overnachtingsplaatsen as $overnachting) {
                                array_push($ids, $overnachting->getHerberg()->getID());
                        ?>
                                <tr>
                                    <input type="hidden" name="herbergen[]" value="<?php echo $overnachting->getHerberg()->getID(); ?>">
                                    <td><i class="fa-solid fa-ellipsis-vertical"></i></td>
                                    <td><?php echo $overnachting->getHerberg()->getNaam(); ?></td>
                                    <td class="w-fc"><?php echo $overnachting->getHerberg()->getAdres(); ?></td>
                                    <td><?php echo $overnachting->getStatus()->getStatus(); ?></td>
                                    <td>
                                        <button type="button" class="float-start addbutton btn btn-primary min-height-0 btn-sm" style="display: none;"><i class="fa-solid fa-plus"></i></button>
                                        <button type="submit" class="float-start btn btn-primary min-height-0 btn-sm" name="edit" value="<?php echo $overnachting->getID(); ?>" class="float-start editbutton"><i class="fa-solid fa-edit"></i></button>
                                        <button type="button" class="float-start removebutton btn btn-danger min-height-0 btn-sm"><i class="fa-solid fa-minus"></i></button>
                                    </td>
                                </tr>
                        <?php
                            }
                        }
                        ?>
                        <tr class="unsortable" style="display: none;">
                            <td colspan="5"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-6 h-fc">
                <table>
                    <thead>
                        <tr>
                            <th></th>
                            <th>herberg</th>
                            <th>Adres</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table2" class="connectedSortable min-height">
                        <?php
                        foreach ($herbergen as $herberg) {
                            if (in_array($herberg->getID(), $ids)) continue;
                        ?>
                            <tr>
                                <input type="hidden" name="herbergen[]" value="<?php echo $herberg->getID(); ?>" disabled>
                                <td><i class="fa-solid fa-ellipsis-vertical"></i></td>
                                <td><?php echo $herberg->getNaam(); ?></td>
                                <td><?php echo $herberg->getAdres(); ?></td>
                                <td style="display: none;"></td>
                                <td>
                                    <button type="button" class="float-start addbutton btn btn-primary min-height-0 btn-sm" onclick=""><i class="fa-solid fa-plus"></i></button>
                                    <button type="button" class="float-start btn btn-primary min-height-0 btn-sm" style="display: none;"><i class=" fa-solid fa-pen-to-square"></i></button>
                                    <button type="button" class="float-start removebutton btn btn-danger min-height-0 btn-sm" style="display: none;"><i class="fa-solid fa-minus"></i></button>
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                        <tr class="unsortable" style="display: none;">
                            <td colspan="4"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <br />
        <button type="submit" name="save" class="btn btn-success">Bewaren</button>
        <button type="submit" name="back" class="btn btn-primary">Terug</button>
    </form>
    <script src="./js/dragtable.js"></script>
<?php
}
?>
<?php include "include/footer.php"; ?>