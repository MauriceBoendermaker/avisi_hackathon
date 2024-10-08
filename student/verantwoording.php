<?php include "./include/nav_student.php"; ?>

<?php
// Database connection
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);

// Get criteria
$criteriaList = $db->getCriteria();

// Get student ID from session
$id = $_SESSION['id'];
$studentID = $id;

if (isset($_POST['save_verantwoording'])) {
    $criteriumID = $_POST['criterium_id'];
    $verantwoording = $_POST['verantwoording'];
    // Save the "Verantwoording" for the criterion
    // $db->saveVerantwoording($criteriumID, $verantwoording, $studentID);
    echo "<div class='alert alert-success' role='alert'>Verantwoording opgeslagen.</div>";
}
?>

<h3>Criteria</h3>
<?php foreach ($criteriaList as $criterium): ?>
    <div class="criterium-item mb-3">
        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-<?php echo $criterium->getID(); ?>">
            <?php echo $criterium->getBeschrijving(); ?>
        </a>
    </div>
<?php endforeach; ?>

<!-- Modals -->
<?php foreach ($criteriaList as $criterium): ?>
    <div class="modal fade" id="modal-<?php echo $criterium->getID(); ?>" tabindex="-1" aria-labelledby="modalLabel-<?php echo $criterium->getID(); ?>" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="" method="post">
                <input type="hidden" name="criterium_id" value="<?php echo $criterium->getID(); ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel-<?php echo $criterium->getID(); ?>"><?php echo $criterium->getBeschrijving(); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Sluiten"></button>
                    </div>
                    <!-- Body -->
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="verantwoording-<?php echo $criterium->getID(); ?>">Verantwoording</label>
                            <textarea class="form-control" id="verantwoording-<?php echo $criterium->getID(); ?>" name="verantwoording" placeholder="Lorem Ipsum"></textarea>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="submit" name="save_verantwoording" class="btn btn-primary">Opslaan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sluiten</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endforeach; ?>

<?php include "./include/footer.php"; ?>
