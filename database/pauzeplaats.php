<?php


namespace database;

// pauzeplaatsen
// ID INT
// FKprojectenID INT (foreign key)
// FKbeheerdersID INT (foreign key)
// FKstatussenID INT (foreign key)

class Pauzeplaats
{
	private $id;
	private $project;
	private $beheerder;
	private $status;

	public function __construct($id, $project, $beheerder, $status)
	{
		$this->id = $id;
		$this->project = $project;
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

	public function getProject()
	{
		return $this->project;
	}

	public function getBeheerder()
	{
		return $this->beheerder;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setProject($project)
	{
		$this->project = $project;
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