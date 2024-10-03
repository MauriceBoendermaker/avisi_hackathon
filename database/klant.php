<?php

namespace database;

// klanten
// ID INT
// Naam VARCHAR(50)
// Email VARCHAR(100)
// Telefoon VARCHAR(20)
// Wachtwoord VARCHAR(100)
// gebruikersrechten INT (foreign key)
// Gewijzigd TIMESTAMP

class Klant
{
	private $id;
	private $naam;
	private $email;
	private $telefoon;
	private $wachtwoord;
	private $gebruikersrechten;
	private $gewijzigd;

	public function __construct($id, $naam, $email, $telefoon, $wachtwoord, $gebruikersrechten, $gewijzigd)
	{
		$this->id = $id;
		$this->naam = $naam;
		$this->email = $email;
		$this->telefoon = $telefoon;
		$this->wachtwoord = $wachtwoord;
		$this->gebruikersrechten = $gebruikersrechten;
		$this->gewijzigd = $gewijzigd;
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

	public function getTelefoon()
	{
		return $this->telefoon;
	}

	public function getWachtwoord()
	{
		return $this->wachtwoord;
	}

	public function getGebruikersrechten()
	{
		// if gebruikersrechten is an integer, get gebruikersrechten from database
		if (is_int($this->gebruikersrechten)) {
			$db = new Database("localhost", "root", "", "donkey", null);
			$this->gebruikersrechten = $db->getGebruikersrechtByID($this->gebruikersrechten);
		}
		return $this->gebruikersrechten;
	}

	public function getGewijzigd()
	{
		return $this->gewijzigd;
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

	public function setTelefoon($telefoon)
	{
		$this->telefoon = $telefoon;
	}

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
		$db = new Database("localhost", "root", "", "donkey", null);
		$this->setID($db->applyKlant($this, true));
	}
}