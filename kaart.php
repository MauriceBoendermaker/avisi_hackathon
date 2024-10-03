<?php include "include/nav_kaart.php"; ?>
	<!-- debug print database Tochten -->
<?php
$db = new database\Database($db_host, $db_user, $db_pass, $db_name, $db_port);
$tochten = $db->getTochten();

// tochten
// ID INT
// Omschrijving VARCHAR(40)
// Route VARCHAR(50)
// AantalDagen INT
echo "<h3>Database Tochten</h3>";
echo "<table>";
echo "<tr>";
echo "<th>Omschrijving</th>";
echo "<th>Route naam</th>";
echo "<th>Aantal dagen</th>";
echo "<th>Delete</th>";
echo "</tr>";
foreach ($tochten as $tocht) {
	echo "<tr>";
	echo "<td>" . $tocht->getOmschrijving() . "</td>";
	echo "<td>" . $tocht->getRoute() . "</td>";
	echo "<td>" . $tocht->getAantalDagen() . "</td>";
	echo "</tr>";
}
echo "</table>";
?>
<?php include "include/footer.php"; ?>