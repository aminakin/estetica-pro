<?php
/*
 *
 * Получаем массив состояния корзины
 *
 * {"bas":["13","15"],"fav":["9","12","14","16"]}
 * bas - товары в корзине
 * fav - товары отложенные в избранное
 *
 */
use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Loader::includeModule('sale');

$arBasketItems = array();
$dbBasketItems = CSaleBasket::GetList(
    array(
        "NAME" => "ASC",
        "ID" => "ASC"
        ),
    array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "CAN_BUY"=>'Y',
        "ORDER_ID" => "NULL"
        ),
    false,
    false,
    array(
        "ID",  
        "PRODUCT_ID", 
        "DELAY", 
        "CAN_BUY"
    )
);


$arrBasketList = [];
$arrFavoriteList = [];

while ($arItems = $dbBasketItems->Fetch()){
    if($arItems['DELAY'] == 'Y' ){
        $arrFavoriteList[] = $arItems['PRODUCT_ID']; 
    }else{
        $arrBasketList[] = $arItems['PRODUCT_ID'];
    }
}


$arBasket = GetBasketList();
$arResult["TOTALPRICE"] = 0;
foreach($arBasket as $arBasketItem) {
    $arResult["TOTALPRICE"] += $arBasketItem["PRICE"] * $arBasketItem["QUANTITY"];
}


$arr['bas'] = $arrBasketList;
$arr['fav'] = $arrFavoriteList;
$arr['bassumm'] = $arResult["TOTALPRICE"];
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
echo json_encode($arr);