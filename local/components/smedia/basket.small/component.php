<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * компонент получает актуальную корзину товаров
*/

 
use Bitrix\Main\Loader;
Loader::includeModule('sale');

$arResult['FAVORITE_QUANTITY'] = CSaleBasket::GetList(
    array(),
    array( 
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL",
        "DELAY" => "N"
    ), 
    array()
);

$arBasket = GetBasketList();
$arResult["TOTALPRICE"] = 0;
foreach($arBasket as $arBasketItem) {
	$arResult["TOTALPRICE"] += $arBasketItem["PRICE"] * $arBasketItem["QUANTITY"];
}

$this->IncludeComponentTemplate();
?>