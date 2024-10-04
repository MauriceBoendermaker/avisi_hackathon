<?php

namespace database;

// docenten
// ID INT
// Naam VARCHAR(50)
// Email VARCHAR(100)
// Afkorting VARCHAR(10)
// Wachtwoord VARCHAR(100)
// Gebruikersrechten INT

class Docent
    {
        private $id;
        private $naam;
        private $email;
        private $afkorting;
        private $wachtwoord;
        private $gebruikersrechten;
        private $gewijzigd;
    
        public function __construct($id, $naam, $email, $wachtwoord, $gebruikersrechten, $gewijzigd)
        {
            $this->id = $id;
            $this->naam = $naam;
            $this->email = $email;
            $name_parts = explode(" ", $naam);

            // Afkorting van docent craften
            //$first_name = $name_parts[0];
            //$last_name = $name_parts[1];
            //$last_name_part = substr($last_name, 0, 3);
            //$first_name_part = substr($first_name, 0, 2);

            $this->afkorting = "AFKort";
            $this->wachtwoord = "8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918"; // $wachtwoord;
            $this->gebruikersrechten = $gebruikersrechten;
        }
    
        public function getID()
        {
            return $this->id;
        }
    
        public function getNaam()
        {
            return $this->naam;
        }
    
        public function getEmail()
        {
            return $this->email;
        }
    
        public function getAfkorting()
        {
            return $this->afkorting;
        }
    
        public function getWachtwoord()
        {
            return $this->wachtwoord;
        }
    
        public function getGewijzigd()
        {
            return $this->gewijzigd;
        }
    
        public function getGebruikersrechten()
        {
            // if gebruikersrechten is an integer, get gebruikersrechten from database
    
            if (is_int($this->gebruikersrechten)) {
                $db = new Database("localhost", "root", "", "learnflow", null);
                $this->gebruikersrechten = $db->getGebruikersrechtByID($this->gebruikersrechten);
            }
            return $this->gebruikersrechten;
        }
    
    
        public function setID($id)
        {
            $this->id = $id;
        }
    
        public function setNaam($naam)
        {
            $this->naam = $naam;
        }
    
        public function setEmail($email)
        {
            $this->email = $email;
        }
    
        // public function setAfkorting($afkorting)
        // {
        // 	$this->afkorting = $afkorting;
        // }
    
        public function setWachtwoord($wachtwoord)
        {
            $this->wachtwoord = $wachtwoord;
        }
    
        public function setGebruikersrechten($gebruikersrechten)
        {
            $this->gebruikersrechten = $gebruikersrechten;
        }
    
        public function setGewijzigd($gewijzigd)
        {
            $this->gewijzigd = $gewijzigd;
        }
    
        public function save()
        {
            $db = new Database("localhost", "root", "", "learnflow", null);
            $this->setID($db->applyDocent($this, true));
        }
    }
    