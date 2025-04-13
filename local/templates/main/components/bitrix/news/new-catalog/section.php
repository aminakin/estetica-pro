<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
global $arrFilter;

global $sortCatalog;
global $orderCatalog;
global $sortCatalog2;
global $orderCatalog2;
global $notAvailable;

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/scrollpage.js');
$arrFilter = $arResult['arrFilter']; ?>

        <div class="row">
			<div class="filter catalog-filter">
            	<div class="catalog-filter-sort other sort col-xs-12<?= (isset($arParams['FILTER_HIDE_ON_MOBILE']) && $arParams['FILTER_HIDE_ON_MOBILE'] === 'Y' ? ' hidden-xs' : '') ?>">
            	    <?
            	    $APPLICATION->IncludeComponent(
            	        "bitrix:catalog.smart.filter",
            	        'new',
            	        array(
            	            "IBLOCK_TYPE" => 'catalog',
            	            "IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
            	            "SECTION_ID" => $arCurSection['ID'],
            	            "FILTER_NAME" => "arrFilter",
            	            "PRICE_CODE" => array(
								0 => "Типовое соглашение с клиентами",
							),
							"CACHE_GROUPS" => "Y",
							"CACHE_TIME" => "36000000",
							"CACHE_TYPE" => "A",
            	            "SAVE_IN_SESSION" => "N",
            	            "FILTER_VIEW_MODE" => $arParams["FILTER_VIEW_MODE"],
            	            "XML_EXPORT" => "Y",
            	            "SECTION_TITLE" => "NAME",
            	            "SECTION_DESCRIPTION" => "DESCRIPTION",
            	            'HIDE_NOT_AVAILABLE' => 'N',
            	            "TEMPLATE_THEME" => 'site',
            	            'CONVERT_CURRENCY' => 'N',
            	            'CURRENCY_ID' => $arParams['CURRENCY_ID'],
            	            "SEF_MODE" => 'Y',
            	            "SEF_RULE" => '/catalog/' . '#SECTION_CODE#/filter/#SMART_FILTER_PATH#/apply/',
            	            "SMART_FILTER_PATH" => $arResult["VARIABLES"]["SMART_FILTER_PATH"],
            	            "PAGER_PARAMS_NAME" => $arParams["PAGER_PARAMS_NAME"],
            	            "INSTANT_RELOAD" => 'N',
            	        ),
            	        $component,
            	        array('HIDE_ICONS' => 'Y')
            	    );
            	    ?>
            	</div>
			</div>
            <div class="sections">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section.list",
                    "catalog-top",
                    Array(
                        "ADD_SECTIONS_CHAIN" => "Y",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "COUNT_ELEMENTS" => "N",
                        "HIDE_SECTION_NAME" => "N",
                        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                        "SECTION_FIELDS" => array("", ""),
                        "SECTION_ID" => $_REQUEST["SECTION_ID"],
                        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
                        "SECTION_USER_FIELDS" => array("", ""),
                        "SHOW_PARENT_NAME" => "N",
                        "TOP_DEPTH" => "4",
                        "VIEW_MODE" => "TILE"
                    )
                ); ?>
            </div>
            <?$arParams["SORT_BY1"] = 'NAME';
			$arParams["SORT_ORDER1"] = 'ASC';?>
            <? $APPLICATION->IncludeComponent(
				"bitrix:catalog.section", 
				"addamant", 
				array(
					"ACTION_VARIABLE" => "action",
					"ADD_PROPERTIES_TO_BASKET" => "Y",
					"ADD_SECTIONS_CHAIN" => "Y",
					"ADD_TO_BASKET_ACTION" => "ADD",
					"AJAX_MODE" => "N",
					"AJAX_OPTION_ADDITIONAL" => "",
					"AJAX_OPTION_HISTORY" => "N",
					"AJAX_OPTION_JUMP" => "N",
					"AJAX_OPTION_STYLE" => "Y",
					"BACKGROUND_IMAGE" => "-",
					"BASKET_URL" => "/cart/",
					"BROWSER_TITLE" => "-",
					"CACHE_FILTER" => "N",
					"CACHE_GROUPS" => "Y",
					"CACHE_TIME" => "36000000",
					"CACHE_TYPE" => "A",
					"COMPATIBLE_MODE" => "Y",
					"CONVERT_CURRENCY" => "N",
					"DETAIL_URL" => "",
					"DISABLE_INIT_JS_IN_COMPONENT" => "N",
					"DISPLAY_BOTTOM_PAGER" => "Y",
					"DISPLAY_COMPARE" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"ELEMENT_SORT_FIELD" => $sortCatalog,
                        "ELEMENT_SORT_ORDER" => $orderCatalog,
                        "ELEMENT_SORT_FIELD2" => $sortCatalog2,
                        "ELEMENT_SORT_ORDER2" => $orderCatalog2,
					"ENLARGE_PRODUCT" => "STRICT",
					"FILTER_NAME" => "arrFilter",
					"HIDE_NOT_AVAILABLE" => "N",
					"HIDE_NOT_AVAILABLE_OFFERS" => "L",
					"IBLOCK_ID" => $arParams["CATALOG_IBLOCK_ID"],
					"IBLOCK_TYPE" => "data",
					"INCLUDE_SUBSECTIONS" => "A",
					"LAZY_LOAD" => "N",
					"LINE_ELEMENT_COUNT" => "31",
					"LOAD_ON_SCROLL" => "N",
					"MESSAGE_404" => "",
					"MESS_BTN_ADD_TO_BASKET" => "Добавить в корзину",
					"MESS_BTN_BUY" => "Купить",
					"MESS_BTN_DETAIL" => "Подробнее",
					"MESS_BTN_SUBSCRIBE" => "Подписаться",
					"MESS_NOT_AVAILABLE" => "Товар отсутствует на складе",
					"META_DESCRIPTION" => "-",
					"META_KEYWORDS" => "-",
					"OFFERS_LIMIT" => "0",
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "catalog_section_new",
					"PAGER_TITLE" => "Товары",
					"PAGE_ELEMENT_COUNT" => "24",
					"PARTIAL_PRODUCT_PROPERTIES" => "Y",
					"PRICE_CODE" => array(
						0 => "Типовое соглашение с клиентами",
					),
					"PRICE_VAT_INCLUDE" => "Y",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_PROPERTIES" => array(
					),
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PRODUCT_SUBSCRIPTION" => "Y",
					"LIST_OFFERS_PROPERTY_CODE" => array(
						0 => "IZGIB",
						1 => "TOLSHCHINA",
						2 => "TSVET_UPAKOVKI",
						3 => "DLINA",
						4 => "TSVET",
						5 => "TOLSHCHINA_1",
						6 => "DLINA_1",
						7 => "RAZMER",
						8 => "AROMAT",
						9 => "IRGIB",
						10 => "TOLSHINA",
						11 => "65",
						12 => "",
					),
					"RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
					"RCM_TYPE" => "personal",
					"SEF_FOLDER" => "/catalog/",
					"SECTION_ID" => $_REQUEST["SECTION_ID"],
					"SECTION_ID_VARIABLE" => "SECTION_ID",
					"SECTION_URL" => "",
					"SECTION_USER_FIELDS" => array(
						0 => "",
						1 => "",
					),
					"SEF_MODE" => "N",
					"SET_BROWSER_TITLE" => "N",
					"SET_LAST_MODIFIED" => "N",
					"SET_META_DESCRIPTION" => "Y",
					"SET_META_KEYWORDS" => "N",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "Y",
					"SHOW_404" => "N",
					"SHOW_ALL_WO_SECTION" => "Y",
					"SHOW_CLOSE_POPUP" => "Y",
					"SHOW_DISCOUNT_PERCENT" => "Y",
					"SHOW_FROM_SECTION" => "N",
					"SHOW_MAX_QUANTITY" => "N",
					"SHOW_OLD_PRICE" => "Y",
					"SHOW_PRICE_COUNT" => "1",
					"SHOW_SLIDER" => "N",
					"TEMPLATE_THEME" => "blue",
					"USE_ENHANCED_ECOMMERCE" => "N",
					"USE_MAIN_ELEMENT_SECTION" => "N",
					"USE_PRICE_COUNT" => "N",
					"USE_PRODUCT_QUANTITY" => "Y",
					"COMPONENT_TEMPLATE" => "addamant",
					"SECTION_CODE" => "",
					"CUSTOM_FILTER" => "",
					"OFFERS_SORT_FIELD" => "sort",
					"OFFERS_SORT_ORDER" => "asc",
					"OFFERS_SORT_FIELD2" => "id",
					"OFFERS_SORT_ORDER2" => "desc",
					"LIST_PROPERTY_CODE" => array(
						0 => "",
						1 => "ICON",
						2 => "",
					),
					"PROPERTY_CODE_MOBILE" => "",
					"LIST_OFFERS_FIELD_CODE" => array(
						0 => "",
						1 => "",
					),
					"OFFERS_PROPERTY_CODE" => array(
						0 => "IZGIB",
						1 => "TOLSHCHINA",
						2 => "TSVET_UPAKOVKI",
						3 => "DLINA",
						4 => "TSVET",
						5 => "TOLSHCHINA_1",
						6 => "DLINA_1",
						7 => "RAZMER",
						8 => "AROMAT",
						9 => "IRGIB",
						10 => "TOLSHINA",
						11 => "65",
						12 => "",
					),
					"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false},{'VARIANT':'3','BIG_DATA':false}]",
					"LIST_PRODUCT_BLOCKS_ORDER" => "price,quantity,quantityLimit,sku,buttons,compare,props",
					"SLIDER_INTERVAL" => "3000",
					"SLIDER_PROGRESS" => "N",
					"PRODUCT_DISPLAY_MODE" => "Y",
					"ADD_PICT_PROP" => "MORE_PHOTO",
					"LABEL_PROP" => "",
					"LABEL_PROP_MOBILE" => "",
					"LABEL_PROP_POSITION" => "top-left",
					"OFFER_ADD_PICT_PROP" => "-",
					"OFFER_TREE_PROPS" => array(
						0 => "IZGIB",
						1 => "TOLSHCHINA",
						2 => "DLINA",
						3 => "TSVET",
						4 => "AKTSIONNYY",
						5 => "RAZMER",
						6 => "AROMAT",
					),
					"OFFERS_CART_PROPERTIES" => array(
						0 => "IZGIB",
						1 => "TOLSHCHINA",
						2 => "TSVET_UPAKOVKI",
						3 => "DLINA",
						4 => "TSVET",
						5 => "TOLSHCHINA_1",
						6 => "RAZMER",
						7 => "AROMAT",
					),
					"PROPERTY_CODE" => array(
						0 => "",
						1 => "",
					),
					"PRODUCT_BLOCKS_ORDER" => "price,quantity,props,sku,buttons,quantityLimit,compare",
					"DISCOUNT_PERCENT_POSITION" => "bottom-right",
					"MESS_BTN_LAZY_LOAD" => "Показать ещё"
				),
				false
			); ?>
        </div>

<script type="text/javascript">
	$(document).on('scroll', function(){
        sessionStorage['scrollTop'] = $(document).scrollTop();
    });

$(document).ready( function(){
    if (sessionStorage['goToBack'] != undefined && sessionStorage['goToBack'] == "true") {
        if (navigator.userAgent.match(/(iPod|iPhone|iPad|Android)/)) {     
            setTimeout(function() { 
                $('html,body').animate({
                    scrollTop: sessionStorage['scrollLastTop'],
                }, 800, function(){
                    $('html,body').clearQueue();
                });
            }, 500);
        }else{
            $(document).scrollTop(sessionStorage['scrollLastTop']);
        }
        sessionStorage['goToBack'] = false;
    }
});
</script>
