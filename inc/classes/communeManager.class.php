<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Gestion des communes
*/

class CommuneManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/**
	* Retourne l'objet commune correspondant à l'cp
	* @param $cp
	*/
	public function getCommune($cp) {
		$q = $this->bdd->prepare("SELECT * FROM communes WHERE cp = :cp");
		$q->bindValue(':cp', $cp, PDO::PARAM_INT);
		$q->execute();
		return new Commune($q->fetch(PDO::FETCH_ASSOC));
	}

	/**
	* Retourne la liste des communes
	*/
	public function getCommunes($offset = null, $count = null) {
		$communes = array();
		if (isset($offset) && isset($count)) {
			$q = $this->bdd->prepare('SELECT * FROM communes ORDER BY cp DESC LIMIT :offset, :count');
			$q->bindValue(':offset', $offset, PDO::PARAM_INT);
			$q->bindValue(':count', $count, PDO::PARAM_INT);
		}
		else {
			$q = $this->bdd->prepare('SELECT * FROM communes ORDER BY cp');
		}

		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$communes[] = new Commune($data);
		}
		return $communes;
	}

	/**
	 * Retourne la liste des communes par page
	 */
	 public function getCommunesByPage($page_num, $count) {
		return $this->getCommunes(($page_num-1)*$count, $count);
	 }

	/**
	 * Retourne le nombre max de communes
	 */
	public function getMaxCommunes() {
		$q = $this->bdd->prepare('SELECT count(1) FROM communes');
		$q->execute();
		return intval($q->fetch(PDO::FETCH_COLUMN));
	}

	/**
	* Efface l'objet commune de la bdd
	* @param Commune $commune
	*/
	public function deleteCommune(Commune $commune) {
		try {	
			$q = $this->bdd->prepare("DELETE FROM communes WHERE cp = :cp");
			$q->bindValue(':cp', $commune->getcp(), PDO::PARAM_INT);
			return $q->execute();
			}
		catch( PDOException $Exception ) {
			return false;
		}
	}

	/**
	* Enregistre l'objet commune en bdd
	* @param Commune $commune
	*/
	public function saveCommune(Commune $commune) {
		$q = $this->bdd->prepare('INSERT INTO communes SET cp = :cp, name = :name');
		$q->bindValue(':cp', $commune->getcp(), PDO::PARAM_STR);
		$q->bindValue(':name', $commune->getName(), PDO::PARAM_STR);
		$q->execute();
	}

	/**
	 * Retourne une liste des communes formatés pour peupler un menu déroulant
	 */
	public function getCommunesForSelect() {
		$communes = array();
		$q = $this->bdd->prepare('SELECT cp, name FROM communes ORDER BY cp');
		$q->execute();
		while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$communes[$row["cp"]] =  $row["name"];
		}
		return $communes;
	}
}
?>