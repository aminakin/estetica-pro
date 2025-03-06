<?php

const NO_AGENT_CHECK = true;
const DisableEventsCheck = true;
const NO_KEEP_STATISTIC = true;
const NO_AGENT_STATISTIC = true;
const NOT_CHECK_PERMISSIONS = true;
const STOP_STATISTICS = true;

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $APPLICATION;
$APPLICATION->RestartBuffer();

ob_start();
$APPLICATION->IncludeComponent(
    "bitrix:sale.basket.basket",
    "basket.small.list.basket",
    [
        "COMPONENT_TEMPLATE" => "basket.small.list.basket",
        "COLUMNS_LIST" => [
            0 => "NAME",
            1 => "DELETE",
            2 => "PRICE",
            3 => "QUANTITY",
        ],
        "TEMPLATE_THEME" => "blue",
        "PATH_TO_ORDER" => "/personal/cart/",
        "HIDE_COUPON" => "Y",
        "PRICE_VAT_SHOW_VALUE" => "N",
        "USE_PREPAYMENT" => "N",
        "QUANTITY_FLOAT" => "N",
        "CORRECT_RATIO" => "N",
        "AUTO_CALCULATION" => "Y",
        "SET_TITLE" => "N",
        "ACTION_VARIABLE" => "basketAction",
        "USE_GIFTS" => "N",
        "USE_ENHANCED_ECOMMERCE" => "N",
        "COLUMNS_LIST_EXT" => [
            0 => "PREVIEW_PICTURE",
            1 => "DISCOUNT",
            2 => "DELETE",
            3 => "DELAY",
            4 => "TYPE",
            5 => "SUM",
        ],
        "COMPATIBLE_MODE" => "Y",
        "OFFERS_PROPS" => [],
        "ADDITIONAL_PICT_PROP_3" => "-",
        "ADDITIONAL_PICT_PROP_9" => "-",
        "ADDITIONAL_PICT_PROP_10" => "-",
        "ADDITIONAL_PICT_PROP_11" => "-",
        "ADDITIONAL_PICT_PROP_12" => "-",
        "ADDITIONAL_PICT_PROP_13" => "-",
        "BASKET_IMAGES_SCALING" => "adaptive"
    ],
    false
);

$html = ob_get_contents();
ob_end_clean();

die(json_encode(['SUCCESS' => true, 'HTML' => $html]));