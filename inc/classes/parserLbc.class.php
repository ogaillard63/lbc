<?php
/**
*
*/

//namespace LbcParser;
class ParserLbc {
  protected $content = "";
  protected $generalcategory = false;
  function __construct($content=false)  {
    if ($content) $this->content = $content;
    $this->_cleancontent();
  }
  
  protected function _cleancontent() {
    $this->content = preg_replace("(\n|\r)", "", $this->content);
    $this->content = preg_replace('/\s+/', " ", $this->content);
    return $this;
  }
  
  public function getListData() {
    $items    = [];
    $rawitems = $this->_extractListItems();
    preg_match('/data-element="customSelect_categories">(.*?)<\/span>/s',$this->content,$match);
    $this->generalcategory = (isset($match[1]))?trim($match[1]):false;
    foreach ($rawitems as $rawitem) {
      // liste des annonces
      $item['uid']      = $this->_getUid($rawitem);
      $item['url']      = $this->_getUrl($rawitem);
      $item['image']    = $this->_getImage($rawitem);
      //$item['price']    = $this->_getPrice($rawitem);
      $item['category'] = $this->_getCategory($rawitem);
      $item['title']    = $this->_getTitle($rawitem);
      $item['date']     = $this->_getDate($rawitem);
      //$item['isurgent'] = $this->_isUrgent($rawitem);
      $items[] = $item;
    }
    return $items;
  }
  
  protected function _extractListItems() {
    preg_match_all('/<li itemscope itemtype="http:\/\/schema.org\/Offer">(.*?)<\/li>/', $this->content, $matches);
    return isset($matches[1])?$matches[1]:[];
  }

  public function extractPageItem($url) {
    $content = file_get_contents($url); // recupere la page de l'annonce
    //preg_match('/<section class="properties lineNegative">(.*?)<\/section>/', $content, $matches);
    preg_match_all("/<div class=\"line\">(.*?)<div class=\"line properties_description\">/s", $content, $matches);
    return isset($matches[0][0])?$matches[0][0]:false;
    //return $content;
  }

// -------------------------------------------------------------------------------------- //
// Récupére les données dans la liste des annonces
// -------------------------------------------------------------------------------------- //

  protected function _isPro($string) {
    preg_match('/\(pro\)/s', $string, $match);
    return isset($match[0]);
  }
  
  protected function _isUrgent($string) {
    preg_match('/item_supp emergency/s', $string, $match);
    return isset($match[0]);
  }
  
  protected function _getUid($string) { // data-savead-id="1098977703">
    preg_match('/ data-savead-id="(.*?)"/s', $string, $match);
    return isset($match[1])?$match[1]:false;
  }
  
  protected function _getUrl($string) {
    preg_match('/<a href="(.*?)"/s', $string, $match);
    return isset($match[1])?'https:'.$match[1]:false;
  }
  
  protected function _getImage($string) {
    preg_match('/data-imgSrc="(.*?)"/s', $string, $match);
    if (isset($match[1])) {
      return 'https:' . str_replace('thumb','image',$match[1]);
    }
    return false;
  }
  
  protected function _getCategory($string) {
    preg_match('/itemprop="category" content="(.*?)"/s', $string, $match);
    return (isset($match[1]) && !empty($match[1]))?$match[1]:$this->generalcategory;
  }
  
  protected function _getTitle($string) {
    preg_match('/title="(.*?)"/s', $string, $match);
    return isset($match[1])?$match[1]:false;
  }
  
  protected function _getGeneralCategory($string) {
    preg_match('/data-element="customSelect_categories">(.*?)<\/span>/s', $string, $match);
    //debug($match);
    return isset($match[1])?trim($match[1]):false;
  }
  
  protected function _getDate($string) {
    preg_match('/itemprop="availabilityStarts" content="(.*?)"/s',$string,$datematch);
    preg_match('/\s([0-9]{2}:[0-9]{2})\s/s',$string,$hourmatch);
    if (isset($datematch[1]))
    {
      return date_create_from_format('Y-m-d H:i' , $datematch[1] . " " . $hourmatch[1]);
    }
    return false;
  }


// -------------------------------------------------------------------------------------- //
// Extraction des données dans la page de l'annonce
// -------------------------------------------------------------------------------------- //

    public function extractCp($string) {
    preg_match('/<span class="value" itemprop="address">(.*?)<\/span>/s', $string, $match);
    if (isset($match[1])) {
      preg_match("/(.*?)\s?([0-9]{5}?)/", $match[1], $output);
      return isset($output[2])?trim($output[2]):false;
    }
    return false;
  }
    
    public function extractVille($string) {
    preg_match('/<span class="value" itemprop="address">(.*?)<\/span>/s', $string, $match);
    if (isset($match[1])) {
      preg_match("/(.*?)\s?([0-9]{5}?)/", $match[1], $output);
      return isset($output[1])?trim(utf8_encode($output[1])):false;
    }
    return false;
  }
}