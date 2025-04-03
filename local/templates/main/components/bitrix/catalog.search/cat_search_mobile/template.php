<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<?

$arElements = $APPLICATION->IncludeComponent(
	"bitrix:search.page",
	".default",
	Array(
		"RESTART" => $arParams["RESTART"],
		"NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"],
		"USE_LANGUAGE_GUESS" => $arParams["USE_LANGUAGE_GUESS"],
		"CHECK_DATES" => $arParams["CHECK_DATES"],
		"arrFILTER" => array("iblock_".$arParams["IBLOCK_TYPE"]),
		"arrFILTER_iblock_".$arParams["IBLOCK_TYPE"] => array($arParams["IBLOCK_ID"]),
		"USE_TITLE_RANK" => "N",
		"DEFAULT_SORT" => "rank",
		"FILTER_NAME" => "",
		"SHOW_WHERE" => "N",
		"arrWHERE" => array(),
		"SHOW_WHEN" => "N",
		"PAGE_RESULT_COUNT" => 999,
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"PAGER_TITLE" => "",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => "N",
	),
	$component,
	array('HIDE_ICONS' => 'Y')
);
if (!empty($arElements) && is_array($arElements))
{
	global $searchFilter;
	$searchFilter = array(
		"=ID" => $arElements,
	); 
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.section",
		"sale",
		array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			"ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
			"ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
			"ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
			"ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
			"PAGE_ELEMENT_COUNT" => $arParams["PAGE_ELEMENT_COUNT"],
			"LINE_ELEMENT_COUNT" => $arParams["LINE_ELEMENT_COUNT"],
			"PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
			"OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
			"OFFERS_LIMIT" => $arParams["OFFERS_LIMIT"],
			"LIST_OFFERS_PROPERTY_CODE" => array(
				0 => "TOLSHCHINA",
				1 => "TSVET_UPAKOVKI",
				2 => "DLINA",
				3 => "TSVET",
				4 => "IZGIB",
				5 => "TOLSHCHINA_1",
				6 => "DLINA_1",
				7 => "IRGIB",
				8 => "TOLSHINA",
				9 => "65",
				10 => "",
			),
			"MESS_BTN_ADD_TO_BASKET" => "Добавить в корзину",
			"PRODUCT_BLOCKS_ORDER" => "price,quantity,quantityLimit,buttons,compare,props,sku",
			
			"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
			"LIST_PROPERTY_CODE" => array(
				0 => "",
				1 => "ICON",
				2 => "",
			),
			"OFFERS_CART_PROPERTIES" => array(
			),
			"OFFERS_SORT_FIELD" => "shows",
			"OFFERS_SORT_FIELD2" => "shows",
			"OFFERS_SORT_ORDER" => "asc",
			"OFFERS_SORT_ORDER2" => "asc",
			"OFFER_ADD_PICT_PROP" => "-",
			"PRODUCT_DISPLAY_MODE" => "Y",
			"OFFER_TREE_PROPS" => array(
				0 => "TOLSHCHINA",
				1 => "TSVET_UPAKOVKI",
				2 => "DLINA",
				3 => "TSVET",
				4 => "IZGIB",
                5 => "TOLSHCHINA_1",
                6 => "DLINA_1",
                7 => "RAZMER",
                8 => "AROMAT",
                9 => "",
			),
			"SECTION_URL" => $arParams["SECTION_URL"],
			"DETAIL_URL" => $arParams["DETAIL_URL"],
			"BASKET_URL" => $arParams["BASKET_URL"],
			"ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
			"PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
			"PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
			"PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
			"SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"DISPLAY_COMPARE" => $arParams["DISPLAY_COMPARE"],
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
			"SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
			"USE_PRODUCT_QUANTITY" => $arParams["USE_PRODUCT_QUANTITY"],
			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"HIDE_NOT_AVAILABLE" => 'N',
			"MESS_NOT_AVAILABLE" => 'Товар отсутствует на складе',
			"HIDE_NOT_AVAILABLE_OFFERS" => 'N',
			"DISPLAY_TOP_PAGER" => $arParams["DISPLAY_TOP_PAGER"],
			"DISPLAY_BOTTOM_PAGER" => $arParams["DISPLAY_BOTTOM_PAGER"],
			"PAGER_TITLE" => $arParams["PAGER_TITLE"],
			"PAGER_SHOW_ALWAYS" => $arParams["PAGER_SHOW_ALWAYS"],
			"PAGER_TEMPLATE" => $arParams["PAGER_TEMPLATE"],
			"PAGER_DESC_NUMBERING" => $arParams["PAGER_DESC_NUMBERING"],
			"PAGER_DESC_NUMBERING_CACHE_TIME" => $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"],
			"PAGER_SHOW_ALL" => $arParams["PAGER_SHOW_ALL"],
			"FILTER_NAME" => "searchFilter",
			"SECTION_ID" => "",
			"SECTION_CODE" => "",
			"SECTION_USER_FIELDS" => array(),
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
			"META_KEYWORDS" => "",
			"META_DESCRIPTION" => "",
			"BROWSER_TITLE" => "",
			"ADD_SECTIONS_CHAIN" => "N",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"CACHE_FILTER" => "N", 
			"CACHE_GROUPS" => "N",
            "LAZY_LOAD" => "Y",
            "MESS_BTN_LAZY_LOAD" => "Показать ещё"
		),
		$arResult["THEME_COMPONENT"],
		array('HIDE_ICONS' => 'Y')
	);
}
elseif (is_array($arElements))
{?><div class="search-not-found-wrap">
    <div class="search-not-found decktop">
    	<div class="what_you_found">«<?=urldecode($_GET['q']);?>»</div>
		<div class="not_found"><?=GetMessage("CT_BCSE_NOT_FOUND");?></div>
		<div class="you_not_found"><?=GetMessage("CT_YOU_NOT_FOUND");?></div>
		<div class="call_as"><?=GetMessage("CT_CALL_ASS");?></div>
	</div>
	<div class="search-not-found mobile">
		<div class="what_you_found">«<?=urldecode($_GET['q']);?>»</div>
		<div class="not_found"><?=GetMessage("CT_BCSE_NOT_FOUND");?></div>
		<div class="you_not_found"><?=GetMessage("CT_YOU_NOT_FOUND");?></div>
		<div class="call_as"><?=GetMessage("CT_CALL_ASS_MOBILE");?></div>
		<a class="phone" href='tel:+78332776887'>+7 8332 77 68 87</a>
		<a class="whatsapp" href='https://api.whatsapp.com/send?phone=79229776867'>WhatsApp</a>
	</div>
	<h2 class="catalog-block-header"><?=GetMessage("CT_MAYBY");?></h2>
	<?$APPLICATION->IncludeComponent(
	"bitrix:catalog.top", 
	"main", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"BASKET_URL" => "/personal/basket.php",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"COMPATIBLE_MODE" => "Y",
		"COMPONENT_TEMPLATE" => "main",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[{\"CLASS_ID\":\"CondIBProp:14:133\",\"DATA\":{\"logic\":\"Equal\",\"value\":361}}]}",
		"DETAIL_URL" => "",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "8",
		"ELEMENT_SORT_FIELD" => "shows",
		"ELEMENT_SORT_FIELD2" => "shows",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "asc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "",
		"HIDE_NOT_AVAILABLE" => "Y",
		"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
		"IBLOCK_ID" => IblockHelper::getIdByCode('catalog'),
		"IBLOCK_TYPE" => "catalog",
		"LABEL_PROP" => array(
			0 => "SKIDKA",
		),
		"LABEL_PROP_MOBILE" => array(
			0 => "SKIDKA",
		),
		"LABEL_PROP_POSITION" => "top-left",
		"LINE_ELEMENT_COUNT" => "4",
		"MESS_BTN_ADD_TO_BASKET" => "Добавить в корзину",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_COMPARE" => "Сравнить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"MESS_SHOW_MAX_QUANTITY" => "Наличие",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_LIMIT" => "5",
		"OFFERS_PROPERTY_CODE" => array(
			0 => "TOLSHCHINA",
			1 => "TSVET_UPAKOVKI",
			2 => "DLINA",
			3 => "TSVET",
			4 => "IZGIB",
			5 => "",
		),
		"OFFERS_SORT_FIELD" => "shows",
		"OFFERS_SORT_FIELD2" => "shows",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_ORDER2" => "asc",
		"OFFER_ADD_PICT_PROP" => "-",
		"OFFER_TREE_PROPS" => array(
			0 => "TOLSHCHINA",
			1 => "TSVET_UPAKOVKI",
			2 => "DLINA",
			3 => "TSVET",
			4 => "IZGIB",
		),
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
			0 => "Типовое соглашение с клиентами",
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,quantity,buttons,props,sku,quantityLimit",
		"PRODUCT_DISPLAY_MODE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
		),
		"ROTATE_TIMER" => "30",
		"SECTION_URL" => "",
		"SEF_MODE" => "N",
		"SHOW_CLOSE_POPUP" => "N",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_MAX_QUANTITY" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"SHOW_PAGINATION" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "Y",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "site",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "Y",
		"VIEW_MODE" => "SECTION"
	),
	false
);?>
</div>
<?}