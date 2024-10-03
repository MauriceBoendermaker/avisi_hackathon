<?php


namespace database;

// trackers
// ID INT
// PINCode INT
// Lat DOUBLE
// Lon DOUBLE
// Time BIGINT(20)
class Tracker {
    private $id;
    private $pincode;
    private $lat;
    private $lon;
    private $time;

    public function __construct($id, $pincode, $lat, $lon, $time) {
        $this->id = $id;
        $this->pincode = $pincode;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->time = $time;
    }

    public function getID() {
        return $this->id;
    }

    public function getPincode() {
        return $this->pincode;
    }

    public function getLat() {
        return $this->lat;
    }

    public function getLon() {
        return $this->lon;
    }

    public function getTime() {
        return $this->time;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function setPincode($pincode) {
        $this->pincode = $pincode;
    }

    public function setLat($lat) {
        $this->lat = $lat;
    }

    public function setLon($lon) {
        $this->lon = $lon;
    }

    public function setTime($time) {
        $this->time = $time;
    }
}