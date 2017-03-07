<?php
/**
* Extraction spécifique des données pour la rubrique Voiture
*/

//namespace LbcParser;

class ParserVoiture extends ParserLbc {

// -------------------------------------------------------------------------------------- //
// Extraction des données dans la page de l'annonce
// -------------------------------------------------------------------------------------- //

  
  public function extractPrix($html) {
   // <h2 class="item_price clearfix" itemprop="price" content="12990">
    preg_match('/itemprop="price" content="(.*?)">/s', $html, $match);
    return isset($match[1])?trim($match[1]):false;
  }

  public function extractMarque($html) {
    // <span class="value" itemprop="brand">Citroen</span>
    preg_match('/<span class=\"value\" itemprop=\"brand\">(.*?)<\/span>/s',$html, $match);
    return isset($match[1])?trim($match[1]):false;
  }

  public function extractModele($html) {
    // <span class="value" itemprop="model">C3</span>
    preg_match('/<span class=\"value\" itemprop=\"model\">(.*?)<\/span>/s',$html, $match);
    return isset($match[1])?trim($match[1]):false;
  }

  public function extractAnnee($html) {
    /// <span class="value" itemprop="releaseDate">2015</span>
    preg_match('/<span class=\"value\" itemprop=\"releaseDate\">(.*?)<\/span>/s',$html, $match);
    return isset($match[1])?trim($match[1]):false;
  }
  public function extractKm($html) {
    // <span class="property">Kilométrage</span><span class="value">35 000 KM</span>
    preg_match('/<span class="property">Kilom(.*?)trage<\/span>(.*?)<\/span>/s',$html, $match);
    return isset($match[2])?trim(strip_tags($match[2])):false;
  }
  public function extractCarburant($html) {
    // <span class="property">Kilométrage</span><span class="value">Diesel</span>
    preg_match('/<span class="property">Carburant<\/span>(.*?)<\/span>/s',$html, $match);
    return isset($match[1])?trim(strip_tags($match[1])):false;
  }
  public function extractBv($html) {
    // <span class="property">Boîte de vitesse</span><span class="value">Manuelle</span>
   preg_match('/<span class="property">Bo(.*?)te de vitesse<\/span>(.*?)<\/span>/s',$html, $match);
    return isset($match[2])?trim(strip_tags($match[2])):false;
  }
}