<?php


namespace database;

// pauzeplaatsen
// ID INT
// FKboekingenID INT (foreign key)
// FKbeheerdersID INT (foreign key)
// FKstatussenID INT (foreign key)

class Pauzeplaats
{
	private $id;
	private $boeking;
	private $beheerder;
	private $status;

	public function __construct($id, $boeking, $beheerder, $status)
	{
		$this->id = $id;
		$this->boeking = $boeking;
		$this->beheerder = $beheerder;
		$this->status = $status;
	}

	public function getID()
	{
		return $this->id;
	}

	public function setID($id)
	{
		$this->id = $id;
	}

	public function getBoeking()
	{
		return $this->boeking;
	}

	public function getBeheerder()
	{
		return $this->beheerder;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setBoeking($boeking)
	{
		$this->boeking = $boeking;
	}

	public function setBeheerder($beheerder)
	{
		$this->beheerder = $beheerder;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
}