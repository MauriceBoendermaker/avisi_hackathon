<body>
<?php include "include/nav_student.php"; ?>
	                <!-- Welcome screen saying name of user -->
                    <?php
                    if (isset($_SESSION['naam'])) {
                        echo "<h3>Welkom, " . $_SESSION['naam'] . "!</h3>";
                    } else
                        echo "<h3>Welkom, Gast</h3>";
                    ?>
                    <div class="alert alert-info" role="alert">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                        <span class="sr-only">Info:</span>
	                    LearnFlow RijnIjssel is een tool die kwalificerend leren ondersteunt.
                    </div>


<?php include "include/footer.php" ?>
        </div>
    </div>
</body>
</html>