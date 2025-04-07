<?php
/*
 *Обновление количества товаров в корзине
 *
 *
*/
use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

Loader::includeModule('sale');


$ID = $_REQUEST['id'];

if ($_REQUEST['action'] == 'change') {

    $qty = $_REQUEST['quantity'];
    $arFields = array(
        "QUANTITY" => $qty,
        "DELAY" => "N"
    );
    $result = CSaleBasket::Update($ID, $arFields);


}elseif($_REQUEST['action'] == 'remove'){

    $result = CSaleBasket::Delete($ID);

}elseif($_REQUEST['action'] == 'remove_basket'){

	// $result = CSaleBasket::GetBasketUserID();
    $result = CSaleBasket::DeleteAll(CSaleBasket::GetBasketUserID());


}


die($result);
?>