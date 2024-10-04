<?php


namespace database;


class Project
{
	private $id;
	private $startDatum;
	private $pincode;
	private $criterium;
	private $docent;
	private $status;
	private $tracker;

	public function __construct($id, $startDatum, $pincode, $criterium, $docent, $status, $tracker)
	{
		$this->id = $id;
		$this->startDatum = $startDatum;
		$this->pincode = $pincode;
		$this->criterium = $criterium;
		$this->docent = $docent;
		$this->status = $status;
		$this->tracker = $tracker;
	}

	// -= Get functions =-

	public function getID()
	{
		return $this->id;
	}

	public function getStartDatum()
	{
		return $this->startDatum;
	}

	public function getEindDatum($project)
	{
		return date('Y-m-d', strtotime($project->getStartdatum()));
	}

	public function getPincode()
	{
		return $this->pincode;
	}

	public function getCriterium()
	{
		return $this->criterium;
	}

	public function getDocent()
	{
		return $this->docent;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function getTracker()
	{
		return $this->tracker;
	}

	// -= Set functions =-
	public function setID($id)
	{
		$this->id = $id;
	}

	public function setStartDatum($startDatum)
	{
		$this->startDatum = $startDatum;
	}

	public function setPincode($pincode)
	{
		$this->pincode = $pincode;
	}

	public function setTocht($tocht)
	{
		$this->tocht = $tocht;
	}

	public function setDocent($docent)
	{
		$this->docent = $docent;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}

	public function setTracker($tracker)
	{
		$this->tracker = $tracker;
	}
}
