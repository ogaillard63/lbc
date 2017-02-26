<?php
/**
* @project		lbc
* @author		Olivier Gaillard
* @version		1.0 du 26/02/2017
* @desc			Controleur des objets : communes
*/

require_once( "inc/prepend.php" );
// Récupération des variables
$action			= Utils::get_input('action','both');
$page			= Utils::get_input('page','both');
$id				= Utils::get_input('id','both');
$name			= Utils::get_input('name','post');


$commune_manager = new CommuneManager($bdd);

switch($action) {
	
	case "add" :
		$smarty->assign("commune", new Commune(array("id" => -1)));
		$smarty->assign("content", "communes/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("commune", $commune_manager->getCommune($id));
		$smarty->assign("content","communes/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;


	case "save" :
		$data = array("cp" => $cp, "name" => $name);
		$commune_manager->saveCommune(new Commune($data));
		$log->notification($translate->__('the_commune_has_been_saved'));
		Utils::redirection("communes.php");
		break;

	case "delete" :
		$commune = $commune_manager->getCommune($id);
		if ($commune_manager->deleteCommune($commune)) {
			$log->notification($translate->__('the_commune_has_been_deleted'));
		}
		Utils::redirection("communes.php");
		break;

	default:
		$smarty->assign("titre", $translate->__('list_of_communes'));
		$rpp = 10;
		if (empty($page)) $page = 1; // Display first page
		$smarty->assign("communes", $commune_manager->getCommunesByPage($page, $rpp));
		$pagination = new Pagination($page, $commune_manager->getMaxCommunes(), $rpp);
		$smarty->assign("btn_nav", $pagination->getNavigation());

		$smarty->assign("content", "communes/list.tpl.html");
		$smarty->display("main.tpl.html");
}
require_once( "inc/append.php" );
?>