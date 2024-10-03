<?php


namespace database;

// statussen
// ID INT
// StatusCode TINYINT(4)
// Status VARCHAR(40)
// Verwijderbaar BIT
// PINtoekennen BIT

class Status
{
	private $id;
	private $statusCode;
	private $status;
	private $verwijderbaar;
	private $pintoekeennen;

	public function __construct($id, $statusCode, $status, $verwijderbaar, $pintoekeennen)
	{
		$this->id = $id;
		$this->statusCode = $statusCode;
		$this->status = $status;
		$this->verwijderbaar = $verwijderbaar;
		$this->pintoekeennen = $pintoekeennen;
	}

	public function getID()
	{
		return $this->id;
	}

	public function getStatusCode()
	{
		return $this->statusCode;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getVerwijderbaar()
	{
		return $this->verwijderbaar;
	}

	public function getPintoekennen()
	{
		return $this->pintoekeennen;
	}

	public function setID($id)
	{
		$this->id = $id;
	}

	public function setStatusCode($statusCode)
	{
		$this->statusCode = $statusCode;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function setVerwijderbaar($verwijderbaar)
	{
		$this->verwijderbaar = $verwijderbaar;
	}

	public function setPintoekennen($pintoekeennen)
	{
		$this->pintoekeennen = $pintoekeennen;
	}
}