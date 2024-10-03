<?php

namespace database;

require_once 'boeking.php';
require_once 'herberg.php';
require_once 'klant.php';
require_once 'overnachting.php';
require_once 'pauzeplaats.php';
require_once 'restaurant.php';
require_once 'status.php';
require_once 'tocht.php';
require_once 'tracker.php';
require_once 'gebruikersrechten.php';


class Database
{
    private $db;
    private $host;
    private $user;
    private $password;
    private $database;
    private $port;

    public function __construct($host, $user, $password, $database, $port)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
        $this->database = $database;
        $this->port = $port;
        $this->connect();
    }

    public function __destruct()
    {
        $this->close();
        $this->db = null;
    }

    public function connect()
    {
        $this->db = new \mysqli($this->host, $this->user, $this->password, $this->database, $this->port);
        if ($this->db->connect_errno) {
            echo "Failed to connect to MySQL: (" . $this->db->connect_errno . ") " . $this->db->connect_error;
        }
    }

    public function close()
    {
        $this->db->close();
    }

    // boekingen
    // ID INT
    // StartDatum DATE
    // PINCode INT
    // FKtochtenID INT (foreign key)
    // FKklantenID INT (foreign key)
    // FKstatussenID INT (foreign key)
    public function getBoekingen()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM boekingen");
        $boekingen = array();
        while ($row = $result->fetch_assoc()) {
            $boekingen[] = new Boeking($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getTochtByID($row["FKtochtenID"]), $this->getKlantByID($row["FKklantenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
        }
        return $boekingen;
    }

    public function getBoekingByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM boekingen WHERE ID = $id");
        // check if result is empty
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Boeking($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getTochtByID($row["FKtochtenID"]), $this->getKlantByID($row["FKklantenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
    }

    public function getBoekingenByKlantID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM boekingen WHERE FKklantenID = $id");
        $boekingen = array();
        while ($row = $result->fetch_assoc()) {
            $boekingen[] = new Boeking($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getTochtByID($row["FKtochtenID"]), $this->getKlantByID($row["FKklantenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
        }
        return $boekingen;
    }

    public function getBoekingenByStatusID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM boekingen WHERE FKstatussenID = $id");
        $boekingen = array();
        while ($row = $result->fetch_assoc()) {
            $boekingen[] = new Boeking($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getTochtByID($row["FKtochtenID"]), $this->getKlantByID($row["FKklantenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
        }
        return $boekingen;
    }

    public function setBoeking($id, $startDatum, $pinCode, $fkTochtenID, $fkKlantenID, $fkStatussenID, $fkTrackersID)
    {
        $this->connect();

        if (is_null($id)) {
            $query = "INSERT INTO boekingen (StartDatum, FKtochtenID, FKklantenID, FKstatussenID";
            if (!is_null($pinCode))
                $query .= ", PINCode";
            if (!is_null($fkTrackersID))
                $query .= ", FKtrackerID";
            $query .= ") VALUES ('$startDatum', '$fkTochtenID', '$fkKlantenID', '$fkStatussenID'";

            if (!is_null($pinCode))
                $query .= ", '$pinCode'";
            if (!is_null($fkTrackersID))
                $query .= ", '$fkTrackersID'";

            $query .= ")";
            
            $result = $this->db->query($query);
        } else {
            $query = "UPDATE boekingen SET StartDatum = '$startDatum', FKtochtenID = '$fkTochtenID', FKklantenID = '$fkKlantenID', FKstatussenID = '$fkStatussenID'";
            if (!is_null($pinCode))
                $query .= ", PINCode = '$pinCode'";
            if (!is_null($fkTrackersID))
                $query .= ", FKtrackerID = '$fkTrackersID'";
            $query .= " WHERE ID = $id";

            $result = $this->db->query($query);
        }
    }

    public function applyBoeking($boeking, $new = false)
    {
        $tocht = $boeking->getTocht();
        if ($tocht instanceof Tocht) $tocht = $tocht->getID();

        $klant = $boeking->getKlant();
        if ($klant instanceof Klant) $klant = $klant->getID();

        $status = $boeking->getStatus();
        if ($status instanceof Status) $status = $status->getID();

        $tracker = $boeking->getTracker();
        if ($tracker instanceof Tracker) $tracker = $tracker->getID();

        $klant = $boeking->getKlant();
        if (!is_numeric($klant)) $klant = $klant->getID();

        $this->setBoeking($new ? null : $boeking->getID(), $boeking->getStartDatum(), $boeking->getPINCode(), $tocht, $klant, $status, $tracker);

        return;
    }

    public function deleteBoeking($id)
    {
        $this->connect();
        $boeking = $this->getBoekingByID($id);
        if ($boeking != null && $boeking->getTracker() != null)
            $result = $this->db->query("DELETE FROM trackers WHERE ID = ".$boeking->getTracker()->getID());
        $result = $this->db->query("DELETE FROM boekingen WHERE ID = $id");
    }

    // herbergen
    // ID INT
    // Naam VARCHAR(50)
    // Adres VARCHAR(50)
    // Email VARCHAR(100)
    // Telefoon VARCHAR(20)
    // Coordinaten VARCHAR(20)
    // Gewijzigd TIMESTAMP
    public function getHerbergen()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM herbergen");
        $herbergen = array();
        while ($row = $result->fetch_assoc()) {
            $herbergen[] = new Herberg($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Telefoon"], $row["Coordinaten"], $row["Gewijzigd"]);
        }
        return $herbergen;
    }

    public function getHerbergByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM herbergen WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Herberg($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Telefoon"], $row["Coordinaten"], $row["Gewijzigd"]);
    }

    public function setHerberg($id, $naam, $adres, $email, $telefoon, $coordinaten, $gewijzigd = null)
    {
        $this->connect();
        if (is_null($gewijzigd) || empty($gewijzigd))
            $gewijzigd = date("Y-m-d H:i:s");

        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO herbergen (Naam, Adres, Email, Telefoon, Coordinaten, Gewijzigd) VALUES ('$naam', '$adres', '$email', '$telefoon', '$coordinaten', '$gewijzigd')");
        } else {
            $result = $this->db->query("UPDATE herbergen SET Naam = '$naam', Adres = '$adres', Email = '$email', Telefoon = '$telefoon', Coordinaten = '$coordinaten', Gewijzigd = '$gewijzigd' WHERE ID = $id");
        }
    }

    public function applyHerberg($herberg, $new = false)
    {
        $this->setHerberg($new ? null : $herberg->getID(), $herberg->getNaam(), $herberg->getAdres(), $herberg->getEmail(), $herberg->getTelefoon(), $herberg->getCoordinaten());
    }

    public function deleteHerberg($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM herbergen WHERE ID = $id");
    }

    // klanten
    // ID INT
    // Naam VARCHAR(50)
    // Email VARCHAR(100)
    // Telefoon VARCHAR(20)
    // Wachtwoord VARCHAR(100)
	// FKgebruikersrechtenID INT (foreign key)
    // Gewijzigd TIMESTAMP

    public function getKlanten()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM klanten");
        $klanten = array();
        while ($row = $result->fetch_assoc()) {
            $klanten[] = new Klant($row["ID"], $row["Naam"], $row["Email"], $row["Telefoon"], $row["Wachtwoord"], $this->getGebruikersrechtByID($row["FKgebruikersrechtenID"]), $row["Gewijzigd"]);
        }
        return $klanten;
    }

    public function getKlantByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM klanten WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Klant($row["ID"], $row["Naam"], $row["Email"], $row["Telefoon"], $row["Wachtwoord"], $this->getGebruikersrechtByID($row["FKgebruikersrechtenID"]), $row["Gewijzigd"]);
    }

    public function getKlantbyEmail($email)
	{
		$this->connect();
		$result = $this->db->query("SELECT * FROM klanten WHERE Email = '$email'");
		$row = $result->fetch_assoc();
		if ($result->num_rows == 0) {
			return null;
		}
        return new Klant($row["ID"], $row["Naam"], $row["Email"], $row["Telefoon"], $row["Wachtwoord"], $this->getGebruikersrechtByID($row["FKgebruikersrechtenID"]), $row["Gewijzigd"]);
	}

    public function setKlant($id, $naam, $email, $telefoon, $wachtwoord, $fkGebruikersrechtenID, $gewijzigd = null)
	{
		$this->connect();
		if (is_null($gewijzigd) || empty($gewijzigd))
			$gewijzigd = date("Y-m-d H:i:s");

		if (is_null($id)) {
			if (is_null($fkGebruikersrechtenID)) {
				$result = $this->db->query("INSERT INTO klanten (Naam, Email, Telefoon, Wachtwoord, Gewijzigd) VALUES ('$naam', '$email', '$telefoon', '$wachtwoord', '$gewijzigd')");
			} else {
				$result = $this->db->query("INSERT INTO klanten (Naam, Email, Telefoon, Wachtwoord, FKgebruikersrechtenID, Gewijzigd) VALUES ('$naam', '$email', '$telefoon', '$wachtwoord', $fkGebruikersrechtenID, '$gewijzigd')");
			}
		} else {
			if (is_null($fkGebruikersrechtenID)) {
				$result = $this->db->query("UPDATE klanten SET Naam = '$naam', Email = '$email', Telefoon = '$telefoon', Wachtwoord = '$wachtwoord', Gewijzigd = '$gewijzigd' WHERE ID = $id");
			} else {
				$result = $this->db->query("UPDATE klanten SET Naam = '$naam', Email = '$email', Telefoon = '$telefoon', Wachtwoord = '$wachtwoord', FKgebruikersrechtenID = $fkGebruikersrechtenID, Gewijzigd = '$gewijzigd' WHERE ID = $id");
			}
		}
	}

    public function applyKlant($klant, $new = false)
    {
        $this->connect();
        $this->setKlant($new ? null : $klant->getID(), $klant->getNaam(), $klant->getEmail(), $klant->getTelefoon(), $klant->getWachtwoord(), $klant->getGebruikersrechten()->getID());
        if (!$new) return $klant->getID();
        return $this->db->insert_id;
    }
    
    public function deleteKlant($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM klanten WHERE ID = $id");
    }

    // gebruikersrechten
    // ID INT
    // Recht VARCHAR(45)
    // Rechten INT (bitmask) ( 1 = read | 2 = write | 4 = update | 8 = delete | 2147483647 = admin )
    public function getGebruikersrechten()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM gebruikersrechten");
        $gebruikersrechten = array();
        while ($row = $result->fetch_assoc()) {
            $gebruikersrechten[] = new Gebruikersrechten($row["ID"], $row["Recht"], $row["Rechten"]);
        }
        return $gebruikersrechten;
    }

    public function getGebruikersrechtByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM gebruikersrechten WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Gebruikersrechten($row["ID"], $row["Recht"], $row["Rechten"]);
    }

    // overnachtingen
    // ID INT
    // FKboekingenID INT (foreign key)
    // FKherbergenID INT (foreign key)
    // FKstatussenID INT (foreign key)
    public function getOvernachtingen()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen");
        $overnachtingen = array();
        while ($row = $result->fetch_assoc()) {
            $overnachtingen[] = new Overnachting($row["ID"], $row["FKboekingenID"], $row["FKherbergenID"], $row["FKstatussenID"]);
        }
        return $overnachtingen;
    }

    public function getOvernachtingByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Overnachting($row["ID"], $this->getBoekingByID($row["FKboekingenID"]), $this->getHerbergByID($row["FKherbergenID"]), $this->getStatusByID($row["FKstatussenID"]));
    }

    public function getOvernachtingenByBoekingID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen WHERE FKboekingenID = $id");
        $overnachtingen = array();
        while ($row = $result->fetch_assoc()) {
            $overnachtingen[] = new Overnachting($row["ID"], $this->getBoekingByID($row["FKboekingenID"]), $this->getHerbergByID($row["FKherbergenID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $overnachtingen;
    }

    public function getOvernachtingenByHerbergID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen WHERE FKherbergenID = $id");
        $overnachtingen = array();
        while ($row = $result->fetch_assoc()) {
            $overnachtingen[] = new Overnachting($row["ID"], $this->getBoekingByID($row["FKboekingenID"]), $this->getHerbergByID($row["FKherbergenID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $overnachtingen;
    }

    public function setOvernachting($id, $fkBoekingenID, $fkHerbergenID, $fkStatussenID)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO overnachtingen (FKboekingenID, FKherbergenID, FKstatussenID) VALUES ('$fkBoekingenID', '$fkHerbergenID', '$fkStatussenID')");
        } else {
            $result = $this->db->query("UPDATE overnachtingen SET FKboekingenID = '$fkBoekingenID', FKherbergenID = '$fkHerbergenID', FKstatussenID = '$fkStatussenID' WHERE ID = $id");
        }
    }

    public function applyOvernachting($overnachting, $new = false)
    {
        $this->setOvernachting($new ? null : $overnachting->getID(), $overnachting->getBoeking()->getID(), $overnachting->getHerberg()->getID(), $overnachting->getStatus()->getID());
    }

    public function deleteOvernachting($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM overnachtingen WHERE ID = $id");
    }

    // pauzeplaatsen
    // ID INT
    // FKboekingenID INT (foreign key)
    // FKrestaurantsID INT (foreign key)
    // FKstatussenID INT (foreign key)

    public function getPauzeplaatsen()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen");
        $pauzeplaatsen = array();
        while ($row = $result->fetch_assoc()) {
            $pauzeplaatsen[] = new Pauzeplaats($row["ID"], $this->getBoekingByID($row["FKboekingenID"]), $this->getRestaurantByID($row["FKrestaurantsID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $pauzeplaatsen;
    }

    public function getPauzeplaatsByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Pauzeplaats($row["ID"], $this->getBoekingByID($row["FKboekingenID"]), $this->getRestaurantByID($row["FKrestaurantsID"]), $this->getStatusByID($row["FKstatussenID"]));
    }

    public function getPauzeplaatsenByBoekingID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen WHERE FKboekingenID = $id");
        $pauzeplaatsen = array();
        while ($row = $result->fetch_assoc()) {
            $pauzeplaatsen[] = new Pauzeplaats($row["ID"], $this->getBoekingByID($row["FKboekingenID"]), $this->getRestaurantByID($row["FKrestaurantsID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $pauzeplaatsen;
    }

    public function getPauzeplaatsenByRestaurantID($id, $boekingID)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen WHERE FKrestaurantsID = $id AND FKboekingenID = $boekingID");
        $pauzeplaatsen = array();
        while ($row = $result->fetch_assoc()) {
            $pauzeplaatsen[] = new Pauzeplaats($row["ID"], $this->getBoekingByID($row["FKboekingenID"]), $this->getRestaurantByID($row["FKrestaurantsID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $pauzeplaatsen;
    }

    public function setPauzeplaats($id, $fkBoekingenID, $fkRestaurantsID, $fkStatussenID)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO pauzeplaatsen (FKboekingenID, FKrestaurantsID, FKstatussenID) VALUES ('$fkBoekingenID', '$fkRestaurantsID', '$fkStatussenID')");
        } else {
            $result = $this->db->query("UPDATE pauzeplaatsen SET FKboekingenID = '$fkBoekingenID', FKrestaurantsID = '$fkRestaurantsID', FKstatussenID = '$fkStatussenID' WHERE ID = $id");
        }
    }

    public function applyPauzeplaats($pauzeplaats, $new = false)
    {
        $this->setPauzeplaats($new ? null : $pauzeplaats->getID(), $pauzeplaats->getFKboekingenID(), $pauzeplaats->getFKrestaurantsID(), $pauzeplaats->getFKstatussenID());
    }

    public function deletePauzeplaats($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM pauzeplaatsen WHERE ID = $id");
    }

    // restaurants
    // ID INT
    // Naam VARCHAR(50)
    // Adres VARCHAR(50)
    // Email VARCHAR(50)
    // Telefoon VARCHAR(20)
    // Coordinaten VARCHAR(20)
    // Gewijzigd TIMESTAMP
    public function getRestaurants()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM restaurants");
        $restaurants = array();
        while ($row = $result->fetch_assoc()) {
            $restaurants[] = new Restaurant($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Telefoon"], $row["Coordinaten"], $row["Gewijzigd"]);
        }
        return $restaurants;
    }

    public function getRestaurantByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM restaurants WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Restaurant($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Telefoon"], $row["Coordinaten"], $row["Gewijzigd"]);
    }

    public function setRestaurant($id, $naam, $adres, $email, $telefoon, $coordinaten, $gewijzigd = null)
    {
        $this->connect();
        if (is_null($gewijzigd) || empty($gewijzigd)) $gewijzigd = date("Y-m-d H:i:s");
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO restaurants (Naam, Adres, Email, Telefoon, Coordinaten, Gewijzigd) VALUES ('$naam', '$adres', '$email', '$telefoon', '$coordinaten', '$gewijzigd')");
        } else {
            $result = $this->db->query("UPDATE restaurants SET Naam = '$naam', Adres = '$adres', Email = '$email', Telefoon = '$telefoon', Coordinaten = '$coordinaten', Gewijzigd = '$gewijzigd' WHERE ID = $id");
        }
    }

    public function applyRestaurant($restaurant, $new = false)
    {
        $this->setRestaurant($new ? null : $restaurant->getID(), $restaurant->getNaam(), $restaurant->getAdres(), $restaurant->getEmail(), $restaurant->getTelefoon(), $restaurant->getCoordinaten());
    }

    public function deleteRestaurant($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM restaurants WHERE ID = $id");
    }

    // statussen
    // ID INT
    // StatusCode TINYINT(4)
    // Status VARCHAR(40)
    // Verwijderbaar BIT
    // PINtoekennen BIT
    public function getStatussen()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM statussen");
        $statussen = array();
        while ($row = $result->fetch_assoc()) {
            $statussen[] = new Status($row["ID"], $row["StatusCode"], $row["Status"], boolval($row["Verwijderbaar"]), boolval($row["PINtoekennen"]));
        }
        return $statussen;
    }

    public function getStatusByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM statussen WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Status($row["ID"], $row["StatusCode"], $row["Status"], boolval($row["Verwijderbaar"]), boolval($row["PINtoekennen"]));
    }

    public function setStatus($id, $statusCode, $status, $verwijderbaar, $pintoekennen)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO statussen (statusCode, Status, Verwijderbaar, PINtoekennen) VALUES ('$statusCode', '$status', '$verwijderbaar', '$pintoekennen')");
        } else {
            $result = $this->db->query("UPDATE statussen SET statusCode = '$statusCode', Status = '$status', Verwijderbaar = '$verwijderbaar', PINtoekennen = '$pintoekennen' WHERE ID = $id");
        }
    }

    public function applyStatus($status, $new = false)
    {
        $this->setStatus($new ? null : $status->getID(), $status->getStatusCode(), $status->getStatus(), $status->getVerwijderbaar(), $status->getPINtoekennen());
    }

    public function deleteStatus($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM statussen WHERE ID = $id");
    }

    // tochten
    // ID INT
    // Omschrijving VARCHAR(40)
    // Route VARCHAR(50)
    // AantalDagen INT
    public function getTochten()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM tochten");
        $tochten = array();
        while ($row = $result->fetch_assoc()) {
            $tochten[] = new Tocht($row["ID"], $row["Omschrijving"], $row["Route"], $row["AantalDagen"]);
        }
        return $tochten;
    }

    public function getTochtByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM tochten WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Tocht($row["ID"], $row["Omschrijving"], $row["Route"], $row["AantalDagen"]);
    }

    public function setTocht($id, $omschrijving, $route, $aantaldagen)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO tochten (Omschrijving, Route, AantalDagen) VALUES ('$omschrijving', '$route', '$aantaldagen')");
        } else {
            $result = $this->db->query("UPDATE tochten SET Omschrijving = '$omschrijving', Route = '$route', AantalDagen = '$aantaldagen' WHERE ID = $id");
        }
    }

    public function applyTocht($tocht, $new = false)
    {
        $this->setTocht($new ? null : $tocht->getID(), $tocht->getOmschrijving(), $tocht->getRoute(), $tocht->getAantalDagen());
    }

    public function deleteTocht($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM tochten WHERE ID = $id");
    }

    // trackers
    // ID INT
    // PINCode INT
    // Lat DOUBLE
    // Lon DOUBLE
    // Time BIGINT(20)
    public function getTrackers()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM trackers");
        $trackers = array();
        while ($row = $result->fetch_assoc()) {
            $trackers[] = new Tracker($row["ID"], $row["PINCode"], $row["Lat"], $row["Lon"], $row["Time"]);
        }
        return $trackers;
    }

    public function getTrackerByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM trackers WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Tracker($row["ID"], $row["PINCode"], $row["Lat"], $row["Lon"], $row["Time"]);
    }

    public function getTrackerByPincode($pin)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM trackers WHERE PINCode = $pin");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Tracker($row["ID"], $row["PINCode"], $row["Lat"], $row["Lon"], $row["Time"]);
    }

    public function setTracker($id, $pin, $lat, $lon, $time)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO trackers (PINCode, Lat, Lon, Time) VALUES ($pin, $lat, $lon, $time)");
        } else {
            $result = $this->db->query("UPDATE trackers SET PINCode = $pin, Lat = $lat, Lon = $lon, Time = $time WHERE ID = $id");
        }
        return $this->db->insert_id;
    }

    public function applyTracker($tracker, $new = false)
    {
        $this->setTracker($new ? null : $tracker->getID(), $tracker->getPINCode(), $tracker->getLat(), $tracker->getLon(), $tracker->getTime());
    }

    public function deleteTracker($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM trackers WHERE ID = $id");
    }
}