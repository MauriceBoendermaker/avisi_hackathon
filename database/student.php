<?php


namespace database;

// studenten
// ID INT
// Naam VARCHAR(50)
// Adres VARCHAR(50)
// Email VARCHAR(100)
// Wachtwoord VARCHAR(100)
// cohort VARCHAR(20)
// Coordinaten VARCHAR(20)
// Gewijzigd TIMESTAMP

//- [ ] Studentnummer (uniek)
//- [ ] Voornaam, tussenvoegsel en achternaam
//- [ ] Klas
//- [ ] Cohort (het jaar waarin de student start met de opleiding)
//- [ ] Crebonummer (nummer van het kwalificatiedossier waarvoor de student wordt opgeleid)
//- [ ] Geboortedatum 

class student
{
	private $id;
	private $volnaam;
	private $klas;
	private $wachtwoord;
	private $cohort;
	private $crebonummer;
	private $geboortedatum;
	private $email;

	public function __construct($id, $volnaam, $klas, $cohort, $crebonummer, $geboortedatum)
	{
		$this->id = $id;
		$this->volnaam = $volnaam;
		$this->klas = $klas;
		$this->wachtwoord = "8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918"; // admin
		$this->cohort = $cohort;
		$this->crebonummer = $crebonummer;
		$this->geboortedatum = $geboortedatum;
		$this->email = $this->id . "@rijnijssel.nl";
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
		return $this->volnaam;
	}

	public function setNaam($naam)
	{
		$this->volnaam = $naam;
	}

	public function getKlas()
	{
		return $this->klas;
	}

	public function getWachtwoord()
	{
		return $this->wachtwoord;
	}

	public function setKlas($klas)
	{
		$this->klas = $klas;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function setWachtwoord($wachtwoord)
	{
		$this->wachtwoord = $wachtwoord;
	}

	public function getCohort()
	{
		return $this->cohort;
	}

	public function setCohort($cohort)
	{
		$this->cohort = $cohort;
	}

	public function getCrebo()
	{
		return $this->crebonummer;
	}

	public function setCrebo($crebo)
	{
		$this->crebonummer = $crebo;
	}

	public function getGeboorte()
	{
		return $this->geboortedatum;
	}

	public function setGewijzigd($geboorte)
	{
		$this->geboortedatum = $geboorte;
	}
}