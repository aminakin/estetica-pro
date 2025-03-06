<?php
class couponsImport {
  private $XMLFILE = '';
  private $xmlCoupons=[];

  function __construct() {
    $this->XMLFILE = $_SERVER['DOCUMENT_ROOT'] . '/upload/from1c/discount.xml';
  }

  public function start() {
    /*парсим xml*/
    $xmlCoup = new parseCouponsXml();
    $arrXmlCoupons = $xmlCoup->parseXml($this->XMLFILE);
    $this->setXmlCoupons($arrXmlCoupons);


    /*Актуализируем скидки для купонов*/
    $discountVal = $this->calculateXmlDiscount();
    $discount = new siteDiscount();
    $discounts = $discount->rebuildDiscount($discountVal);

    /*Достаем текущие купоны и актуализируем*/
    $siteCoupons = new siteCoupons();
    $siteCoupons->rebuildCoupons($this->getXmlCoupons(), $discounts);
  }

  public function setXmlCoupons($xmlCoupons) {
    $this->xmlCoupons = $xmlCoupons;
  }
  public function getXmlCoupons() {
    return $this->xmlCoupons;
  }

  private function calculateXmlDiscount() {
    $discountVal = [];
    foreach($this->xmlCoupons as $coupon) {
      $discountVal[$coupon['procent']] = '|k'.$coupon['procent'];
    }
    return $discountVal;
  }

}