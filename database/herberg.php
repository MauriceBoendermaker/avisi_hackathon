<?php


namespace database;

// herbergen
// ID INT
// Naam VARCHAR(50)
// Adres VARCHAR(50)
// Email VARCHAR(100)
// Telefoon VARCHAR(20)
// Coordinaten VARCHAR(20)
// Gewijzigd TIMESTAMP

class Herberg
{
	private $id;
	private $naam;
	private $adres;
	private $email;
	private $telefoon;
	private $coordinaten;
	private $gewijzigd;

	public function __construct($id, $naam, $adres, $email, $telefoon, $coordinaten, $gewijzigd)
	{
		$this->id = $id;
		$this->naam = $naam;
		$this->adres = $adres;
		$this->email = $email;
		$this->telefoon = $telefoon;
		$this->coordinaten = $coordinaten;
		$this->gewijzigd = $gewijzigd;
	}

	public function getID()
	{
		return $this->id;
	}

	public function setID($id)
	{
		$this->id = $id;
	}

	public function getNaam()
	{
		return $this->naam;
	}

	public function setNaam($naam)
	{
		$this->naam = $naam;
	}

	public function getAdres()
	{
		return $this->adres;
	}

	public function setAdres($adres)
	{
		$this->adres = $adres;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getTelefoon()
	{
		return $this->telefoon;
	}

	public function setTelefoon($telefoon)
	{
		$this->telefoon = $telefoon;
	}

	public function getCoordinaten()
	{
		return $this->coordinaten;
	}

	public function setCoordinaten($coordinaten)
	{
		$this->coordinaten = $coordinaten;
	}

	public function getGewijzigd()
	{
		return $this->gewijzigd;
	}

	public function setGewijzigd($gewijzigd)
	{
		$this->gewijzigd = $gewijzigd;
	}
}