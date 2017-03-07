<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 06/03/2017
* @desc			Controleur des objets : voitures
*/

require_once( "inc/prepend.php" );
// Récupération des variables
$action			= Utils::get_input('action','both');
$page			= Utils::get_input('page','both');
$id				= Utils::get_input('id','both');
$addate			= Utils::get_input('addate','post');
$uid			= Utils::get_input('uid','post');
$title			= Utils::get_input('title','post');
$url			= Utils::get_input('url','post');
$image			= Utils::get_input('image','post');
$cp			= Utils::get_input('cp','post');
$ville			= Utils::get_input('ville','post');
$prix			= Utils::get_input('prix','post');
$marque			= Utils::get_input('marque','post');
$modele			= Utils::get_input('modele','post');
$annee			= Utils::get_input('annee','post');
$km			= Utils::get_input('km','post');
$carburant			= Utils::get_input('carburant','post');
$bv			= Utils::get_input('bv','post');
$status			= Utils::get_input('status','post');

$query			= Utils::get_input('query','post');

$voiture_manager = new VoitureManager($bdd);

switch($action) {
	
	case "add" :
		$smarty->assign("voiture", new Voiture(array("id" => -1)));
		$smarty->assign("content", "voitures/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("voiture", $voiture_manager->getVoiture($id));
		$smarty->assign("content","voitures/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search" :
		$smarty->assign("content","voitures/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_results" :
		if (strlen($query) > 2) {
			$smarty->assign("voitures", $voiture_manager->searchVoitures($query));
		}
		else {
			$log->notification($translate->__('query_too_short'));
			Utils::redirection("voitures.php?action=search");
		}
		$smarty->assign("query",$query);
		$smarty->assign("content","voitures/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "save" :
		$data = array("id" => $id, "addate" => $addate, "uid" => $uid, "title" => $title, 
		"url" => $url, "image" => $image, "cp" => $cp, "ville" => $ville, "prix" => $prix, 
		"marque" => $marque, "modele" => $modele, "annee" => $annee, "km" => $km, 
		"carburant" => $carburant, "bv" => $bv, "status" => $status);
		$voiture_manager->saveVoiture(new Voiture($data));
		$log->notification($translate->__('the_voiture_has_been_saved'));
		Utils::redirection("voitures.php");
		break;

	case "delete" :
		$voiture = $voiture_manager->getVoiture($id);
		if ($voiture_manager->deleteVoiture($voiture)) {
			$log->notification($translate->__('the_voiture_has_been_deleted'));
		}
		Utils::redirection("voitures.php");
		break;

	default:
		$smarty->assign("titre", $translate->__('list_of_voitures'));
		$rpp = 30;
		if (empty($page)) $page = 1; // Display first page
		$smarty->assign("voitures", $voiture_manager->getVoituresByPage($page, $rpp));
		$pagination = new Pagination($page, $voiture_manager->getMaxVoitures(), $rpp);
		$smarty->assign("btn_nav", $pagination->getNavigation());
		$smarty->assign("page", $page);
		$smarty->assign("content", "voitures/list.tpl.html");
		$smarty->display("main.tpl.html");
}
require_once( "inc/append.php" );
?>