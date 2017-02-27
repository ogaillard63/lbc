<?php
/**
 * @project		WebApp Generator
 * @author		Olivier Gaillard
 * @version		1.0 du 04/06/2012
 * @desc	   	Accueil
 */

 
require_once( "inc/prepend.php" );
require_once( "parser.php" );

$location_manager = new LocationManager($bdd);



$url = 'https://www.leboncoin.fr/locations/offres/auvergne/puy_de_dome/?th=1&parrot=0&ret=1'; // &o=3
$content = file_get_contents($url);
$parser = new Parser($content);

$ads = $parser->getListData();
$cpt = 0;
foreach ($ads as $ad) {
   
   if (!$location_manager->locationExist($ad['uid'])) {
        $cpt ++; 
        $rawad = file_get_contents($ad['url']);
        $data = array(
                        "id" => -1,
                        "uid" => $ad['uid'], 
                        "title" => $ad['title'], 
                        "url" => $ad['url'], 
                        "addate" =>  $ad['date']->format('Y-m-d H:i:s'), 
                        "image" => $ad['image'], 
                        //"city" => $ad['location']['city'], 
                        
                        "loyer" =>  $parser->extractLoyer($rawad), 
                        "surface" => $parser->extractSurface($rawad),
                        "cp" => $parser->extractCp($rawad),
                        "ville" => $parser->extractVille($rawad),
                        "status" =>1 

                        );
            
       // var_dump($ad);
        var_dump($data);
       $location_manager->saveLocation(new Location($data));
      // if ($cpt >3) break;
   }

}

echo $cpt." annonce()s ajoutée(s)";



$smarty->assign("titre", "Homepage"); 
$smarty->assign("content", "misc/homepage.tpl.html");
$smarty->display("main.tpl.html");
?>