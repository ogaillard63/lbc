<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 05/03/2017
* @desc			Objet commune
*/

class Commune {
	public $id;
	public $cp;
	public $name;
	public $lat;
	public $lon;
	public $distance;
	public $duration;


	public function __construct(array $data) {
		$this->hydrate($data);
	}

	public function hydrate(array $data){
		foreach ($data as $key => $value) {
			if (strpos($key, "_") !== false) {
				$method = 'set';
				foreach (explode("_", $key) as $part) {
					$method .= ucfirst($part);
				}
			}
			else $method = 'set'.ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
	}

	/* --- Getters et Setters --- */
	// id;
	public function setId($id) {
		$this->id = (integer)$id;
	}
	public function getId() {
		return $this->id;
	}
	// cp;
	public function setCp($cp) {
		$this->cp = $cp;
	}
	public function getCp() {
		return $this->cp;
	}
	// name;
	public function setName($name) {
		$this->name = $name;
	}
	public function getName() {
		return $this->name;
	}
	// lat;
	public function setLat($lat) {
		$this->lat = $lat;
	}
	public function getLat() {
		return $this->lat;
	}
	// lon;
	public function setLon($lon) {
		$this->lon = $lon;
	}
	public function getLon() {
		return $this->lon;
	}
	// distance;
	public function setDistance($distance) {
		$this->distance = $distance;
	}
	public function getDistance() {
		return $this->distance;
	}
	// duration;
	public function setDuration($duration) {
		$this->duration = (integer)$duration;
	}
	public function getDuration() {
		return $this->duration;
	}


}
?>
