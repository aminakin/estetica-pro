<?php
/*
 * Выведем актуальную сумму корзины для текущего пользователя
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
Loader::includeModule('catalog');
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
        "ORDER_ID" => "NULL",
        "DELAY" => "N"
    ),
    false,
    false,
    array(
    	"ID", 
    	"CALLBACK_FUNC", 
    	"MODULE",
    	"PRODUCT_ID", 
    	"QUANTITY", 
    	"DELAY", 
        "CAN_BUY", 
        "PRICE", 
        "WEIGHT"
    )
);

while ($arItems = $dbBasketItems->Fetch())
{
    if (strlen($arItems["CALLBACK_FUNC"]) > 0)
    {
        CSaleBasket::UpdatePrice($arItems["ID"], 
                                $arItems["CALLBACK_FUNC"], 
                                $arItems["MODULE"], 
                                $arItems["PRODUCT_ID"], 
                                $arItems["QUANTITY"]);

        $arItems = CSaleBasket::GetByID($arItems["ID"]);
    }

    $arBasketItems[] = $arItems;
}

foreach ($arBasketItems as $arBasketItem){
	$sum += round($arBasketItem['PRICE']) * round($arBasketItem['QUANTITY']);
}

$sum = format_price($sum). '&nbsp;₽';

die((string)$sum);
?>