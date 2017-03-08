<?php
/**
 * @project		WebApp Generator
 * @author		Olivier Gaillard
 * @version		1.0 du 04/06/2012
 * @desc	   	Accueil
 */

//ini_set('max_execution_time', 300); //300 seconds = 5 minutes
 
require_once( "inc/prepend.php" );
$action			= Utils::get_input('action','both');
$cpt = 0;
$log = "";

switch($action) {
	
	case "update_locations" :
        $location_manager = new LocationManager($bdd);
        $url = 'https://www.leboncoin.fr/locations/offres/auvergne/puy_de_dome/?th=1&parrot=0&mre=900&ret=1'; // &o=3
        $parser = new parserImmoLocation(file_get_contents($url));
        $ads = $parser->getListData();
        foreach ($ads as $ad) {
            if (!$location_manager->locationExist($ad['uid'])) {
                    $html = $parser->extractPageItem($ad['url']); // recupere le contenu de l'annonce
                    $data = array(
                                    "id" => -1,
                                    "uid" => $ad['uid'], 
                                    "title" => $ad['title'], 
                                    "url" => $ad['url'], 
                                    "addate" =>  $ad['date']->format('Y-m-d H:i:s'), 
                                    "image" => $ad['image'], 
                                    "loyer" =>  $parser->extractLoyer($html), 
                                    "surface" => $parser->extractSurface($html),
                                    "cp" => $parser->extractCp($html),
                                    "ville" => $parser->extractVille($html),
                                    "status" => 1 
                                    );
                        
                // var_dump($ad);
                if (!empty($data["cp"])) {
                    $location_manager->saveLocation(new Location($data));
                    $cpt ++; 
                }
                // if ($cpt >3) break;
            }
        $log = $cpt. " location(s) trouvée(s)";
        }
		break;

	case "update_ventes" :
        $vente_manager = new VenteManager($bdd);
        $url = 'https://www.leboncoin.fr/ventes_immobilieres/offres/auvergne/puy_de_dome/?th=1&parrot=0&pe=9&ret=1'; // &o=3
        $parser = new parserImmoVente(file_get_contents($url));
        $ads = $parser->getListData();
        foreach ($ads as $ad) {
            if (!$vente_manager->venteExist($ad['uid'])) {
                    $html = $parser->extractPageItem($ad['url']); // recupere le contenu de l'annonce
                    $data = array(
                                    "id" => -1,
                                    "uid" => $ad['uid'], 
                                    "title" => $ad['title'], 
                                    "url" => $ad['url'], 
                                    "addate" =>  $ad['date']->format('Y-m-d H:i:s'), 
                                    "image" => $ad['image'], 
                                    "prix" =>  $parser->extractPrix($html), 
                                    "surface" => $parser->extractSurface($html),
                                    "cp" => $parser->extractCp($html),
                                    "ville" => $parser->extractVille($html),
                                    "status" => 1 
                                    );
                        
                // var_dump($ad);
                if (!empty($data["cp"])) {
                    $vente_manager->saveVente(new Vente($data));
                    $cpt ++; 
                }
                // if ($cpt >3) break;
            }
        $log = $cpt. " vente(s) trouvée(s)";
        }

		break;

	case "update_voitures" :
        $voiture_manager = new VoitureManager($bdd);
        $url = 'https://www.leboncoin.fr/voitures/offres/auvergne/puy_de_dome/?th=1&parrot=0&pe=27&rs=2012&me=40000&gb=1'; // &o=3
        $parser = new parserVoiture(file_get_contents($url));
        $ads = $parser->getListData();
        foreach ($ads as $ad) {
            if (!$voiture_manager->voitureExist($ad['uid'])) {
                    $html = $parser->extractPageItem($ad['url']); // recupere le contenu de l'annonce
                    if ($html != false) {
                        $data = array(
                                        "id" => -1,
                                        "uid" => $ad['uid'], 
                                        "title" => $ad['title'], 
                                        "url" => $ad['url'], 
                                        "addate" =>  $ad['date']->format('Y-m-d H:i:s'), 
                                        "image" => $ad['image'], 
                                        "prix" =>  $parser->extractPrix($html), 
                                        "marque" => $parser->extractMarque($html),
                                        "modele" =>  $parser->extractModele($html), 
                                        "annee" =>  $parser->extractAnnee($html), 
                                        "km" =>  $parser->extractKm($html), 
                                        "carburant" =>  $parser->extractCarburant($html), 
                                        "bv" =>  $parser->extractBv($html), 
                                        "cp" => $parser->extractCp($html),
                                        "ville" => $parser->extractVille($html),
                                        "status" => 1 
                                        );
                            
                   // var_dump($data);
                    if (!empty($data["cp"])) {
                        $voiture_manager->saveVoiture(new Voiture($data));
                        $cpt ++; 
                    }
                 }
                // if ($cpt >3) break;
            }
        $log = $cpt. " voiture(s) trouvée(s)";
        }

		break;

// velo
// https://www.leboncoin.fr/velos/offres/auvergne/puy_de_dome/?th=1&q=-enfant%20-selle%20-derailleur%20-%2216%20pouces%22%20-chaussures%20-protections%20-lunettes%20-draisienne%20-porte%20-elliptique%20-trotinette%20-trottinette&it=1&parrot=0&ps=2&pe=8
// https://www.leboncoin.fr/velos/offres/auvergne/puy_de_dome/?th=1&q=%20V.T.T%20OR%20%20vtt%20OR%20decathlon%20OR%20VTC%20-enfant%20-gar%E7on%20-fille%20-fillette%20-chaussure%20-draisienne%20-elliptique%20-siege%20-cintre%20-pneu%20-casque&it=1&parrot=0&ps=2&pe=8
}


$smarty->assign("msg", $log); 

$smarty->assign("titre", "Homepage"); 
$smarty->assign("content", "misc/homepage.tpl.html");
$smarty->display("main.tpl.html");
?>