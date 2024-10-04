<?php
//
//
//namespace database;
//
//
//// overnachtingen
//// ID INT
//// FKprojectenID INT (foreign key)
//// FKstudentenID INT (foreign key)
//// FKstatussenID INT (foreign key)
//class Overnachting {
//    private $id;
//    private $fkprojectenid;
//    private $fkstudentenid;
//    private $fkstatussenid;
//
//    public function __construct($id, $fkprojectenid, $fkstudentenid, $fkstatussenid) {
//        $this->id = $id;
//        $this->fkprojectenid = $fkprojectenid;
//        $this->fkstudentenid = $fkstudentenid;
//        $this->fkstatussenid = $fkstatussenid;
//    }
//
//    public function getID() {
//        return $this->id;
//    }
//
//    public function getProject() {
//        return $this->fkprojectenid;
//    }
//
//    public function getStudent() {
//        return $this->fkstudentenid;
//    }
//
//    public function getStatus() {
//        return $this->fkstatussenid;
//    }
//
//    public function setID($id) {
//        $this->id = $id;
//    }
//
//    public function setProject($project) {
//        $this->fkprojectenid = $project;
//    }
//
//    public function setStudent($student) {
//        $this->fkstudentenid = $student;
//    }
//
//    public function setStatus($status) {
//        $this->fkstatussenid = $status;
//    }
//}