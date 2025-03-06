<?php
/*
 *
 * Добавление в корзину товара при клике
 * 
 *
*/
use Bitrix\Main\Loader;
use Bitrix\Sale;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
global $USER;
Loader::includeModule('catalog');
Loader::includeModule('iblock');
Loader::includeModule('sale');


if (isset($_REQUEST['id']) && intval($_REQUEST['id']) > 0) {
	$productId = intval($_REQUEST['id']);
} 
else {
	die('0');
}

if (isset($_REQUEST['quantity']) && intval($_REQUEST['quantity']) > 0) {
	$qty = intval($_REQUEST['quantity']);
} 
else {
	$qty = 1;
}
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());
    $basketItems = $basket->getBasketItems();
    foreach ($basketItems as $basketItem) {
        if($basketItem->getProductId() == $productId){
            $content['qtyprod'] = $basketItem->getQuantity();
        }
    }   
if($newid = Add2BasketByProductID($productId, $qty)) {
     
    $res = CIBlockElement::GetByID($productId);
	$ar_res  = CCatalogProduct::GetByID($productId);
    if ($el = $res->GetNext())
    {
        $content['name'] = $el['NAME'];
        $content['url'] = $el['DETAIL_PAGE_URL'];
        if ($el['PREVIEW_PICTURE'])
        {
            $file = CFile::ResizeImageGet(
                $el['PREVIEW_PICTURE'],
                array('width' => 70, 'height' => 90),
                BX_RESIZE_IMAGE_EXACT,
                false
            );
            $content['img'] = $file['src'];
        }
        if ($el['IBLOCK_SECTION_ID'])
        {
            $s = CIBlockSection::GetByID($el['IBLOCK_SECTION_ID']);
            if ($sn = $s->GetNext())
                $content['sect'] = $sn['NAME'];
        }
        $arPrice = CCatalogProduct::GetOptimalPrice($productId, $qty, $USER->GetUserGroupArray(), 'N');
        $content['price'] = format_price($arPrice['DISCOUNT_PRICE']).'&nbsp;<span class="rub">Р</span>';
    }
    
    $content['count'] = $qty;
	$max_count = CCatalogProduct::GetNearestQuantityPrice($productId);
    $content['max_count'] = $ar_res['QUANTITY'];
    $content['nid'] = $newid;
	die(json_encode($content));
} 
else {
	die('0');
}
