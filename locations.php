<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Controleur des objets : locations
*/

require_once( "inc/prepend.php" );
// Récupération des variables
$action			= Utils::get_input('action','both');
$page			= Utils::get_input('page','both');
$id				= Utils::get_input('id','both');
$uid			= Utils::get_input('uid','post');
$title			= Utils::get_input('title','post');
$url			= Utils::get_input('url','post');
$image			= Utils::get_input('image','post');
$cp				= Utils::get_input('cp','post');
$loyer			= Utils::get_input('loyer','post');
$surface		= Utils::get_input('surface','post');
$query			= Utils::get_input('query','both');

$location_manager = new LocationManager($bdd);

switch($action) {
	
	case "add" :
		$smarty->assign("location", new Location(array("id" => -1)));
		$smarty->assign("content", "locations/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("location", $location_manager->getLocation($id));
		$smarty->assign("content","locations/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search" :
		$smarty->assign("content","locations/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_commune" :
		$commune_manager = new CommuneManager($bdd);
		$commune = $commune_manager->getCommune($id);
		$smarty->assign("locations", $location_manager->searchLocationsByCommune($commune->name));
		$smarty->assign("query",$commune->name);
		$smarty->assign("referer", $session->getValue("referer"));
		$smarty->assign("content","locations/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_results" :
		if (strlen($query) > 2) {
			$smarty->assign("locations", $location_manager->searchLocations($query));
		}
		else {
			$log->notification($translate->__('query_too_short'));
			Utils::redirection("locations.php?action=search");
		}
		$smarty->assign("query",$query);
		$smarty->assign("content","locations/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;


	case "save" :
		$data = array("id" => $id, "uid" => $uid, "title" => $title, "url" => $url, "image" => $image, "cp" => $cp, "loyer" => $loyer, "surface" => $surface);
		$location_manager->saveLocation(new Location($data));
		$log->notification($translate->__('the_location_has_been_saved'));
		Utils::redirection("locations.php");
		break;

	case "delete" :
		$location = $location_manager->getLocation($id);
		$location->setStatus(0);
		$location_manager->saveLocation($location);
/*
		if ($location_manager->deleteLocation($location)) {
			$log->notification($translate->__('the_location_has_been_deleted'));
		}
*/		
		if ($session->getValue("referer")) Utils::redirection($session->getValue("referer"));
		else Utils::redirection("locations.php?page={$page}");
		break;

	default:
		$smarty->assign("titre", $translate->__('list_of_locations'));
		$rpp = 30;
		if (empty($page)) $page = 1; // Display first page
		$smarty->assign("locations", $location_manager->getLocationsByPage($page, $rpp));
		$pagination = new Pagination($page, $location_manager->getMaxLocations(), $rpp);
		$smarty->assign("btn_nav", $pagination->getNavigation());
		$smarty->assign("page", $page);
		$session->setValue("referer", "locations.php");
		$smarty->assign("content", "locations/list.tpl.html");
		$smarty->display("main.tpl.html");
}
require_once( "inc/append.php" );
?>