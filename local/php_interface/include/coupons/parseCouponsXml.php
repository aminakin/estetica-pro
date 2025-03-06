<?php
/**
 * Created by PhpStorm.
 * User: l0rd
 * Date: 19.10.18
 * Time: 13:59
 */

class parseCouponsXml
{
    public function parseXml($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        $z = new XMLReader;

        $yt = $z->open($file);

        while ($z->nodeType != XMLReader::ELEMENT || $z->localName != 'data') {
            if (!$z->read()) {
                $z->close();
                return false;
            }
        }
        $xmlcoupons = [];
        if ($z->localName == 'data')
            do {
                if ($z->localName == 'data' && $z->nodeType == XMLReader::ELEMENT) {
                    $str = $z->readOuterXML();
                    $sxml = simplexml_load_string($str);
                    $coupon = $this->load_coupons($sxml);
                    $xmlCoupons[$coupon['id']] = $coupon;
                }
            } while ($z->next());
        $z->close();
        return $xmlCoupons;
    }

    private function load_coupons($sxml)
    {
        $arData = (array)$sxml;
        return ['id' => $arData['CardNumber'], 'procent' => $arData['discount']];
    }
} 