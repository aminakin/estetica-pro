<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION;

$aMenuLinksExt=$APPLICATION->IncludeComponent(
    "bitrix:menu.sections",
    "", 
    array(
        "IS_SEF" => "Y",
        "SEF_BASE_URL" => "/new-catalog/",
        "SECTION_PAGE_URL" => "#SECTION_CODE_PATH#/",
        "DETAIL_PAGE_URL" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#.html",
        "IBLOCK_TYPE" => "catalog",
        "IBLOCK_ID" => "26",
        "DEPTH_LEVEL" => "2",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "36000000",
    ),
    false
);

foreach ($aMenuLinksExt as $key => $value) {
    $aMenuLinksExt[$key]['3']['DEPTH_LEVEL'] = $value['3']['DEPTH_LEVEL'] + 1;
}

// $arCatalog = Array(
//     "Каталог", 
//     "/catalog/",
//     Array(
//         "/catalog/"
//     ), 
//     Array(
//         "FROM_IBLOCK" => 1,
//         "IS_PARENT" => 1,
//         "DEPTH_LEVEL" => 1,
//     )
// );
// array_unshift($aMenuLinksExt, $arCatalog);

array_unshift($aMenuLinksExt);

$aMenuLinks = array_merge($aMenuLinksExt, $aMenuLinks);
?>