<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Gestion des ventes
*/

class VenteManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/**
	* Retourne l'objet vente correspondant à l'Id
	* @param $id
	*/
	public function getVente($id) {
		$q = $this->bdd->prepare("SELECT * FROM ventes WHERE id = :id");
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		return new Vente($q->fetch(PDO::FETCH_ASSOC));
	}
	/**
	* Test si l'objet correspondant à l'Uid existe en Bdd
	* @param $uid
	*/	
	public function venteExist($uid) {
		$q = $this->bdd->prepare("SELECT count(*) FROM ventes WHERE uid = :uid");
		$q->bindValue(':uid', $uid, PDO::PARAM_STR);
		$q->execute();
		return (intval($q->fetch(PDO::FETCH_COLUMN))>0)?true:false;
	}

	/**
	* Retourne la liste des ventes
	*/
	public function getVentes($offset = null, $count = null) {
		$ventes = array();
		if (isset($offset) && isset($count)) {
			$q = $this->bdd->prepare('SELECT * FROM ventes WHERE status = "1" ORDER BY addate DESC LIMIT :offset, :count');
			$q->bindValue(':offset', $offset, PDO::PARAM_INT);
			$q->bindValue(':count', $count, PDO::PARAM_INT);
		}
		else {
			$q = $this->bdd->prepare('SELECT * FROM ventes ORDER BY id');
		}

		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$vente = new Vente($data);
			$commune_manager = new CommuneManager($this->bdd);
			$commune = $commune_manager->searchCommune($vente->getCp(), $vente->getVille());
			if (is_object($commune)) $vente->setCommune($commune);
			$ventes[] = $vente;
		}
		return $ventes;
	}

	/**
	 * Retourne la liste des ventes par page
	 */
	 public function getVentesByPage($page_num, $count) {
		return $this->getVentes(($page_num-1)*$count, $count);
	 }
	/**
	* Recherche les ventes
	*/
	public function searchVentes($query) {
		$ventes = array();
		$q = $this->bdd->prepare('SELECT * FROM ventes 
			WHERE status = "1" AND (title LIKE :query OR ville LIKE :query OR cp LIKE :query)');
		$q->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$vente = new Vente($data);
			$commune_manager = new CommuneManager($this->bdd);
			$commune = $commune_manager->searchCommune($vente->getCp(), $vente->getVille());
			if (is_object($commune)) $vente->setCommune($commune);
			$ventes[] = $vente;
		}
		return $ventes;
	}

	/**
	* Recherche les ventes pour une ville
	*/
	public function searchVentesByCommune($commune) {
		$ventes = array();
		$q = $this->bdd->prepare('SELECT * FROM ventes WHERE status = "1" AND ville LIKE :commune');
		$q->bindValue(':commune', $commune, PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$vente = new Vente($data);
			$commune_manager = new CommuneManager($this->bdd);
			$commune = $commune_manager->searchCommune($vente->getCp(), $vente->getVille());
			if (is_object($commune)) $vente->setCommune($commune);
			$ventes[] = $vente;
		}
		return $ventes;
	}
	/**
	 * Retourne le nombre max de ventes
	 */
	public function getMaxVentes() {
		$q = $this->bdd->prepare('SELECT count(1) FROM ventes WHERE status = "1"');
		$q->execute();
		return intval($q->fetch(PDO::FETCH_COLUMN));
	}

	/**
	* Efface l'objet vente de la bdd
	* @param Vente $vente
	*/
	public function deleteVente(Vente $vente) {
		try {	
			$q = $this->bdd->prepare("DELETE FROM ventes WHERE id = :id");
			$q->bindValue(':id', $vente->getId(), PDO::PARAM_INT);
			return $q->execute();
			}
		catch( PDOException $Exception ) {
			return false;
		}
	}

	/**
	* Enregistre l'objet vente en bdd
	* @param Vente $vente
	*/
	public function saveVente(Vente $vente) {
		var_dump($vente);
		if ($vente->getId() == -1) {
			$q = $this->bdd->prepare('INSERT INTO ventes SET uid = :uid, addate = :addate, title = :title, url = :url, image = :image, cp = :cp, ville = :ville, prix = :prix, surface = :surface, status = :status');
		} else {
			$q = $this->bdd->prepare('UPDATE ventes SET uid = :uid, addate = :addate	, title = :title, url = :url, image = :image, cp = :cp, ville = :ville, prix = :prix, surface = :surface, status = :status WHERE id = :id');
			$q->bindValue(':id', $vente->getId(), PDO::PARAM_INT);
		}
		$q->bindValue(':uid', $vente->getUid(), PDO::PARAM_STR);
		$q->bindValue(':addate', $vente->getAddate(), PDO::PARAM_STR);
		$q->bindValue(':title', $vente->getTitle(), PDO::PARAM_STR);
		$q->bindValue(':url', $vente->getUrl(), PDO::PARAM_STR);
		$q->bindValue(':image', $vente->getImage(), PDO::PARAM_STR);
		$q->bindValue(':cp', $vente->getCp(), PDO::PARAM_STR);
		$q->bindValue(':ville', $vente->getVille(), PDO::PARAM_STR);
		$q->bindValue(':prix', $vente->getprix(), PDO::PARAM_INT);
		$q->bindValue(':surface', $vente->getSurface(), PDO::PARAM_INT);
		$q->bindValue(':status', $vente->getStatus(), PDO::PARAM_INT);

		$q->execute();
		if ($vente->getId() == -1) $vente->setId($this->bdd->lastInsertId());
	}

}
?>