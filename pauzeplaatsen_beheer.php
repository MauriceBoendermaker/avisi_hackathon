<?php

use database\Beheerder;

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
    header('Location: projecten');
    exit();
}
?>
<?php include "include/nav.php"; ?>
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);

$projecten = $db->getProjectByID($id);
$pauzeplaatsen = $db->getPauzeplaatsenByProjectID($id);
$beheerders = $db->getBeheerders();

if (isset($_POST['save']) && isset($_POST['statusID'])) {
    $pauzeplaats = $db->getPauzeplaatsByID($_POST['id']);
    $db->setPauzeplaats($pauzeplaats->getID(), $pauzeplaats->getProject()->getID(), $pauzeplaats->getBeheerder()->getID(), $_POST['statusID']);
    header('Location: pauzeplaatsen_beheer?id=' . $id);
}
else if (isset($_POST['save'])) {
    array_map(function ($beheerder) use ($db, $id) {
        if (isset($_POST['beheerders']) && in_array($beheerder->getID(), $_POST['beheerders'], false)) {
            // check if pauzeplaatsen already has an entry with this beheerder
            $pauzeplaats = $db->getPauzeplaatsenByBeheerderID($beheerder->getID(), $id);
            if (is_null($pauzeplaats) || count($pauzeplaats) == 0) {
                $db->setPauzeplaats(null, $id, $beheerder->getID(), 1);
            }
        } else {
            $pauzeplaats = $db->getPauzeplaatsenByBeheerderID($beheerder->getID(), $id);
            if (!is_null($pauzeplaats) && count($pauzeplaats) > 0) {
                $db->deletePauzeplaats($pauzeplaats[0]->getID());
            }
        }
    }, $beheerders);
    header('Location: pauzeplaatsen_beheer?id=' . $id);
    exit();
}

?>
<?php
if (isset($edit)) {
    $pauzeplaats = $db->getPauzeplaatsByID($edit);
?>
    <h3>Pauzeplaats wijzigen</h3>
    <br>
    <form action="" method="post">
        <input type="hidden" name="id" value="<?php echo $pauzeplaats->getID(); ?>">
        <div class="form-group mt-2">
            <label for="beheerder">beheerder:</label>
            <input type="text" class="form-control" name="" id="beheerder" value="<?php echo $pauzeplaats->getBeheerder()->getNaam(); ?>" disabled>
        </div>
        <div class="form-group mt-2">
            <label for="adres">Adres:</label>
            <input type="text" class="form-control" name="" id="adres" value="<?php echo $pauzeplaats->getBeheerder()->getAdres(); ?>" disabled>
        </div>
        <div class="form-group mt-2">
            <label for="status">Status:</label>
            <select class="form-select" id="status" aria-label="Select status" name="statusID">
                <?php foreach ($db->getStatussen() as $status) { ?>
                    <option value="
							<?php echo $status->getID(); ?>" <?php if ($status->getID() == $pauzeplaats->getStatus()->getID()) echo "selected"; ?>>
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
	$project = $db->getProjectByID($id);
?>
    <h3>Pauzeplaatsen</h3>
	<div class="container mb-3">
		<div class="row">
			<div class="col-sm border pb-3">
				<div class="form-group mt-2">
					<label for="startdatum">Startdatum:</label>
					<input value="<?php echo $project->getStartdatum(); ?>" name="startDatum" type="date" class="form-control" id="startdatum" disabled>
				</div>
				<div class="form-group mt-2">
					<label for="eindDatum">Einddatum:</label>
					<input value="<?php echo $project->getEinddatum($project); ?>" name="eindDatum" type="date" class="form-control" id="eindDatum" disabled>
				</div>
			</div>
			<div class="col-sm border pb-3">
				<div class="form-group mt-2">
					<label for="docent">Docent:</label>
						<input name="docentID" class="form-control" aria-label="Select docent" value="<?php echo $project->getDocent()->getNaam(); ?>" disabled>
				</div>
				<div class="form-group mt-2">
					<label for="docent">Email / Telefoon:</label>
					<input name="docentID" class="form-control" aria-label="Select docent" value="<?php echo $project->getDocent()->getEmail() . " / " . $project->getDocent()->getTelefoon(); ?>" disabled>
				</div>
			</div>
			<div class="col-sm border pb-3">
				<div class="form-group mt-2">
					<label for="status">Projectstatus:</label>
					<input class="form-control" aria-label="Select status" name="statusID" value="<?php echo $project->getStatus()->getStatus(); ?>" disabled>
				</div>
				<div class="form-group mt-2">
					<label for="tocht">Route:</label>
					<input name="tochtID" class="form-control" aria-label="Select tocht" value="<?php echo $project->getCriterium()->getOmschrijving(); ?>" disabled>
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
                            <th>Beheerder</th>
                            <th>Adres</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table1" class="connectedSortable min-height">
                        <?php
                        $ids = array();
                        if (!is_null($pauzeplaatsen)) {
                            foreach ($pauzeplaatsen as $pauzeplaats) {
                                array_push($ids, $pauzeplaats->getBeheerder()->getID());
                        ?>
                                <tr>
                                    <input type="hidden" name="beheerders[]" value="<?php echo $pauzeplaats->getBeheerder()->getID(); ?>">
                                    <td><i class="fa-solid fa-ellipsis-vertical"></i></td>
                                    <td><?php echo $pauzeplaats->getBeheerder()->getNaam(); ?></td>
                                    <td class="w-fc"><?php echo $pauzeplaats->getBeheerder()->getAdres(); ?></td>
                                    <td><?php echo $pauzeplaats->getStatus()->getStatus(); ?></td>
                                    <td>
                                        <button type="button" class="float-start addbutton btn btn-primary min-height-0 btn-sm" style="display: none;"><i class="fa-solid fa-plus"></i></button>
                                        <button type="submit" class="float-start btn btn-primary min-height-0 btn-sm" name="edit" value="<?php echo $pauzeplaats->getID(); ?>" class="float-start editbutton"><i class="fa-solid fa-edit"></i></button>
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
                            <th>Beheerder</th>
                            <th>Adres</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="table2" class="connectedSortable min-height">
                        <?php
                        foreach ($beheerders as $beheerder) {
                            if (in_array($beheerder->getID(), $ids)) continue;
                        ?>
                            <tr>
                                <input type="hidden" name="beheerders[]" value="<?php echo $beheerder->getID(); ?>" disabled>
                                <td><i class="fa-solid fa-ellipsis-vertical"></i></td>
                                <td><?php echo $beheerder->getNaam(); ?></td>
                                <td><?php echo $beheerder->getAdres(); ?></td>
                                <td style="display: none;"></td>
                                <td>
                                    <button type="button" class="float-start addbutton btn btn-primary min-height-0 btn-sm" onclick=""><i class="fa-solid fa-plus"></i></button>
                                    <button type="button" class="float-start btn btn-primary min-height-0 btn-sm" class="float-start" style="display: none;"><i class=" fa-solid fa-pen-to-square"></i></button>
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