<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Controleur des objets : ventes
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
$cp			= Utils::get_input('cp','post');
$prix			= Utils::get_input('prix','post');
$surface			= Utils::get_input('surface','post');

$query			= Utils::get_input('query','post');

$vente_manager = new VenteManager($bdd);

switch($action) {
	
	case "add" :
		$smarty->assign("vente", new Vente(array("id" => -1)));
		$smarty->assign("content", "ventes/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("vente", $vente_manager->getVente($id));
		$smarty->assign("content","ventes/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search" :
		$smarty->assign("content","ventes/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_commune" :
		$commune_manager = new CommuneManager($bdd);
		$commune = $commune_manager->getCommune($id);
		$smarty->assign("ventes", $vente_manager->searchVentesByCommune($commune->name));
		$smarty->assign("query",$commune->name);
		$smarty->assign("referer", $session->getValue("referer"));
		$smarty->assign("content","ventes/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_results" :
		if (strlen($query) > 2) {
			$smarty->assign("ventes", $vente_manager->searchVentes($query));
		}
		else {
			$log->notification($translate->__('query_too_short'));
			$session->setValue("referer", "ventes.php?action=search");
			Utils::redirection("ventes.php?action=search");
		}
		$smarty->assign("query",$query);
		$smarty->assign("content","ventes/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "save" :
		$data = array("id" => $id, "uid" => $uid, "title" => $title, "url" => $url, "image" => $image, "cp" => $cp, "prix" => $prix, "surface" => $surface);
		$vente_manager->saveVente(new Vente($data));
		$log->notification($translate->__('the_vente_has_been_saved'));
		Utils::redirection("ventes.php");
		break;

	case "delete" :
		$vente = $vente_manager->getVente($id);
		$vente->setStatus(0);
		$vente_manager->saveVente($vente);
/*
		if ($vente_manager->deleteVente($vente)) {
			$log->notification($translate->__('the_vente_has_been_deleted'));
		}
*/
		if ($session->getValue("referer")) Utils::redirection($session->getValue("referer"));
		Utils::redirection("ventes.php?page={$page}");
		break;

	default:
		$smarty->assign("titre", $translate->__('list_of_ventes'));
		$rpp = 30;
		if (empty($page)) $page = 1; // Display first page
		$smarty->assign("ventes", $vente_manager->getVentesByPage($page, $rpp));
		$pagination = new Pagination($page, $vente_manager->getMaxVentes(), $rpp);
		$smarty->assign("btn_nav", $pagination->getNavigation());
		$smarty->assign("page", $page);
		$session->setValue("referer", "ventes.php");
		$smarty->assign("content", "ventes/list.tpl.html");
		$smarty->display("main.tpl.html");
}
require_once( "inc/append.php" );
?>