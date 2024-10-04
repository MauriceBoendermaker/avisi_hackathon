<?php


namespace database;

// statussen
// ID INT
// StatusCode TINYINT(4)
// Status VARCHAR(40)
// Verwijderbaar BIT
// PINtoekennen BIT

class Criterium
{
	private $id;
	private $beschrijving;

	public function __construct($id, $beschrijving)
	{
		$this->id = $id;
		$this->beschrijving = $beschrijving;
	}

	public function getID()
	{
		return $this->id;
	}

	public function getBeschrijving()
	{
		return $this->beschrijving;
	}

	public function setID($id)
	{
		$this->id = $id;
	}

	public function setBeschrijving($beschrijving)
	{
		$this->statusCode = $statusCode;
	}
}