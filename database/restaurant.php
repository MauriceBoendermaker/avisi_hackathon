<?php


namespace database;

    // restaurants
    // ID INT
    // Naam VARCHAR(50)
    // Adres VARCHAR(50)
    // Email VARCHAR(50)
    // Telefoon VARCHAR(20)
    // Coordinaten VARCHAR(20)
    // Gewijzigd TIMESTAMP
class Restaurant {
    private $id;
    private $naam;
    private $adres;
    private $email;
    private $telefoon;
    private $coordinaten;
    private $gewijzigd;

    public function __construct($id, $naam, $adres, $email, $telefoon, $coordinaten, $gewijzigd) {
        $this->id = $id;
        $this->naam = $naam;
        $this->adres = $adres;
        $this->email = $email;
        $this->telefoon = $telefoon;
        $this->coordinaten = $coordinaten;
        $this->gewijzigd = $gewijzigd;
    }

    public function getID() {
        return $this->id;
    }

    public function getNaam() {
        return $this->naam;
    }

    public function getAdres() {
        return $this->adres;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelefoon() {
        return $this->telefoon;
    }

    public function getCoordinaten() {
        return $this->coordinaten;
    }

    public function getGewijzigd() {
        return $this->gewijzigd;
    }

    public function setID($id) {
        $this->id = $id;
    }

    public function setNaam($naam) {
        $this->naam = $naam;
    }

    public function setAdres($adres) {
        $this->adres = $adres;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelefoon($telefoon) {
        $this->telefoon = $telefoon;
    }

    public function setCoordinaten($coordinaten) {
        $this->coordinaten = $coordinaten;
    }

    public function setGewijzigd($gewijzigd) {
        $this->gewijzigd = $gewijzigd;
    }
}