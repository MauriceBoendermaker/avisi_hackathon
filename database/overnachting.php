<?php


namespace database;


// overnachtingen
// ID INT
// FKboekingenID INT (foreign key)
// FKherbergenID INT (foreign key)
// FKstatussenID INT (foreign key)
class Overnachting {
    private $id;
    private $fkboekingenid;
    private $fkherbergenid;
    private $fkstatussenid;

    public function __construct($id, $fkboekingenid, $fkherbergenid, $fkstatussenid) {
        $this->id = $id;
        $this->fkboekingenid = $fkboekingenid;
        $this->fkherbergenid = $fkherbergenid;
        $this->fkstatussenid = $fkstatussenid;
    }

    public function getID() {
        return $this->id;
    }

    public function getBoeking() {
        return $this->fkboekingenid;
    }

    public function getHerberg() {
        return $this->fkherbergenid;
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

    public function setHerberg($herberg) {
        $this->fkherbergenid = $herberg;
    }

    public function setStatus($status) {
        $this->fkstatussenid = $status;
    }
}