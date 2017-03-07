<?php
/**
 * @project		WebApp Generator
 * @author		Olivier Gaillard
 * @version		1.0 du 04/06/2012
 * @desc	   	Carte
 */


require_once( "inc/prepend.php" );
// Récupération des variables
$action			= Utils::get_input('action','both');
$markers = array();

if (empty($action) && null !== $session->getValue("action")) $action = $session->getValue("action");

switch($action) {
	
	case "show_ventes" :
		$q = $bdd->prepare('SELECT  c.id, c.name, c.lat, c.lon FROM ventes AS l, communes AS c 
			WHERE l.status = "1" AND l.ville = c.name GROUP BY c.id');
		$q->execute();
		
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) $markers[] = $data;
		$session->setValue("referer", "map.php?action=show_ventes");
		$session->setValue("action", "show_ventes");
		$smarty->assign("search_link", "ventes.php?action=search_commune");
		$smarty->assign("marker_img", "marker_blue.png");
		$smarty->assign("title", "Carte des annonces (Ventes)");
		break;

	case "show_locations" :
	default:
		$q = $bdd->prepare('SELECT  c.id, c.name, c.lat, c.lon FROM locations AS l, communes AS c 
			WHERE l.status = "1" AND l.ville = c.name GROUP BY c.id');
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) $markers[] = $data;

		//var_dump($markers);
		$session->setValue("referer", "map.php?action=show_locations");
		$session->setValue("action", "show_locations");
		$smarty->assign("search_link", "locations.php?action=search_commune");
		$smarty->assign("marker_img", "marker_green.png");
		$smarty->assign("title", "Carte des annonces (Locations)");

}

$smarty->assign("markers", $markers);
$smarty->assign("content", "misc/map.tpl.html");
$smarty->display("main.tpl.html");
?>