<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Gestion des locations
*/

class LocationManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/**
	* Retourne l'objet location correspondant à l'Id
	* @param $id
	*/
	public function getLocation($id) {
		$q = $this->bdd->prepare("SELECT * FROM locations WHERE id = :id");
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		return new Location($q->fetch(PDO::FETCH_ASSOC));
	}
	/**
	* Test si l'objet correspondant à l'Uid existe en Bdd
	* @param $uid
	*/	
	public function locationExist($uid) {
		$q = $this->bdd->prepare("SELECT count(*) FROM locations WHERE uid = :uid");
		$q->bindValue(':uid', $uid, PDO::PARAM_STR);
		$q->execute();
		return (intval($q->fetch(PDO::FETCH_COLUMN))>0)?true:false;
	}

	/**
	* Retourne la liste des locations
	*/
	public function getLocations($offset = null, $count = null) {
		$locations = array();
		if (isset($offset) && isset($count)) {
			$q = $this->bdd->prepare('SELECT * FROM locations WHERE status = "1" ORDER BY addate DESC LIMIT :offset, :count');
			$q->bindValue(':offset', $offset, PDO::PARAM_INT);
			$q->bindValue(':count', $count, PDO::PARAM_INT);
		}
		else {
			$q = $this->bdd->prepare('SELECT * FROM locations ORDER BY id');
		}

		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$locations[] = new Location($data);
			// $commune_manager = new CommuneManager($this->bdd);
			// $location->setCommune($commune_manager->getCommune($location->getCp()));
			// $locations[] = $location;
		}
		return $locations;
	}

	/**
	 * Retourne la liste des locations par page
	 */
	 public function getLocationsByPage($page_num, $count) {
		return $this->getLocations(($page_num-1)*$count, $count);
	 }
	/**
	* Recherche les locations
	*/
	public function searchLocations($query) {
		$locations = array();
		$q = $this->bdd->prepare('SELECT * FROM locations 
			WHERE status = "1"  AND (title LIKE :query OR ville LIKE :query OR cp LIKE :query)');
		$q->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$locations[] = new Location($data);
			// $commune_manager = new CommuneManager($this->bdd);
			// $location->setCommune($commune_manager->getCommune($location->getCp()));
			// $locations[] = $location;
		}
		return $locations;
	}

	/**
	 * Retourne le nombre max de locations
	 */
	public function getMaxLocations() {
		$q = $this->bdd->prepare('SELECT count(1) FROM locations');
		$q->execute();
		return intval($q->fetch(PDO::FETCH_COLUMN));
	}

	/**
	* Efface l'objet location de la bdd
	* @param Location $location
	*/
	public function deleteLocation(Location $location) {
		try {	
			$q = $this->bdd->prepare("DELETE FROM locations WHERE id = :id");
			$q->bindValue(':id', $location->getId(), PDO::PARAM_INT);
			return $q->execute();
			}
		catch( PDOException $Exception ) {
			return false;
		}
	}

	/**
	* Enregistre l'objet location en bdd
	* @param Location $location
	*/
	public function saveLocation(Location $location) {
		var_dump($location);
		if ($location->getId() == -1) {
			$q = $this->bdd->prepare('INSERT INTO locations SET uid = :uid, addate = :addate, title = :title, url = :url, image = :image, cp = :cp, ville = :ville, loyer = :loyer, surface = :surface, status = :status');
		} else {
			$q = $this->bdd->prepare('UPDATE locations SET uid = :uid, addate = :addate	, title = :title, url = :url, image = :image, cp = :cp, ville = :ville, loyer = :loyer, surface = :surface, status = :status WHERE id = :id');
			$q->bindValue(':id', $location->getId(), PDO::PARAM_INT);
		}
		$q->bindValue(':uid', $location->getUid(), PDO::PARAM_STR);
		$q->bindValue(':addate', $location->getAddate(), PDO::PARAM_STR);
		$q->bindValue(':title', $location->getTitle(), PDO::PARAM_STR);
		$q->bindValue(':url', $location->getUrl(), PDO::PARAM_STR);
		$q->bindValue(':image', $location->getImage(), PDO::PARAM_STR);
		$q->bindValue(':cp', $location->getCp(), PDO::PARAM_STR);
		$q->bindValue(':ville', $location->getVille(), PDO::PARAM_STR);
		$q->bindValue(':loyer', $location->getLoyer(), PDO::PARAM_INT);
		$q->bindValue(':surface', $location->getSurface(), PDO::PARAM_INT);
		$q->bindValue(':status', $location->getStatus(), PDO::PARAM_INT);

		$q->execute();
		if ($location->getId() == -1) $location->setId($this->bdd->lastInsertId());
	}

}
?>