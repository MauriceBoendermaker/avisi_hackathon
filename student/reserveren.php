<?php include "./include/nav_docent.php"; ?>
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
//$projecten = $db->getProjectenByDocentID(0); //$_SESSION['docent_id']
if (isset($_POST['submit'])) {
    // verify form data
    $startdatum = $_POST['startdatum'];
    $tocht = $_POST['tochtID'];

    $startdatum = date("Y-m-d", strtotime($startdatum));
    if ($startdatum < date("Y-m-d")) $error = true;
    else {
        $tocht = intval($tocht);

        $project = new database\Project(null, $startdatum, null, $tocht, $_SESSION['id'], 1, null);
        $db->applyProject($project);

        header("Location: projecten");
        exit;
    }
}
?>
<h3>Project starten</h3>
<div class="row">
    <form class="col-md-7 h-100 position-relative" action="reserveren" method="post">
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
            <label for="startdatum">Startdatum:</label>
            <input type="date" class="form-control" id="startdatum" name="startdatum" placeholder="Startdatum">
        </div>
        <div class="form-group mt-2">
            <label for="tocht">Criteria:</label>
            <select class="form-select" aria-label="Select tocht" id="tocht" name="tochtID">
                <?php foreach ($db->getCriteria() as $tocht) { ?>
                    <option value="<?php echo $tocht->getID(); ?>">
                        <?php echo $tocht->getBeschrijving(); ?>
                    <?php } ?>
            </select>
        </div>
        <!-- submit button -->
        <div class="position-absolute bottom-0 right-0 pb-2">
            <button type="submit" name="submit" class="btn btn-primary mt-3">Project reserveren</button>
            <a href="projecten"><button type="button" class="btn btn-primary mt-3">Terug</button></a>
        </div>
    </form>
    <div class="col-md-5">
        <iframe id="view" class="map pb-2" src="../view?RouteName=Altlay"></iframe>
    </div>
    <script>
        $("#tocht").on('change', function() {
            var select = $("#tocht");
            var file = getFile(parseInt(select.val()));
            $("#view").attr('src', "../view?RouteName=" + file);
        });

        function getFile(id) {
            switch (id) {
                <?php
                foreach ($db->getCriterium() as $criterium) {
                    echo "case " . $criterium->getBeschrijving() . ":\n";
                    echo "return \"" . $tocht->getRoute() . "\";\n";
                }
                ?>
                default:
                    return "";
            }
        }
    </script>
</div>
<?php include "./include/footer.php"; ?>