<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Objet vente
*/

class Vente {
	public $id;
	public $uid;
	public $addate;
	public $title;
	public $url;
	public $image;
	public $cp;
	public $ville;
	public $prix;
	public $surface;
	public $status;
	public $commune;


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
	// uid;
	public function setUid($uid) {
		$this->uid = $uid;
	}
	public function getUid() {
		return $this->uid;
	}
	// addate;
	public function setAddate($addate) {
		$this->addate = $addate;
	}
	public function getAddate() {
		return $this->addate;
	}
	// title;
	public function setTitle($title) {
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	// url;
	public function setUrl($url) {
		$this->url = $url;
	}
	public function getUrl() {
		return $this->url;
	}
	// image;
	public function setImage($image) {
		$this->image = $image;
	}
	public function getImage() {
		return $this->image;
	}
	// cp;
	public function setCp($cp) {
		$this->cp = $cp;
	}
	public function getCp() {
		return $this->cp;
	}
	// ville;
	public function setVille($ville) {
		$this->ville = $ville;
	}
	public function getVille() {
		return $this->ville;
	}
	// prix;
	public function setPrix($prix) {
		$this->prix = (integer)$prix;
	}
	public function getPrix() {
		return $this->prix;
	}
	// surface;
	public function setSurface($surface) {
		$this->surface = (integer)$surface;
	}
	public function getSurface() {
		return $this->surface;
	}

	// status;
	public function setStatus($status) {
		$this->status = (integer)$status;
	}
	public function getStatus() {
		return $this->status;
	}
	// commune;
	public function setCommune($commune) {
		$this->commune = $commune;
	}
	public function getCommune() {
		return $this->commune;
	}
}
?>
