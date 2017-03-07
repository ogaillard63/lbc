<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 05/03/2017
* @desc			Gestion des communes
*/

class CommuneManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/**
	* Retourne l'objet commune correspondant à l'Id
	* @param $id
	*/
	public function getCommune($id) {
		$q = $this->bdd->prepare("SELECT * FROM communes WHERE id = :id");
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		return new Commune($q->fetch(PDO::FETCH_ASSOC));
	}


	/**
	* Retourne la commune correspondant au nom ou au code postal
	* @param $id
	*/
	public function searchCommune($cp, $name) {
		//echo "Add = ", $cp, " ",$name,  "<br/>";
		if (!empty($name)) {
			$q = $this->bdd->prepare("SELECT * FROM communes WHERE name = :name AND cp = :cp ");
			$q->bindValue(':name', $name, PDO::PARAM_STR);
		}
		else $q = $this->bdd->prepare("SELECT * FROM communes WHERE cp = :cp ");	
		$q->bindValue(':cp', $cp, PDO::PARAM_STR);
		$q->execute();
		$data = $q->fetch(PDO::FETCH_ASSOC);
		//var_dump($data);
		if ($data !=false) return new Commune($data);
		else die("Commune inconnue = $cp $name !");
	}

	/**
	* Retourne la liste des communes
	*/
	public function getCommunes($offset = null, $count = null) {
		$communes = array();
		if (isset($offset) && isset($count)) {
			$q = $this->bdd->prepare('SELECT * FROM communes ORDER BY name LIMIT :offset, :count');
			$q->bindValue(':offset', $offset, PDO::PARAM_INT);
			$q->bindValue(':count', $count, PDO::PARAM_INT);
		}
		else {
			$q = $this->bdd->prepare('SELECT * FROM communes ORDER BY id');
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
	* Recherche les communes
	*/
	public function searchCommunes($query) {
		$communes = array();
		$q = $this->bdd->prepare('SELECT * FROM communes 
			WHERE cp LIKE :query OR name LIKE :query OR lat LIKE :query OR lon LIKE :query OR distance LIKE :query OR duration LIKE :query');
		$q->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$communes[] = new Commune($data);
		}
		return $communes;
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
			$q = $this->bdd->prepare("DELETE FROM communes WHERE id = :id");
			$q->bindValue(':id', $commune->getId(), PDO::PARAM_INT);
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
		if ($commune->getId() == -1) {
			$q = $this->bdd->prepare('INSERT INTO communes SET cp = :cp, name = :name, lat = :lat, lon = :lon, distance = :distance, duration = :duration');
		} else {
			$q = $this->bdd->prepare('UPDATE communes SET cp = :cp, name = :name, lat = :lat, lon = :lon, distance = :distance, duration = :duration WHERE id = :id');
			$q->bindValue(':id', $commune->getId(), PDO::PARAM_INT);
		}
		$q->bindValue(':cp', $commune->getCp(), PDO::PARAM_STR);
		$q->bindValue(':name', $commune->getName(), PDO::PARAM_STR);
		$q->bindValue(':lat', $commune->getLat(), PDO::PARAM_STR);
		$q->bindValue(':lon', $commune->getLon(), PDO::PARAM_STR);
		$q->bindValue(':distance', $commune->getDistance(), PDO::PARAM_STR);
		$q->bindValue(':duration', $commune->getDuration(), PDO::PARAM_INT);


		$q->execute();
		if ($commune->getId() == -1) $commune->setId($this->bdd->lastInsertId());
	}

	/**
	 * Retourne une liste des communes formatés pour peupler un menu déroulant
	 */
	public function getCommunesForSelect() {
		$communes = array();
		$q = $this->bdd->prepare('SELECT id, name FROM communes ORDER BY id');
		$q->execute();
		while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$communes[$row["id"]] =  $row["name"];
		}
		return $communes;
	}
}
?>