<?php
/*
 *Обновление количества товаров в корзине
 *
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule('sale');


$productId = $_REQUEST['id'];
$qty = $_REQUEST['quantity'];

$arFields = array(
	"QUANTITY" => $qty,
	"DELAY" => "N"
);

if(CSaleBasket::Update($productId, $arFields)){
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
        if ($arBasketItem['ID']==$_POST['id'])
            $sum += round($arBasketItem['PRICE']) * round($arBasketItem['QUANTITY']);
    }

    $sum = format_price($sum). '&nbsp<span class="rub">Р</span>';

    die((string)$sum);
}
else{
	die('111');
}

?>