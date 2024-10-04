<?php


namespace database;


// overnachtingen
// ID INT
// FKboekingenID INT (foreign key)
// FKstudentenID INT (foreign key)
// FKstatussenID INT (foreign key)
class Overnachting {
    private $id;
    private $fkboekingenid;
    private $fkstudentenid;
    private $fkstatussenid;

    public function __construct($id, $fkboekingenid, $fkstudentenid, $fkstatussenid) {
        $this->id = $id;
        $this->fkboekingenid = $fkboekingenid;
        $this->fkstudentenid = $fkstudentenid;
        $this->fkstatussenid = $fkstatussenid;
    }

    public function getID() {
        return $this->id;
    }

    public function getBoeking() {
        return $this->fkboekingenid;
    }

    public function getStudent() {
        return $this->fkstudentenid;
    }

    public function getStatus() {
        return $this->fkstatussenid;
    }

    public function setID($id) {
        $this->id = $id;
    }
    
    public function setBoeking($boeking) {
        $this->fkboekingenid = $boeking;
    }

    public function setStudent($student) {
        $this->fkstudentenid = $student;
    }

    public function setStatus($status) {
        $this->fkstatussenid = $status;
    }
}