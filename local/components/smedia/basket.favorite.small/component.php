<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();
/**
 * компонент получает актуальную корзину избранных товаров
*/

use Bitrix\Main\Loader;
Loader::includeModule('sale');

$arResult['FAVORITE_QUANTITY'] = CSaleBasket::GetList(
    array(),
    array( 
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL",
        "DELAY" => "Y"
    ), 
    array()
);

$this->IncludeComponentTemplate();
?>