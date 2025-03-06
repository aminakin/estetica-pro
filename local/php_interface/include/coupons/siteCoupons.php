<?php
/**
 * Created by PhpStorm.
 * User: l0rd
 * Date: 19.10.18
 * Time: 14:20
 */
use Bitrix\Sale\Internals;
class siteCoupons {
  private static $currentCoupons=[];
/*
 * array ( 1 => array ( 'ID' => '1', 'DISCOUNT_ID' => '5', 'ACTIVE' => 'Y', 'ACTIVE_FROM' => NULL, 'ACTIVE_TO' => NULL, 'COUPON' => '2100000015504', 'TYPE' => '4', 'MAX_USE' => '0', 'USE_COUNT' => '0', 'USER_ID' => '0', 'DATE_APPLY' => NULL, 'TIMESTAMP_X' => Bitrix\Main\Type\DateTime::__set_state(array( 'value' => DateTime::__set_state(array( 'date' => '2018-10-19 10:25:31.000000', 'timezone_type' => 3, 'timezone' => 'Etc/GMT-3', )), )), 'MODIFIED_BY' => '1', 'DATE_CREATE' => Bitrix\Main\Type\DateTime::__set_state(array( 'value' => DateTime::__set_state(array( 'date' => '2018-10-19 06:27:32.000000', 'timezone_type' => 3, 'timezone' => 'Etc/GMT-3', )), )), 'CREATED_BY' => '1', 'DESCRIPTION' => '|card', ), )
 * */

  public function rebuildCoupons($xmlCoupons, $discounts) {
    $coupons = $this->getCoupons();
    $coupons = $this->coupounAddProcent($coupons, $discounts);
    $compareResult = $this->compareCoupons($xmlCoupons, $coupons);
    if (isset($compareResult['del'])) {
      $this->deleteCoupons($compareResult['del']);
    }
    if (isset($compareResult['add'])) {
      $this->addCoupons($compareResult['add'], $discounts);
    }
   /* if (isset($compareResult['upd'])) {
      $this->updateCoupons($compareResult['upd'], $discounts);
    }*/
    //$coupons = $this->getCoupons();
  }

  private function coupounAddProcent($coupons, $discounts) {
    foreach($coupons as &$counpon) {
      if (isset($discounts[$counpon['DISCOUNT_ID']])) {
        $discount = $discounts[$counpon['DISCOUNT_ID']];
        $procent = preg_replace('/\D+/','',$discount[0]);
        $counpon['PROCENT'] = $procent;
      }
    }
    return $coupons;
  }

  private function deleteCoupons($coupons) {
    foreach($coupons as $id) {
      $result = Internals\DiscountCouponTable::delete($id);
    }
  }

//  private function updateCoupons($coupons, $discounts) {
//    foreach($coupons as $coupon) {
//      $fields['DISCOUNT_ID'] = $this->detectDiscount($coupon, $discounts);
//      $fields['COUPON'] = $coupon[0];
//      $fields['ACTIVE'] = 'Y';
//      $fields['TYPE'] = 4;
//      $fields['DESCRIPTION'] = '|card';
//      Internals\DiscountCouponTable::update($coupon[3],$fields);
//    }
//  }
  private function addCoupons($coupons, $discounts) {
    foreach($coupons as $coupon) {
      $fields['DISCOUNT_ID'] = $this->detectDiscount($coupon, $discounts);
      $fields['COUPON'] = $coupon[0];
      $fields['ACTIVE'] = 'Y';
      $fields['TYPE'] = 4;
      $fields['DESCRIPTION'] = '|card';
      Internals\DiscountCouponTable::add($fields);
    }
  }

  private function detectDiscount($coupon, $discounts) {
    foreach($discounts as $discount) {
      if ($discount[0] == '|k' . $coupon[1]){
        return $discount[1];
      }
    }
    return false;
  }

  private function compareCoupons($xmlCoupons, $coupons) {
    $result=[];
    foreach($coupons as $coupon){
      $notOnDel = false;
      foreach($xmlCoupons as $xmlCoupon){
        if ($coupon['COUPON'] == $xmlCoupon['id'] && isset($coupon['PROCENT']) && $coupon['PROCENT'] == $xmlCoupon['procent']) {
          $notOnDel = true;
        }
        if ($coupon['COUPON'] == $xmlCoupon['id'] && isset($coupon['PROCENT']) && $coupon['PROCENT'] != $xmlCoupon['procent']) {
          $result['add'][] = [$xmlCoupon['id'],$xmlCoupon['procent']];
        }
      }
      if (!$notOnDel) {
        $result['del'][] = $coupon['ID'];
      }
    }

    foreach ($xmlCoupons as $xmlCoupon) {
      $notOnAdd = false;
      foreach ($coupons as $coupon) {
        if ($coupon['COUPON'] == $xmlCoupon['id']) {
          $notOnAdd = true;
          //$result['upd'][] = [$coupon['COUPON'],preg_replace('/[^\|]+(\|k\d+)/','$1',$coupon['COUPON']),$coupon['DISCOUNT_ID'], $coupon['ID']];
        }
      }
      if (!$notOnAdd) {
        $result['add'][] = [$xmlCoupon['id'],$xmlCoupon['procent']];
      }
    }
    return $result;
  }
  public function getCoupons() {
    $coupons = $this->getAllCoupounsDB();
    $result = [];
    foreach($coupons as $coupon) {
      $result[] = ['ID' => $coupon['ID'],'DISCOUNT_ID' => $coupon['DISCOUNT_ID'],'COUPON' => $coupon['COUPON'],];
    }
    return $result;
  }

  private function getAllCoupounsDB()
  {
    $couponIterator = Internals\DiscountCouponTable::getList(
        array(
            'select' => array('*'),
            'filter' => array('%=DESCRIPTION' => '%|card%'),
        )
    );
    $coupons = [];
    while ($coupon = $couponIterator->fetch()) {
      $coupons[$coupon['ID']] = $coupon;
    }

    return $coupons;
  }
} 