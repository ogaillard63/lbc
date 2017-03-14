<?php
/**
 * @project		WebApp Generator
 * @author		Olivier Gaillard
 * @version		1.0 du 04/06/2012
 * @desc	   	Accueil
 */

//ini_set('max_execution_time', 300); //300 seconds = 5 minutes
 
require_once( "inc/prepend.php" );

$smarty->assign("msg", $log); 

$smarty->assign("titre", "Homepage"); 
$smarty->assign("content", "misc/homepage.tpl.html");
$smarty->display("main.tpl.html");
?>