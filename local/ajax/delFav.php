<?php
/*
 *Удаление из корзины или избранного
 *
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule('sale');

if ($_REQUEST['id']) {
    $productId = $_REQUEST['id'];
}
elseif($_REQUEST['catid']){
    $dbBasketItems = CSaleBasket::GetList(
        array(
            "NAME" => "ASC",
            "ID" => "ASC"
        ),
        array(
            "FUSER_ID" => CSaleBasket::GetBasketUserID(),
            "LID" => SITE_ID,
            "PRODUCT_ID"=>$_REQUEST['catid'],
            "ORDER_ID" => "NULL"
        ),
        false,
        false,
        array(
            "ID",
            "PRODUCT_ID",
        )
    );
    if ($it = $dbBasketItems->Fetch())
        $productId = $it['ID'];
}

if(CSaleBasket::Delete($productId)) {
    die('1');
}
else die('0');
?>