<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 06/03/2017
* @desc			Objet voiture
*/

class Voiture {
	public $id;
	public $addate;
	public $uid;
	public $title;
	public $url;
	public $image;
	public $cp;
	public $ville;
	public $prix;
	public $marque;
	public $modele;
	public $annee;
	public $km;
	public $carburant;
	public $bv;
	public $status;


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
	// addate;
	public function setAddate($addate) {
		$this->addate = $addate;
	}
	public function getAddate() {
		return $this->addate;
	}
	// uid;
	public function setUid($uid) {
		$this->uid = $uid;
	}
	public function getUid() {
		return $this->uid;
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
	// marque;
	public function setMarque($marque) {
		$this->marque = $marque;
	}
	public function getMarque() {
		return $this->marque;
	}
	// modele;
	public function setModele($modele) {
		$this->modele = $modele;
	}
	public function getModele() {
		return $this->modele;
	}
	// annee;
	public function setAnnee($annee) {
		$this->annee = $annee;
	}
	public function getAnnee() {
		return $this->annee;
	}
	// km;
	public function setKm($km) {
		$this->km = $km;
	}
	public function getKm() {
		return $this->km;
	}
	// carburant;
	public function setCarburant($carburant) {
		$this->carburant = $carburant;
	}
	public function getCarburant() {
		return $this->carburant;
	}
	// bv;
	public function setBv($bv) {
		$this->bv = $bv;
	}
	public function getBv() {
		return $this->bv;
	}
	// status;
	public function setStatus($status) {
		$this->status = $status;
	}
	public function getStatus() {
		return $this->status;
	}


}
?>
