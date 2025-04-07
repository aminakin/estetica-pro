<?php
/*
 * Обновление корзины в шапке
 *
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

global $APPLICATION;
Loader::includeModule('catalog');
Loader::includeModule('sale');

global $hrefDoc;
$hrefDoc = $_POST['urlPage'];

$APPLICATION->IncludeComponent(
    "bitrix:sale.basket.basket", 
    "basket.small.list.basket", 
    Array(
        "COMPONENT_TEMPLATE" => ".default",
        "COLUMNS_LIST" => array(    
            0 => "NAME",
            1 => "DELETE",
            2 => "PRICE",
            3 => "QUANTITY",
        ),
        "TEMPLATE_THEME" => "blue", 
        "PATH_TO_ORDER" => "/cart/order/",   
        "HIDE_COUPON" => "Y",   
        "PRICE_VAT_SHOW_VALUE" => "N",  
        "USE_PREPAYMENT" => "N",    
        "QUANTITY_FLOAT" => "N",    
        "CORRECT_RATIO" => "N", 
        "AUTO_CALCULATION" => "Y",  
        "SET_TITLE" => "Y", 
        "ACTION_VARIABLE" => "basketAction",   
        "USE_GIFTS" => "N", 
        "USE_ENHANCED_ECOMMERCE" => "N",    
    ),
    false
);
