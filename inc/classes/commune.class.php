<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Objet name
*/

class Commune {
	public $cp;
	public $name;


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


}
?>
