<?php


namespace database;


// criterium
// ID INT
// Beschrijving VARCHAR(40)
class Tocht {
    private $id;
    private $beschrijving;

    public function __construct($id, $beschrijving) {
        $this->id = $id;
        $this->beschrijving = $beschrijving;
    }

    public function getID() {
        return $this->id;
    }

    public function getBeschrijving() {
        return $this->beschrijving;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function setBeschrijving($beschrijving) {
        $this->beschrijving = $beschrijving;
    }
}