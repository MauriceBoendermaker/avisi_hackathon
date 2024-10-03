<?php


namespace database;

// herbergen
// ID INT
// Naam VARCHAR(50)
// Adres VARCHAR(50)
// Email VARCHAR(100)
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
	private $cohort;
	private $crebonummer;
	private $geboortedatum;
	private $email;

	public function __construct($id, $volnaam, $klas, $cohort, $crebonummer, $geboortedatum)
	{
		$this->id = $id;
		$this->volnaam = $volnaam;
		$this->klas = $klas;
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