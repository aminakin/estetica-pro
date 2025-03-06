<?php
/**
 * Получение количества товаров в корзине
 * 
 */


define("NO_KEEP_STATISTIC", true);
define("NO_AGENT_CHECK", true);
define('PUBLIC_AJAX_MODE', true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$_SESSION["SESS_SHOW_INCLUDE_TIME_EXEC"]="N";
$APPLICATION->ShowIncludeStat = false;
use Bitrix\Sale;
Bitrix\Main\Loader::includeModule("sale");
Bitrix\Main\Loader::includeModule("catalog");

$fuser = Sale\Fuser::getId();
$basketRes = Sale\Internals\BasketTable::getList(array(
    'filter' => array(
        'FUSER_ID' => $fuser, 
        'ORDER_ID' => null,
        'LID' => SITE_ID,
        'CAN_BUY' => 'Y',
        "DELAY" => "N"
    )
));
$cnt = 0;
while ($item = $basketRes->fetch()) {
	$cnt++;
}
echo $cnt;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");

