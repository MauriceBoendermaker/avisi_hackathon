<?php


namespace database;

// pauzeplaatsen
// ID INT
// FKboekingenID INT (foreign key)
// FKrestaurantsID INT (foreign key)
// FKstatussenID INT (foreign key)

class Pauzeplaats
{
	private $id;
	private $boeking;
	private $restaurant;
	private $status;

	public function __construct($id, $boeking, $restaurant, $status)
	{
		$this->id = $id;
		$this->boeking = $boeking;
		$this->restaurant = $restaurant;
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

	public function getRestaurant()
	{
		return $this->restaurant;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setBoeking($boeking)
	{
		$this->boeking = $boeking;
	}

	public function setRestaurant($restaurant)
	{
		$this->restaurant = $restaurant;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}
}