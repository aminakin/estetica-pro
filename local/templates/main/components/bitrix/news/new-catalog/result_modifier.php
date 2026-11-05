<?php

$sortDefault = 'default';
$sorted = false;

if (isset($_GET['sort']) && $_GET['sort'] !== 'default') {
    $sort = explode('-', strtoupper($_GET['sort']));
    $arParams['SORT_BY1'] = $sort[0];
    $arParams['SORT_ORDER1'] = $sort[1];

    setcookie("CATALOG_SORT", $_GET['sort'], time() + 60 * 60 * 24 * 365, "/");
    $sortDefault = $_GET['sort'];
    $sorted = true;
} elseif (isset($_COOKIE['CATALOG_SORT']) && $_GET['sort'] !== 'default') {
    $sort = explode('-', strtoupper($_COOKIE['CATALOG_SORT']));

    $arParams['SORT_BY1'] = $sort[0];
    $arParams['SORT_ORDER1'] = $sort[1];

    $sortDefault = $_COOKIE['CATALOG_SORT'];
    $sorted = true;
} else {
    $sortDefault = 'default';
    setcookie("CATALOG_SORT", "", time() - 3600, "/");
}

$sortList = [
    ['name' => GetMessage('SORT_BY_DEFAULT'), 'code' => 'default', 'selected' => !$sorted || $sortDefault == 'default'],
    ['name' => GetMessage('SORT_BY_NAME_ASC'), 'code' => 'name-asc'],
    ['name' => GetMessage('SORT_BY_POPULAR'), 'code' => 'show_counter-desc'],
    ['name' => GetMessage('SORT_BY_PRICE_ASC'), 'code' => 'PROPERTY_MINIMUM_PRICE-asc'],
    ['name' => GetMessage('SORT_BY_PRICE_DESC'), 'code' => 'PROPERTY_MAXIMUM_PRICE-desc']
];

$arResult['SORT'] = [
    'SORT_LIST' => $sortList,
    'SORT_DEFAULT' => $sortDefault,
];

$arFilter = [
    'ACTIVE' => 'Y',
    'ACTIVE_DATE' => 'Y',
    'INCLUDE_SUBSECTIONS' => 'Y',
    'SECTION_ID' => $arResult["VARIABLES"]["SECTION_ID"],
    'IBLOCK_ID' => $arParams['IBLOCK_ID']
];

$arSelect = Array('PROPERTY_PRODUCT');

$dbResult = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
while ($obResult = $dbResult->GetNextElement()) {
    $arFields = $obResult->GetFields();
    $arResult['arrFilter']['ID'][] = $arFields['PROPERTY_PRODUCT_VALUE'];
}

unset($arFilter);
$arFilter = [];

if (isset($_GET['price_min']) && $_GET['price_min'] !== '') {
    $arFilter['>=CATALOG_PRICE_1'] = $_GET['price_min'];
}

if (isset($_GET['price_max']) && $_GET['price_max'] !== '' && $_GET['price_max'] !== 0) {
    $arFilter['<CATALOG_PRICE_1'] = $_GET['price_max'];
}


$arResult['arrFilter'] = array_merge($arResult['arrFilter'], $arFilter);

if ($arResult['VARIABLES']['SECTION_ID']) {
    $ipropValues = new \Bitrix\Iblock\InheritedProperty\SectionValues($arParams['IBLOCK_ID'], $arResult['VARIABLES']['SECTION_ID']);

    // Получаем массив всех вычисленных/наследуемых SEO-значений
    $seoValues = $ipropValues->getValues();
    $arResult['SEO_VALUES'] = $seoValues;
}