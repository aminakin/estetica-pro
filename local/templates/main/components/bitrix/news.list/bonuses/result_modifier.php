<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arParams['USER_PERCENT'] = $arParams['USER_PERCENT'] ?? 0;

foreach ($arResult['ITEMS'] as $key => $items) {
    if ($items['PROPERTIES']['PERCENT']['VALUE'] != $arParams['USER_PERCENT']) {
        unset($arResult['ITEMS'][$key]);
    }
}
?>