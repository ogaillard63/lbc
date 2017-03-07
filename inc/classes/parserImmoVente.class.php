<?php
/**
* Extraction spécifique des données pour la rubrique Immobilier / Ventes
*/

//namespace LbcParser;

class ParserImmoVente extends ParserLbc {

// -------------------------------------------------------------------------------------- //
// Extraction des données dans la page de l'annonce
// -------------------------------------------------------------------------------------- //

  public function extractPrix($string)
  {
    preg_match('/itemprop="price" content="(.*?)">/s', $string, $match);
    return isset($match[1])?trim($match[1]):false;
  }
  public function extractLoyer($string)
  {
    preg_match('/itemprop="price" content="(.*?)">/s', $string, $match);
    return isset($match[1])?trim($match[1]):false;
  }
    public function extractSurface($string)
  {
    preg_match('/<span class="property">Surface<\/span>(.*?) m<sup>2<\/sup><\/span>/s',$string, $match);
    return isset($match[1])?trim(strip_tags($match[1])):false;
  }
}