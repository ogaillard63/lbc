<?php
/**
*
*/
//namespace LbcParser;
class Parser
{
  private $content = "";
  private $generalcategory = false;
  function __construct($content=false)
  {
    if ($content)
    {
      $this->content = $content;
    }
    $this->_cleancontent();
  }
  private function _cleancontent()
  {
    $this->content = preg_replace("(\n|\r)", "", $this->content);
    $this->content = preg_replace('/\s+/', " ", $this->content);
    return $this;
  }
  public function getListData()
  {
   // $i = 1;
    $items    = [];
    $rawitems = $this->_extractListItems();
    preg_match('/data-element="customSelect_categories">(.*?)<\/span>/s',$this->content,$match);
    $this->generalcategory = (isset($match[1]))?trim($match[1]):false;
    foreach ($rawitems as $rawitem)
    {
      //  $i++;
      // liste des annonces
      $item['uid']      = $this->_getUid($rawitem);
      $item['url']      = $this->_getUrl($rawitem);
      $item['image']    = $this->_getImage($rawitem);
      $item['price']    = $this->_getPrice($rawitem);
      $item['category'] = $this->_getCategory($rawitem);
      $item['location'] = $this->_getLocation($rawitem);
      $item['title']    = $this->_getTitle($rawitem);
      $item['date']     = $this->_getDate($rawitem);
      $item['ispro']    = $this->_isPro($rawitem);
      $item['isurgent'] = $this->_isUrgent($rawitem);

      $items[] = $item;
      //if ($i>3) break;
    }
    return $items;
  }
  
  private function _extractListItems()
  {
    preg_match_all('/<li itemscope itemtype="http:\/\/schema.org\/Offer">(.*?)<\/li>/', $this->content, $matches);
    //debug($matches[1][0]);
    return isset($matches[1])?$matches[1]:[];
  }

  private function _extractPageItems()
  {
    preg_match_all('/<li itemscope itemtype="http:\/\/schema.org\/Offer">(.*?)<\/li>/', $this->content, $matches);
    //debug($matches[1][0]);
    return isset($matches[1])?$matches[1]:[];
  }

  private function _isPro($string)
  {
    preg_match('/\(pro\)/s', $string, $match);
    return isset($match[0]);
  }
  private function _isUrgent($string)
  {
    preg_match('/item_supp emergency/s', $string, $match);
    return isset($match[0]);
  }
  private function _getUid($string)
  { // data-savead-id="1098977703">
    preg_match('/ data-savead-id="(.*?)"/s', $string, $match);
    return isset($match[1])?$match[1]:false;
  }
  private function _getUrl($string)
  {
    preg_match('/<a href="(.*?)"/s', $string, $match);
    return isset($match[1])?'https:'.$match[1]:false;
  }
  private function _getImage($string)
  {
    preg_match('/data-imgSrc="(.*?)"/s', $string, $match);
    if (isset($match[1]))
    {
      return 'https:' . str_replace('thumb','image',$match[1]);
    }
    return false;
  }
  private function _getPrice($string)
  {
    preg_match('/   ="price" content="(.*?)"/s', $string, $match);
    return isset($match[1])?$match[1]:false;
  }
  private function _getCategory($string)
  {
    preg_match('/itemprop="category" content="(.*?)"/s', $string, $match);
    return (isset($match[1]) && !empty($match[1]))?$match[1]:$this->generalcategory;
  }
  private function _getTitle($string)
  {
    preg_match('/title="(.*?)"/s', $string, $match);
    return isset($match[1])?$match[1]:false;
  }
  private function _getGeneralCategory($string)
  {
    preg_match('/data-element="customSelect_categories">(.*?)<\/span>/s', $string, $match);
    //debug($match);
    return isset($match[1])?trim($match[1]):false;
  }
  private function _getDate($string)
  {
    preg_match('/itemprop="availabilityStarts" content="(.*?)"/s',$string,$datematch);
    preg_match('/\s([0-9]{2}:[0-9]{2})\s/s',$string,$hourmatch);
    if (isset($datematch[1]))
    {
      return date_create_from_format('Y-m-d H:i' , $datematch[1] . " " . $hourmatch[1]);
    }
    return false;
  }
  private function _getLocation($string)
  {
    preg_match_all('/itemprop="address" content="(.*?)"/s', $string, $match);
    if (isset($match[1]))
    {
      if (count($match[1])==1)
      {
        return ['dpt' => $match[1][0] ];
      }
      if (count($match[1])==2)
      {
        return ['dpt' => $match[1][1],'city' => $match[1][0] ];
      }
    }
    return false;
  }


  public function extractCp($string)
  {
    preg_match('/<span class="value" itemprop="address">(.*?)<\/span>/s', $string, $match);
    if (isset($match[1])) {
      preg_match("/[0-9]{2,}/", $match[1], $output);
      return isset($output[0])?trim($output[0]):false;
    }
    return false;
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