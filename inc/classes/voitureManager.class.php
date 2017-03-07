<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 06/03/2017
* @desc			Gestion des voitures
*/

class VoitureManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/**
	* Retourne l'objet voiture correspondant à l'Id
	* @param $id
	*/
	public function getVoiture($id) {
		$q = $this->bdd->prepare("SELECT * FROM voitures WHERE id = :id");
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		return new Voiture($q->fetch(PDO::FETCH_ASSOC));
	}
	
	/**
	* Test si l'objet correspondant à l'Uid existe en Bdd
	* @param $uid
	*/	
	public function voitureExist($uid) {
		$q = $this->bdd->prepare("SELECT count(*) FROM voitures WHERE uid = :uid");
		$q->bindValue(':uid', $uid, PDO::PARAM_STR);
		$q->execute();
		return (intval($q->fetch(PDO::FETCH_COLUMN))>0)?true:false;
	}

	/**
	* Retourne la liste des voitures
	*/
	public function getVoitures($offset = null, $count = null) {
		$voitures = array();
		if (isset($offset) && isset($count)) {
			$q = $this->bdd->prepare('SELECT * FROM voitures ORDER BY id DESC LIMIT :offset, :count');
			$q->bindValue(':offset', $offset, PDO::PARAM_INT);
			$q->bindValue(':count', $count, PDO::PARAM_INT);
		}
		else {
			$q = $this->bdd->prepare('SELECT * FROM voitures ORDER BY id');
		}

		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$voitures[] = new Voiture($data);
		}
		return $voitures;
	}

	/**
	 * Retourne la liste des voitures par page
	 */
	 public function getVoituresByPage($page_num, $count) {
		return $this->getVoitures(($page_num-1)*$count, $count);
	 }
	/**
	* Recherche les voitures
	*/
	public function searchVoitures($query) {
		$voitures = array();
		$q = $this->bdd->prepare('SELECT * FROM voitures 
			WHERE title LIKE :query OR cp LIKE :query OR ville LIKE :query 
			OR marque LIKE :query OR modele LIKE :query OR annee LIKE :query 
			OR km LIKE :query OR carburant LIKE :query OR bv LIKE :query');
		$q->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$voitures[] = new Voiture($data);
		}
		return $voitures;
	}

	/**
	 * Retourne le nombre max de voitures
	 */
	public function getMaxVoitures() {
		$q = $this->bdd->prepare('SELECT count(1) FROM voitures');
		$q->execute();
		return intval($q->fetch(PDO::FETCH_COLUMN));
	}

	/**
	* Efface l'objet voiture de la bdd
	* @param Voiture $voiture
	*/
	public function deleteVoiture(Voiture $voiture) {
		try {	
			$q = $this->bdd->prepare("DELETE FROM voitures WHERE id = :id");
			$q->bindValue(':id', $voiture->getId(), PDO::PARAM_INT);
			return $q->execute();
			}
		catch( PDOException $Exception ) {
			return false;
		}
	}

	/**
	* Enregistre l'objet voiture en bdd
	* @param Voiture $voiture
	*/
	public function saveVoiture(Voiture $voiture) {
		if ($voiture->getId() == -1) {
			$q = $this->bdd->prepare('INSERT INTO voitures SET addate = :addate, uid = :uid, 
			title = :title, url = :url, image = :image, cp = :cp, ville = :ville, prix = :prix, 
			marque = :marque, modele = :modele, annee = :annee, km = :km, carburant = :carburant, bv = :bv, status = :status');
		} else {
			$q = $this->bdd->prepare('UPDATE voitures SET addate = :addate, uid = :uid, 
			title = :title, url = :url, image = :image, cp = :cp, ville = :ville, prix = :prix, 
			marque = :marque, modele = :modele, annee = :annee, km = :km, 
			carburant = :carburant, bv = :bv, status = :status WHERE id = :id');
			$q->bindValue(':id', $voiture->getId(), PDO::PARAM_INT);
		}
		$q->bindValue(':addate', $voiture->getAddate(), PDO::PARAM_STR);
		$q->bindValue(':uid', $voiture->getUid(), PDO::PARAM_STR);
		$q->bindValue(':title', $voiture->getTitle(), PDO::PARAM_STR);
		$q->bindValue(':url', $voiture->getUrl(), PDO::PARAM_STR);
		$q->bindValue(':image', $voiture->getImage(), PDO::PARAM_STR);
		$q->bindValue(':cp', $voiture->getCp(), PDO::PARAM_STR);
		$q->bindValue(':ville', $voiture->getVille(), PDO::PARAM_STR);
		$q->bindValue(':prix', $voiture->getPrix(), PDO::PARAM_INT);
		$q->bindValue(':marque', $voiture->getMarque(), PDO::PARAM_STR);
		$q->bindValue(':modele', $voiture->getModele(), PDO::PARAM_STR);
		$q->bindValue(':annee', $voiture->getAnnee(), PDO::PARAM_STR);
		$q->bindValue(':km', $voiture->getKm(), PDO::PARAM_STR);
		$q->bindValue(':carburant', $voiture->getCarburant(), PDO::PARAM_STR);
		$q->bindValue(':bv', $voiture->getBv(), PDO::PARAM_STR);
		$q->bindValue(':status', $voiture->getStatus(), PDO::PARAM_STR);


		$q->execute();
		if ($voiture->getId() == -1) $voiture->setId($this->bdd->lastInsertId());
	}

	/**
	 * Retourne une liste des voitures formatés pour peupler un menu déroulant
	 */
	public function getVoituresForSelect() {
		$voitures = array();
		$q = $this->bdd->prepare('SELECT id, name FROM voitures ORDER BY id');
		$q->execute();
		while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$voitures[$row["id"]] =  $row["name"];
		}
		return $voitures;
	}
}
?>