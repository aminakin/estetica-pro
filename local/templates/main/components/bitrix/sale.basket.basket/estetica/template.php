<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
/** @var CBitrixBasketComponent $component */
$templateData = array(
	'TEMPLATE_THEME' => $this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css',
	'TEMPLATE_CLASS' => 'bx_'.$arParams['TEMPLATE_THEME'],
);
$this->addExternalCss($templateData['TEMPLATE_THEME']);

$curPage = $APPLICATION->GetCurPage().'?'.$arParams["ACTION_VARIABLE"].'=';
$arUrls = array(
	"delete" => $curPage."delete&id=#ID#",
	"delay" => $curPage."delay&id=#ID#",
	"add" => $curPage."add&id=#ID#",
);
unset($curPage);

$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'ADD_URL' => $arUrls["add"],
	'EVENT_ONCHANGE_ON_START' => (!empty($arResult['EVENT_ONCHANGE_ON_START']) && $arResult['EVENT_ONCHANGE_ON_START'] === 'Y') ? 'Y' : 'N',
	'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
	'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
	'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
);
?>
<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>;
</script>
<?
$APPLICATION->AddHeadScript($templateFolder."/script.js");

if($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP')
{
	$APPLICATION->IncludeComponent(
		"bitrix:sale.gift.basket",
		".default",
		array(
			"SHOW_PRICE_COUNT" => 1,
			"PRODUCT_SUBSCRIPTION" => 'N',
			'PRODUCT_ID_VARIABLE' => 'id',
			"PARTIAL_PRODUCT_PROPERTIES" => 'N',
			"USE_PRODUCT_QUANTITY" => 'N',
			"ACTION_VARIABLE" => "actionGift",
			"ADD_PROPERTIES_TO_BASKET" => "Y",

			"BASKET_URL" => $APPLICATION->GetCurPage(),
			"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
			"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

			'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
			'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
			'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
			'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
			'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
			'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
			'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
			'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
			'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
			'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
			'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
			'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

			"LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
		),
		false
	);
}

if (strlen($arResult["ERROR_MESSAGE"]) <= 0)
{
	?>
	<div id="warning_message">
		<?
		if (!empty($arResult["WARNING_MESSAGE"]) && is_array($arResult["WARNING_MESSAGE"]))
		{
			foreach ($arResult["WARNING_MESSAGE"] as $v)
				ShowError($v);
		}
		?>
	</div>
	<?

	$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
	$normalHidden = ($normalCount == 0) ? 'style="display:none;"' : '';

	$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
	$delayHidden = ($delayCount == 0) ? 'style="display:none;"' : '';

	$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
	$subscribeHidden = ($subscribeCount == 0) ? 'style="display:none;"' : '';

	$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
	$naHidden = ($naCount == 0) ? 'style="display:none;"' : '';

	foreach (array_keys($arResult['GRID']['HEADERS']) as $id)
	{
		$data = $arResult['GRID']['HEADERS'][$id];
		$headerName = (isset($data['name']) ? (string)$data['name'] : '');
		if ($headerName == '')
			$arResult['GRID']['HEADERS'][$id]['name'] = GetMessage('SALE_'.$data['id']);
		unset($headerName, $data);
	}
	unset($id);

	?>
        <p class="basket-attention"><?=GetMessage('ATTENTION');?></p>
        <section class="basket-block">
            <form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form" class="basket">
                <div id="basket_form_container">
                    <div class="bx_ordercart <?=$templateData['TEMPLATE_CLASS']; ?>">
                        <?/*<div class="bx_sort_container">
                            <span><?=GetMessage("SALE_ITEMS")?></span>
                            <a href="javascript:void(0)" id="basket_toolbar_button" class="current" onclick="showBasketItemsList()"><?=GetMessage("SALE_BASKET_ITEMS")?><div id="normal_count" class="flat" style="display:none">&nbsp;(<?=$normalCount?>)</div></a>
                            <a href="javascript:void(0)" id="basket_toolbar_button_delayed" onclick="showBasketItemsList(2)" <?=$delayHidden?>><?=GetMessage("SALE_BASKET_ITEMS_DELAYED")?><div id="delay_count" class="flat">&nbsp;(<?=$delayCount?>)</div></a>
                            <a href="javascript:void(0)" id="basket_toolbar_button_subscribed" onclick="showBasketItemsList(3)" <?=$subscribeHidden?>><?=GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED")?><div id="subscribe_count" class="flat">&nbsp;(<?=$subscribeCount?>)</div></a>
                            <a href="javascript:void(0)" id="basket_toolbar_button_not_available" onclick="showBasketItemsList(4)" <?=$naHidden?>><?=GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE")?><div id="not_available_count" class="flat">&nbsp;(<?=$naCount?>)</div></a>
                        </div>*/?>
                        <?
                        include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
                        //include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delayed.php");
                        //include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");
                       // include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");
                        ?>
                    </div>
                </div>
                <input type="hidden" name="BasketOrder" value="BasketOrder" />
                <!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
            </form>
        </section>
	<?

	if($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
	{
		?>
		<div style="margin-top: 35px;"><? $APPLICATION->IncludeComponent(
			"bitrix:sale.gift.basket",
			".default",
			array(
				"SHOW_PRICE_COUNT" => 1,
				"PRODUCT_SUBSCRIPTION" => 'N',
				'PRODUCT_ID_VARIABLE' => 'id',
				"PARTIAL_PRODUCT_PROPERTIES" => 'N',
				"USE_PRODUCT_QUANTITY" => 'N',
				"ACTION_VARIABLE" => "actionGift",
				"ADD_PROPERTIES_TO_BASKET" => "Y",

				"BASKET_URL" => $APPLICATION->GetCurPage(),
				"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
				"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

				"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
				"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
				"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

				'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
				'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
				'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
				'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
				'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
				'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
				'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
				'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
				'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
				'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
				'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
				'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
				'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
				'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

				"LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
			),
			false
		); ?>
		</div><?
	}
    GLOBAL $arrFilterCatalog;
    $arrFilterCatalog['ID'] = $arResult['ADDITIONAL'];
  ?>
  <!--h2>С этими товарами покупают</h2-->
  <div class="clear"></div>
    <?/*
    $APPLICATION->IncludeComponent(
	"bitrix:catalog.section", 
	"addamant", 
	array(
		"ACTION_VARIABLE" => "action",
		"ADD_PICT_PROP" => "-",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"ADD_TO_BASKET_ACTION" => "ADD",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"BACKGROUND_IMAGE" => "-",
		"BASKET_URL" => "/personal/basket.php",
		"BROWSER_TITLE" => "-",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "N",
		"COMPATIBLE_MODE" => "Y",
		"CONVERT_CURRENCY" => "N",
		"CUSTOM_FILTER" => "",
		"DETAIL_URL" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_COMPARE" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"ELEMENT_SORT_FIELD" => "RAND",
		"ELEMENT_SORT_FIELD2" => "name",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "asc",
		"ENLARGE_PRODUCT" => "STRICT",
		"FILTER_NAME" => "arrFilterCatalog",
		"HIDE_NOT_AVAILABLE" => "N",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"IBLOCK_ID" => IblockHelper::getIdByCode('catalog'),
		"IBLOCK_TYPE" => "catalog",
		"INCLUDE_SUBSECTIONS" => "Y",
		"LABEL_PROP" => array(
		),
		"LABEL_PROP_MOBILE" => "",
		"LABEL_PROP_POSITION" => "top-left",
		"LAZY_LOAD" => "N",
		"LINE_ELEMENT_COUNT" => "3",
		"LOAD_ON_SCROLL" => "N",
		"MESSAGE_404" => "",
		"MESS_BTN_ADD_TO_BASKET" => "Купить",
		"MESS_BTN_BUY" => "Купить",
		"MESS_BTN_DETAIL" => "Подробнее",
		"MESS_BTN_SUBSCRIBE" => "Подписаться",
		"MESS_NOT_AVAILABLE" => "Нет в наличии",
		"META_DESCRIPTION" => "-",
		"META_KEYWORDS" => "-",
		"OFFERS_LIMIT" => "6",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Товары",
		"PAGE_ELEMENT_COUNT" => "3",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false}]",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"PROPERTY_CODE_MOBILE" => array(
		),
		"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
		"RCM_TYPE" => "personal",
		"SECTION_CODE" => "",
		"SECTION_ID" => $_REQUEST["SECTION_ID"],
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SEF_MODE" => "N",
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "Y",
		"SET_META_KEYWORDS" => "Y",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "Y",
		"SHOW_404" => "N",
		"SHOW_ALL_WO_SECTION" => "Y",
		"SHOW_CLOSE_POPUP" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "N",
		"SHOW_FROM_SECTION" => "N",
		"SHOW_MAX_QUANTITY" => "N",
		"SHOW_OLD_PRICE" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"SHOW_SLIDER" => "N",
		"SLIDER_INTERVAL" => "3000",
		"SLIDER_PROGRESS" => "N",
		"TEMPLATE_THEME" => "blue",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "Y",
		"COMPONENT_TEMPLATE" => "addamant",
		"COMPARE_PATH" => "",
		"MESS_BTN_COMPARE" => "Сравнить",
		"COMPARE_NAME" => "CATALOG_COMPARE_LIST",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "asc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"OFFERS_PROPERTY_CODE" => array(
			0 => "TOLSHCHINA",
			1 => "DLINA",
			2 => "IZGIB",
			3 => "",
		),
		"PRODUCT_DISPLAY_MODE" => "N",
		"OFFERS_CART_PROPERTIES" => array(
		)
	),
	false
);*/
}
else
{?>
    <p>
        <font class="errortext">
            <?print_r($arResult["ERROR_MESSAGE"]);?>
        </font>
    </p>
<?}
?>
