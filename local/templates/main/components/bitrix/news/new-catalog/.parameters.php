<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader,
    Bitrix\Main\ModuleManager,
    Bitrix\Iblock,
    Bitrix\Catalog,
    Bitrix\Currency;

if (!Loader::includeModule('iblock'))
    return;

$arIBlockType = CIBlockParameters::GetIBlockTypes();
$catalogIncluded = Loader::includeModule('catalog');
$arIBlock = array();

$offersIblock = array();
if ($catalogIncluded)
{
    $iterator = Catalog\CatalogIblockTable::getList(array(
        'select' => array('IBLOCK_ID'),
        'filter' => array('!=PRODUCT_IBLOCK_ID' => 0)
    ));
    while ($row = $iterator->fetch())
        $offersIblock[$row['IBLOCK_ID']] = true;
    unset($row, $iterator);
}


$iblockFilter = (
!empty($arCurrentValues['IBLOCK_TYPE'])
    ? array('TYPE' => $arCurrentValues['IBLOCK_TYPE'], 'ACTIVE' => 'Y')
    : array('ACTIVE' => 'Y')
);
$rsIBlock = CIBlock::GetList(array('SORT' => 'ASC'), $iblockFilter);
while ($arr = $rsIBlock->Fetch()) {
    $id = (int)$arr['ID'];
    if (isset($offersIblock[$id]))
        continue;
    $arIBlock[$id] = '[' . $id . '] ' . $arr['NAME'];
}
unset($id, $arr, $rsIBlock, $iblockFilter);
unset($offersIblock);

$arTemplateParameters = array(
    "DISPLAY_DATE" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_DATE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
    "DISPLAY_PICTURE" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_PICTURE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
    "DISPLAY_PREVIEW_TEXT" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_TEXT"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y",
    ),
    "USE_SHARE" => Array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_USE_SHARE"),
        "TYPE" => "CHECKBOX",
        "MULTIPLE" => "N",
        "VALUE" => "Y",
        "DEFAULT" => "N",
        "REFRESH" => "Y",
    ),

    "CATALOG_IBLOCK_TYPE" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("IBLOCK_TYPE"),
        "TYPE" => "LIST",
        "VALUES" => $arIBlockType,
        "REFRESH" => "Y",
    ),
    "CATALOG_IBLOCK_ID" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("IBLOCK_IBLOCK"),
        "TYPE" => "LIST",
        "ADDITIONAL_VALUES" => "Y",
        "VALUES" => $arIBlock,
    ),
    "ELEMENT_SORT_FIELD" => array(
        "PARENT" => "LIST_SETTINGS",
        "NAME" => GetMessage("IBLOCK_ELEMENT_SORT_FIELD"),
        "TYPE" => "LIST",
        "VALUES" => $arSort,
        "ADDITIONAL_VALUES" => "Y",
        "DEFAULT" => "sort",
    ),
    "ELEMENT_SORT_ORDER" => array(
        "PARENT" => "LIST_SETTINGS",
        "NAME" => GetMessage("IBLOCK_ELEMENT_SORT_ORDER"),
        "TYPE" => "LIST",
        "VALUES" => $arAscDesc,
        "DEFAULT" => "asc",
        "ADDITIONAL_VALUES" => "Y",
    ),
    "ELEMENT_SORT_FIELD2" => array(
        "PARENT" => "LIST_SETTINGS",
        "NAME" => GetMessage("IBLOCK_ELEMENT_SORT_FIELD2"),
        "TYPE" => "LIST",
        "VALUES" => $arSort,
        "ADDITIONAL_VALUES" => "Y",
        "DEFAULT" => "id",
    ),
    "ELEMENT_SORT_ORDER2" => array(
        "PARENT" => "LIST_SETTINGS",
        "NAME" => GetMessage("IBLOCK_ELEMENT_SORT_ORDER2"),
        "TYPE" => "LIST",
        "VALUES" => $arAscDesc,
        "DEFAULT" => "desc",
        "ADDITIONAL_VALUES" => "Y",
    ),

);

if ($arCurrentValues["USE_SHARE"] == "Y") {
    $arTemplateParameters["SHARE_HIDE"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_HIDE"),
        "TYPE" => "CHECKBOX",
        "VALUE" => "Y",
        "DEFAULT" => "N",
    );

    $arTemplateParameters["SHARE_TEMPLATE"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_TEMPLATE"),
        "DEFAULT" => "",
        "TYPE" => "STRING",
        "MULTIPLE" => "N",
        "COLS" => 25,
        "REFRESH" => "Y",
    );

    if (strlen(trim($arCurrentValues["SHARE_TEMPLATE"])) <= 0)
        $shareComponentTemlate = false;
    else
        $shareComponentTemlate = trim($arCurrentValues["SHARE_TEMPLATE"]);

    include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/bitrix/main.share/util.php");

    $arHandlers = __bx_share_get_handlers($shareComponentTemlate);

    $arTemplateParameters["SHARE_HANDLERS"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SYSTEM"),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "VALUES" => $arHandlers["HANDLERS"],
        "DEFAULT" => $arHandlers["HANDLERS_DEFAULT"],
    );

    $arTemplateParameters["SHARE_SHORTEN_URL_LOGIN"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_LOGIN"),
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );

    $arTemplateParameters["SHARE_SHORTEN_URL_KEY"] = array(
        "NAME" => GetMessage("T_IBLOCK_DESC_NEWS_SHARE_SHORTEN_URL_KEY"),
        "TYPE" => "STRING",
        "DEFAULT" => "",
    );
}

?>