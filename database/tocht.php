<?php


namespace database;


// tochten
// ID INT
// Omschrijving VARCHAR(40)
// Route VARCHAR(50)
// AantalDagen INT
class Tocht {
    private $id;
    private $omschrijving;
    private $route;
    private $aantaldagen;

    public function __construct($id, $omschrijving, $route, $aantaldagen) {
        $this->id = $id;
        $this->omschrijving = $omschrijving;
        $this->route = $route;
        $this->aantaldagen = $aantaldagen;
    }

    public function getID() {
        return $this->id;
    }

    public function getOmschrijving() {
        return $this->omschrijving;
    }

    public function getRoute() {
        return $this->route;
    }

    public function getAantaldagen() {
        return $this->aantaldagen;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function setOmschrijving($omschrijving) {
        $this->omschrijving = $omschrijving;
    }

    public function setRoute($route) {
        $this->route = $route;
    }

    public function setAantaldagen($aantaldagen) {
        $this->aantaldagen = $aantaldagen;
    }
}