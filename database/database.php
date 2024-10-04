<?php

namespace database;

require_once 'projecten.php';
require_once 'student.php';
require_once 'docent.php';
require_once 'overnachting.php';
require_once 'pauzeplaats.php';
require_once 'beheerder.php';
require_once 'criterium.php';
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

    // projecten
    // ID INT
    // StartDatum DATE
    // PINCode INT
    // FKcriteriumID INT (foreign key)
    // FKDocentenID INT (foreign key)
    // FKstatussenID INT (foreign key)
    public function getProjecten()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM projecten");
        $projecten = array();
        while ($row = $result->fetch_assoc()) {
            $projecten[] = new Project($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getCriteriumByID($row["FKtochtenID"]), $this->getDocentByID($row["FKdocentenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
        }
        return $projecten;
    }

    public function getProjectByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM projecten WHERE ID = $id");
        // check if result is empty
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Project($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getCriteriumByID($row["FKtochtenID"]), $this->getDocentByID($row["FKdocentenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
    }

    public function getProjectenByDocentID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM projecten WHERE FKdocentenID = $id");
        $projecten = array();
        while ($row = $result->fetch_assoc()) {
            $projecten[] = new Project($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getCriteriumByID($row["FKtochtenID"]), $this->getDocentByID($row["FKdocentenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
        }
        return $projecten;
    }

    public function getProjectenByStatusID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM projecten WHERE FKstatussenID = $id");
        $projecten = array();
        while ($row = $result->fetch_assoc()) {
            $projecten[] = new Project($row["ID"], $row["StartDatum"], $row["PINCode"], $this->getCriteriumByID($row["FKtochtenID"]), $this->getDocentByID($row["FKdocentenID"]), $this->getStatusByID($row["FKstatussenID"]), !isset($row["FKtrackerID"]) ? null : $this->getTrackerByID($row["FKtrackerID"]));
        }
        return $projecten;
    }

    public function setProject($id, $startDatum, $pinCode, $fkTochtenID, $fkDocentenID, $fkStatussenID, $fkTrackersID)
    {
        $this->connect();

        if (is_null($id)) {
            $query = "INSERT INTO projecten (StartDatum, FKtochtenID, FKdocentenID, FKstatussenID";
            if (!is_null($pinCode))
                $query .= ", PINCode";
            if (!is_null($fkTrackersID))
                $query .= ", FKtrackerID";
            $query .= ") VALUES ('$startDatum', '$fkTochtenID', '$fkDocentenID', '$fkStatussenID'";

            if (!is_null($pinCode))
                $query .= ", '$pinCode'";
            if (!is_null($fkTrackersID))
                $query .= ", '$fkTrackersID'";

            $query .= ")";

            $result = $this->db->query($query);
        } else {
            $query = "UPDATE projecten SET StartDatum = '$startDatum', FKtochtenID = '$fkTochtenID', FKdocentenID = '$fkDocentenID', FKstatussenID = '$fkStatussenID'";
            if (!is_null($pinCode))
                $query .= ", PINCode = '$pinCode'";
            if (!is_null($fkTrackersID))
                $query .= ", FKtrackerID = '$fkTrackersID'";
            $query .= " WHERE ID = $id";

            $result = $this->db->query($query);
        }
    }

    public function applyProject($project, $new = false)
    {
        $tocht = $project->getCriterium();
        if ($tocht instanceof Tocht) $tocht = $tocht->getID();

        $docent = $project->getDocent();
        if ($docent instanceof Docent) $docent = $docent->getID();

        $status = $project->getStatus();
        if ($status instanceof Status) $status = $status->getID();

        $tracker = $project->getTracker();
        if ($tracker instanceof Tracker) $tracker = $tracker->getID();

        $docent = $project->getDocent();
        if (!is_numeric($docent)) $docent = $docent->getID();

        $this->setProject($new ? null : $project->getID(), $project->getStartDatum(), $project->getPINCode(), $tocht, $docent, $status, $tracker);

        return;
    }

    public function deleteProject($id)
    {
        $this->connect();
        $project = $this->getProjectByID($id);
        if ($project != null && $project->getTracker() != null)
            $result = $this->db->query("DELETE FROM trackers WHERE ID = ".$project->getTracker()->getID());
        $result = $this->db->query("DELETE FROM projecten WHERE ID = $id");
    }

    // studenten
    // ID INT
    // Naam VARCHAR(50)
    // Adres VARCHAR(50)
    // Email VARCHAR(100)
    // Coordinaten VARCHAR(20)
    // Gewijzigd TIMESTAMP
    public function getStudenten()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM studenten");
        $studenten = array();
        while ($row = $result->fetch_assoc()) {
            $studenten[] = new Student($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Coordinaten"], $row["Gewijzigd"]);
        }
        return $studenten;
    }

    public function getStudentByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM studenten WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Student($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Coordinaten"], $row["Gewijzigd"]);
    }

    public function setStudent($id, $naam, $adres, $email, $coordinaten, $gewijzigd = null)
    {
        $this->connect();
        if (is_null($gewijzigd) || empty($gewijzigd))
            $gewijzigd = date("Y-m-d H:i:s");

        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO studenten (Naam, Adres, Email, Coordinaten, Gewijzigd) VALUES ('$naam', '$adres', '$email', '$coordinaten', '$gewijzigd')");
        } else {
            $result = $this->db->query("UPDATE studenten SET Naam = '$naam', Adres = '$adres', Email = '$email', Coordinaten = '$coordinaten', Gewijzigd = '$gewijzigd' WHERE ID = $id");
        }
    }

    public function applyStudent($student, $new = false)
    {
        $this->setStudent($new ? null : $student->getID(), $student->getNaam(), $student->getAdres(), $student->getEmail(), $student->getCoordinaten());
    }

    public function deleteStudent($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM studenten WHERE ID = $id");
    }

    // docent
    // ID INT
    // Naam VARCHAR(50)
    // Email VARCHAR(100)
    // Wachtwoord VARCHAR(100)
	// FKgebruikersrechtenID INT (foreign key)
    // Gewijzigd TIMESTAMP

    public function getDocenten()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM docenten");
        $docenten = array();
        while ($row = $result->fetch_assoc()) {
            $docenten[] = new Docent($row["ID"], $row["Naam"], $row["Email"], $row["Wachtwoord"], $this->getGebruikersrechtByID($row["FKgebruikersrechtenID"]), $row["Gewijzigd"]);
        }
        return $docenten;
    }

    public function getDocentByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM docenten WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Docent($row["ID"], $row["Naam"], $row["Email"], $row["Wachtwoord"], $this->getGebruikersrechtByID($row["FKgebruikersrechtenID"]), $row["Gewijzigd"]);
    }

    public function getDocentByEmail($email)
	{
		$this->connect();
		$result = $this->db->query("SELECT * FROM docenten WHERE Email = '$email'");
		$row = $result->fetch_assoc();
		if ($result->num_rows == 0) {
			return null;
		}
        return new Docent($row["ID"], $row["Naam"], $row["Email"], $row["Wachtwoord"], $this->getGebruikersrechtByID($row["FKgebruikersrechtenID"]), $row["Gewijzigd"]);
	}

    public function getStudentByEmail($email)
	{
		$this->connect();
		$result = $this->db->query("SELECT * FROM studenten WHERE Email = '$email'");
		$row = $result->fetch_assoc();
		if ($result->num_rows == 0) {
			return null;
		}

        // $id, $volnaam, $klas, $cohort, $crebonummer, $geboortedatum
        return new Student($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Coordinaten"], $row["Gewijzigd"]);
	}

    public function setDocent($id, $naam, $email, $wachtwoord, $fkGebruikersrechtenID, $gewijzigd = null)
	{
		$this->connect();
		if (is_null($gewijzigd) || empty($gewijzigd))
			$gewijzigd = date("Y-m-d H:i:s");

		if (is_null($id)) {
			if (is_null($fkGebruikersrechtenID)) {
				$result = $this->db->query("INSERT INTO docenten (Naam, Email, Telefoon, Wachtwoord, Gewijzigd) VALUES ('$naam', '$email', '$telefoon', '$wachtwoord', '$gewijzigd')");
			} else {
				$result = $this->db->query("INSERT INTO docenten (Naam, Email, Telefoon, Wachtwoord, FKgebruikersrechtenID, Gewijzigd) VALUES ('$naam', '$email', '$telefoon', '$wachtwoord', $fkGebruikersrechtenID, '$gewijzigd')");
			}
		} else {
			if (is_null($fkGebruikersrechtenID)) {
				$result = $this->db->query("UPDATE docenten SET Naam = '$naam', Email = '$email', Telefoon = '$telefoon', Wachtwoord = '$wachtwoord', Gewijzigd = '$gewijzigd' WHERE ID = $id");
			} else {
				$result = $this->db->query("UPDATE docenten SET Naam = '$naam', Email = '$email', Telefoon = '$telefoon', Wachtwoord = '$wachtwoord', FKgebruikersrechtenID = $fkGebruikersrechtenID, Gewijzigd = '$gewijzigd' WHERE ID = $id");
			}
		}
	}

    public function applyDocent($docent, $new = false)
    {
        $this->connect();
        $this->setDocent($new ? null : $docent->getID(), $docent->getNaam(), $docent->getEmail(), $docent->getTelefoon(), $docent->getWachtwoord(), $docent->getGebruikersrechten()->getID());
        if (!$new) return $docent->getID();
        return $this->db->insert_id;
    }
    
    public function deleteDocent($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM docenten WHERE ID = $id");
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
    // FKprojectenID INT (foreign key)
    // FKherbergenID INT (foreign key)
    // FKstatussenID INT (foreign key)
    public function getOvernachtingen()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen");
        $overnachtingen = array();
        while ($row = $result->fetch_assoc()) {
            $overnachtingen[] = new Overnachting($row["ID"], $row["FKprojectenID"], $row["FKherbergenID"], $row["FKstatussenID"]);
        }
        return $overnachtingen;
    }

    public function getOvernachtingByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Overnachting($row["ID"], $this->getProjectByID($row["FKprojectenID"]), $this->getHerbergByID($row["FKherbergenID"]), $this->getStatusByID($row["FKstatussenID"]));
    }

    public function getOvernachtingenByProjectID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen WHERE FKprojectenID = $id");
        $overnachtingen = array();
        while ($row = $result->fetch_assoc()) {
            $overnachtingen[] = new Overnachting($row["ID"], $this->getProjectByID($row["FKprojectenID"]), $this->getHerbergByID($row["FKherbergenID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $overnachtingen;
    }

    public function getOvernachtingenByHerbergID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM overnachtingen WHERE FKherbergenID = $id");
        $overnachtingen = array();
        while ($row = $result->fetch_assoc()) {
            $overnachtingen[] = new Overnachting($row["ID"], $this->getProjectByID($row["FKprojectenID"]), $this->getHerbergByID($row["FKherbergenID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $overnachtingen;
    }

    public function setOvernachting($id, $fkProjectenID, $fkHerbergenID, $fkStatussenID)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO overnachtingen (FKprojectenID, FKherbergenID, FKstatussenID) VALUES ('$fkProjectenID', '$fkHerbergenID', '$fkStatussenID')");
        } else {
            $result = $this->db->query("UPDATE overnachtingen SET FKprojectenID = '$fkProjectenID', FKherbergenID = '$fkHerbergenID', FKstatussenID = '$fkStatussenID' WHERE ID = $id");
        }
    }

    public function applyOvernachting($overnachting, $new = false)
    {
        $this->setOvernachting($new ? null : $overnachting->getID(), $overnachting->getProject()->getID(), $overnachting->getHerberg()->getID(), $overnachting->getStatus()->getID());
    }

    public function deleteOvernachting($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM overnachtingen WHERE ID = $id");
    }

    // pauzeplaatsen
    // ID INT
    // FKprojectenID INT (foreign key)
    // FKbeheerdersID INT (foreign key)
    // FKstatussenID INT (foreign key)

    public function getPauzeplaatsen()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen");
        $pauzeplaatsen = array();
        while ($row = $result->fetch_assoc()) {
            $pauzeplaatsen[] = new Pauzeplaats($row["ID"], $this->getProjectByID($row["FKprojectenID"]), $this->getBeheerderByID($row["FKbeheerdersID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $pauzeplaatsen;
    }

    public function getPauzeplaatsByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Pauzeplaats($row["ID"], $this->getProjectByID($row["FKprojectenID"]), $this->getBeheerderByID($row["FKbeheerdersID"]), $this->getStatusByID($row["FKstatussenID"]));
    }

    public function getPauzeplaatsenByProjectID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen WHERE FKprojectenID = $id");
        $pauzeplaatsen = array();
        while ($row = $result->fetch_assoc()) {
            $pauzeplaatsen[] = new Pauzeplaats($row["ID"], $this->getProjectByID($row["FKprojectenID"]), $this->getBeheerderByID($row["FKbeheerdersID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $pauzeplaatsen;
    }

    public function getPauzeplaatsenByBeheerderID($id, $projectID)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM pauzeplaatsen WHERE FKbeheerdersID = $id AND FKprojectenID = $projectID");
        $pauzeplaatsen = array();
        while ($row = $result->fetch_assoc()) {
            $pauzeplaatsen[] = new Pauzeplaats($row["ID"], $this->getProjectByID($row["FKprojectenID"]), $this->getBeheerderByID($row["FKbeheerdersID"]), $this->getStatusByID($row["FKstatussenID"]));
        }
        return $pauzeplaatsen;
    }

    public function setPauzeplaats($id, $fkProjectenID, $fkBeheerdersID, $fkStatussenID)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO pauzeplaatsen (FKprojectenID, FKbeheerdersID, FKstatussenID) VALUES ('$fkProjectenID', '$fkBeheerdersID', '$fkStatussenID')");
        } else {
            $result = $this->db->query("UPDATE pauzeplaatsen SET FKprojectenID = '$fkProjectenID', FKbeheerdersID = '$fkBeheerdersID', FKstatussenID = '$fkStatussenID' WHERE ID = $id");
        }
    }

    public function applyPauzeplaats($pauzeplaats, $new = false)
    {
        $this->setPauzeplaats($new ? null : $pauzeplaats->getID(), $pauzeplaats->getFKprojectenID(), $pauzeplaats->getFKbeheerdersID(), $pauzeplaats->getFKstatussenID());
    }

    public function deletePauzeplaats($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM pauzeplaatsen WHERE ID = $id");
    }

    // beheerders
    // ID INT
    // Naam VARCHAR(50)
    // Adres VARCHAR(50)
    // Email VARCHAR(50)
    // Telefoon VARCHAR(20)
    // Coordinaten VARCHAR(20)
    // Gewijzigd TIMESTAMP
    public function getBeheerders()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM beheerders");
        $beheerders = array();
        while ($row = $result->fetch_assoc()) {
            $beheerders[] = new Beheerder($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Telefoon"], $row["Coordinaten"], $row["Gewijzigd"]);
        }
        return $beheerders;
    }

    public function getBeheerderByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM beheerders WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Beheerder($row["ID"], $row["Naam"], $row["Adres"], $row["Email"], $row["Telefoon"], $row["Coordinaten"], $row["Gewijzigd"]);
    }

    public function setBeheerder($id, $naam, $adres, $email, $telefoon, $coordinaten, $gewijzigd = null)
    {
        $this->connect();
        if (is_null($gewijzigd) || empty($gewijzigd)) $gewijzigd = date("Y-m-d H:i:s");
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO beheerders (Naam, Adres, Email, Telefoon, Coordinaten, Gewijzigd) VALUES ('$naam', '$adres', '$email', '$telefoon', '$coordinaten', '$gewijzigd')");
        } else {
            $result = $this->db->query("UPDATE beheerders SET Naam = '$naam', Adres = '$adres', Email = '$email', Telefoon = '$telefoon', Coordinaten = '$coordinaten', Gewijzigd = '$gewijzigd' WHERE ID = $id");
        }
    }

    public function applyBeheerder($beheerder, $new = false)
    {
        $this->setBeheerder($new ? null : $beheerder->getID(), $beheerder->getNaam(), $beheerder->getAdres(), $beheerder->getEmail(), $beheerder->getTelefoon(), $beheerder->getCoordinaten());
    }

    public function deleteBeheerder($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM beheerders WHERE ID = $id");
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

    // criteria
    // ID INT
    // Beschrijving VARCHAR(40)
    public function getCriteria()
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM criteria");
        $criteria = array();
        while ($row = $result->fetch_assoc()) {
            $criteria[] = new Criterium($row["ID"], $row["Beschrijving"]);
        }
        return $criteria;
    }

    public function getCriteriumByID($id)
    {
        $this->connect();
        $result = $this->db->query("SELECT * FROM criteria WHERE ID = $id");
        $row = $result->fetch_assoc();
        if (is_null($row)) return null;
        return new Tocht($row["ID"], $row["Beschrijving"]);
    }

    public function setCriterium($id, $beschrijving)
    {
        $this->connect();
        if (is_null($id)) {
            $result = $this->db->query("INSERT INTO criteria (Beschrijving) VALUES ('$beschrijving')");
        } else {
            $result = $this->db->query("UPDATE criteria SET Beschrijving = '$beschrijving' WHERE ID = $id");
        }
    }

    public function applyCriterium($tocht, $new = false)
    {
        $this->setCriterium($new ? null : $tocht->getID(), $tocht->getBeschrijving());
    }

    public function deleteCriteria($id)
    {
        $this->connect();
        $result = $this->db->query("DELETE FROM criteria WHERE ID = $id");
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