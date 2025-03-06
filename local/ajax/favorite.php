<?
/*
 * Добавление товаров в список избранных товаров
 *
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
global $APPLICATION;
global $USER;

Bitrix\Main\Loader::includeModule('catalog');

if ($USER->IsAuthorized())
{
    if (isset($_REQUEST['id']) && intval($_REQUEST['id']) > 0) {
        $productId = intval($_REQUEST['id']);
    } else {
        die('3');
    }
    $qty = 1;

    if(Add2BasketByProductID($productId, $qty,array('DELAY' => 'Y'), array())) {
        die('1');
    } else {
        die('0');
    }
}
else
{
    die('2');
}