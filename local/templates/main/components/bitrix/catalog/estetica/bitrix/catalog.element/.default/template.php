<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CatalogSectionComponent $component
 * @var CBitrixComponentTemplate $this
 * @var string $templateName
 * @var string $componentPath
 * @var string $templateFolder
 */

$this->setFrameMode(true);
$this->addExternalCss('/bitrix/css/main/bootstrap.css');

$templateLibrary = array('popup', 'fx');
$currencyList = '';

if (!empty($arResult['CURRENCIES']))
{
	$templateLibrary[] = 'currency';
	$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$templateData = array(
	'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
	'TEMPLATE_LIBRARY' => $templateLibrary,
	'CURRENCIES' => $currencyList,
	'ITEM' => array(
		'ID' => $arResult['ID'],
		'IBLOCK_ID' => $arResult['IBLOCK_ID'],
		'OFFERS_SELECTED' => $arResult['OFFERS_SELECTED'],
		'JS_OFFERS' => $arResult['JS_OFFERS']
	)
);
unset($currencyList, $templateLibrary);

$mainId = $this->GetEditAreaId($arResult['ID']);
$itemIds = array(
	'ID' => $mainId,
	'DISCOUNT_PERCENT_ID' => $mainId.'_dsc_pict',
	'STICKER_ID' => $mainId.'_sticker',
	'BIG_SLIDER_ID' => $mainId.'_big_slider',
	'BIG_IMG_CONT_ID' => $mainId.'_bigimg_cont',
	'SLIDER_CONT_ID' => $mainId.'_slider_cont',
	'OLD_PRICE_ID' => $mainId.'_old_price',
	'PRICE_ID' => $mainId.'_price',
	'DISCOUNT_PRICE_ID' => $mainId.'_price_discount',
	'PRICE_TOTAL' => $mainId.'_price_total',
	'SLIDER_CONT_OF_ID' => $mainId.'_slider_cont_',
	'QUANTITY_ID' => $mainId.'_quantity',
	'QUANTITY_DOWN_ID' => $mainId.'_quant_down',
	'QUANTITY_UP_ID' => $mainId.'_quant_up',
	'QUANTITY_MEASURE' => $mainId.'_quant_measure',
	'QUANTITY_LIMIT' => $mainId.'_quant_limit',
	'BUY_LINK' => $mainId.'_buy_link',
	'ADD_BASKET_LINK' => $mainId.'_add_basket_link',
	'BASKET_ACTIONS_ID' => $mainId.'_basket_actions',
	'NOT_AVAILABLE_MESS' => $mainId.'_not_avail',
	'COMPARE_LINK' => $mainId.'_compare_link',
	'TREE_ID' => $mainId.'_skudiv',
	'DISPLAY_PROP_DIV' => $mainId.'_sku_prop',
	'DISPLAY_MAIN_PROP_DIV' => $mainId.'_main_sku_prop',
	'OFFER_GROUP' => $mainId.'_set_group_',
	'BASKET_PROP_DIV' => $mainId.'_basket_prop',
	'SUBSCRIBE_LINK' => $mainId.'_subscribe',
	'TABS_ID' => $mainId.'_tabs',
	'TAB_CONTAINERS_ID' => $mainId.'_tab_containers',
	'SMALL_CARD_PANEL_ID' => $mainId.'_small_card_panel',
	'TABS_PANEL_ID' => $mainId.'_tabs_panel'
);
$obName = $templateData['JS_OBJ'] = 'ob'.preg_replace('/[^a-zA-Z0-9_]/', 'x', $mainId);
$name = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']
	: $arResult['NAME'];
$title = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_TITLE']
	: $arResult['NAME'];
$alt = !empty($arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT'])
	? $arResult['IPROPERTY_VALUES']['ELEMENT_DETAIL_PICTURE_FILE_ALT']
	: $arResult['NAME'];

$haveOffers = !empty($arResult['OFFERS']);
if ($haveOffers)
{
	$actualItem = isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']])
		? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]
		: reset($arResult['OFFERS']);
	$showSliderControls = false;

	foreach ($arResult['OFFERS'] as $offer)
	{
		if ($offer['MORE_PHOTO_COUNT'] > 1)
		{
			$showSliderControls = true;
			break;
		}
	}

	$jsParams = array(
		'CONFIG' => array(
			'USE_CATALOG' => $arResult['CATALOG'],
			'SHOW_QUANTITY' => $arParams['USE_PRODUCT_QUANTITY'],
			'SHOW_PRICE' => true,
			'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'] === 'Y',
			'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'] === 'Y',
			'USE_PRICE_COUNT' => $arParams['USE_PRICE_COUNT'],
			'DISPLAY_COMPARE' => $arParams['DISPLAY_COMPARE'],
			'SHOW_SKU_PROPS' => $arResult['SHOW_OFFERS_PROPS'],
			'OFFER_GROUP' => $arResult['OFFER_GROUP'],
			'MAIN_PICTURE_MODE' => $arParams['DETAIL_PICTURE_MODE'],
			'ADD_TO_BASKET_ACTION' => $arParams['ADD_TO_BASKET_ACTION'],
			'SHOW_CLOSE_POPUP' => $arParams['SHOW_CLOSE_POPUP'] === 'Y',
			'SHOW_MAX_QUANTITY' => $arParams['SHOW_MAX_QUANTITY'],
			'RELATIVE_QUANTITY_FACTOR' => $arParams['RELATIVE_QUANTITY_FACTOR'],
			'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
			'USE_STICKERS' => true,
			'USE_SUBSCRIBE' => $showSubscribe,
			'SHOW_SLIDER' => $arParams['SHOW_SLIDER'],
			'SLIDER_INTERVAL' => $arParams['SLIDER_INTERVAL'],
			'ALT' => $alt,
			'TITLE' => $title,
			'MAGNIFIER_ZOOM_PERCENT' => 200,
			'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
			'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
			'BRAND_PROPERTY' => !empty($arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']])
				? $arResult['DISPLAY_PROPERTIES'][$arParams['BRAND_PROPERTY']]['DISPLAY_VALUE']
				: null
		),
		'PRODUCT_TYPE' => $arResult['CATALOG_TYPE'],
		'VISUAL' => $itemIds,
		'DEFAULT_PICTURE' => array(
			'PREVIEW_PICTURE' => $arResult['DEFAULT_PICTURE'],
			'DETAIL_PICTURE' => $arResult['DEFAULT_PICTURE']
		),
		'PRODUCT' => array(
			'ID' => $arResult['ID'],
			'ACTIVE' => $arResult['ACTIVE'],
			'NAME' => $arResult['~NAME'],
			'CATEGORY' => $arResult['CATEGORY_PATH']
		),
		'BASKET' => array(
			'QUANTITY' => $arParams['PRODUCT_QUANTITY_VARIABLE'],
			'BASKET_URL' => $arParams['BASKET_URL'],
			'SKU_PROPS' => $arResult['OFFERS_PROP_CODES'],
			'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
			'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE']
		),
		'OFFERS' => $arResult['JS_OFFERS'],
		'OFFER_SELECTED' => $arResult['OFFERS_SELECTED'],
		'TREE_PROPS' => $skuProps
	);
}
else
{
	$actualItem = $arResult;
	$showSliderControls = $arResult['MORE_PHOTO_COUNT'] > 1;
}

$skuProps = array();
$price = $actualItem['ITEM_PRICES'][$actualItem['ITEM_PRICE_SELECTED']];
$measureRatio = $actualItem['ITEM_MEASURE_RATIOS'][$actualItem['ITEM_MEASURE_RATIO_SELECTED']]['RATIO'];
$showDiscount = $price['PERCENT'] > 0;

$showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
$showBuyBtn = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION']);
$buyButtonClassName = in_array('BUY', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showAddBtn = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION']);
$showButtonClassName = in_array('ADD', $arParams['ADD_TO_BASKET_ACTION_PRIMARY']) ? 'btn-default' : 'btn-link';
$showSubscribe = $arParams['PRODUCT_SUBSCRIPTION'] === 'Y' && ($arResult['CATALOG_SUBSCRIBE'] === 'Y' || $haveOffers);

$arParams['MESS_BTN_BUY'] = $arParams['MESS_BTN_BUY'] ?: Loc::getMessage('CT_BCE_CATALOG_BUY');
$arParams['MESS_BTN_ADD_TO_BASKET'] = $arParams['MESS_BTN_ADD_TO_BASKET'] ?: Loc::getMessage('CT_BCE_CATALOG_ADD');
$arParams['MESS_NOT_AVAILABLE'] = $arParams['MESS_NOT_AVAILABLE'] ?: Loc::getMessage('CT_BCE_CATALOG_NOT_AVAILABLE');
$arParams['MESS_BTN_COMPARE'] = $arParams['MESS_BTN_COMPARE'] ?: Loc::getMessage('CT_BCE_CATALOG_COMPARE');
$arParams['MESS_PRICE_RANGES_TITLE'] = $arParams['MESS_PRICE_RANGES_TITLE'] ?: Loc::getMessage('CT_BCE_CATALOG_PRICE_RANGES_TITLE');
$arParams['MESS_DESCRIPTION_TAB'] = $arParams['MESS_DESCRIPTION_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_DESCRIPTION_TAB');
$arParams['MESS_PROPERTIES_TAB'] = $arParams['MESS_PROPERTIES_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_PROPERTIES_TAB');
$arParams['MESS_COMMENTS_TAB'] = $arParams['MESS_COMMENTS_TAB'] ?: Loc::getMessage('CT_BCE_CATALOG_COMMENTS_TAB');
$arParams['MESS_SHOW_MAX_QUANTITY'] = $arParams['MESS_SHOW_MAX_QUANTITY'] ?: Loc::getMessage('CT_BCE_CATALOG_SHOW_MAX_QUANTITY');
$arParams['MESS_RELATIVE_QUANTITY_MANY'] = $arParams['MESS_RELATIVE_QUANTITY_MANY'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_MANY');
$arParams['MESS_RELATIVE_QUANTITY_FEW'] = $arParams['MESS_RELATIVE_QUANTITY_FEW'] ?: Loc::getMessage('CT_BCE_CATALOG_RELATIVE_QUANTITY_FEW');

$positionClassMap = array(
	'left' => 'product-item-label-left',
	'center' => 'product-item-label-center',
	'right' => 'product-item-label-right',
	'bottom' => 'product-item-label-bottom',
	'middle' => 'product-item-label-middle',
	'top' => 'product-item-label-top'
);

$discountPositionClass = 'product-item-label-big';
if ($arParams['SHOW_DISCOUNT_PERCENT'] === 'Y' && !empty($arParams['DISCOUNT_PERCENT_POSITION']))
{
	foreach (explode('-', $arParams['DISCOUNT_PERCENT_POSITION']) as $pos)
	{
		$discountPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}

$labelPositionClass = 'product-item-label-big';
if (!empty($arParams['LABEL_PROP_POSITION']))
{
	foreach (explode('-', $arParams['LABEL_PROP_POSITION']) as $pos)
	{
		$labelPositionClass .= isset($positionClassMap[$pos]) ? ' '.$positionClassMap[$pos] : '';
	}
}
?>
<section class="catalog-detail">
    <div class="detail-img">
        <?if ($arResult['ICON'])
        {?>
            <div class="act" style="background: #<?=$arResult['ICON']['COLOR']?>;"><?=$arResult['ICON']['NAME']?></div>
        <?}?>
        <div id="product-cart-slider">
            <div id="product-cart-slider-main">
                <?if (!empty($actualItem['MORE_PHOTO']) && $actualItem['MORE_PHOTO'][0]['ID']!=0)
                {
                    foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
                    {?>
                        <a data-fancybox-group="product-gallery" href="<?=$photo['SRC_BIG']?>"><img src="<?=$photo['SRC']?>" alt="" /></a>
                    <?}
                }
                else
                {?>
                    <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/nophoto.svg" width="340" alt="" class="defaultcursor" />
                <?}?>
            </div>
            <div id="product-cart-slider-thumbnails">
                <?if (!empty($actualItem['MORE_PHOTO']) && $actualItem['MORE_PHOTO'][0]['ID']!=0)
                {
                    foreach ($actualItem['MORE_PHOTO'] as $key => $photo)
                    {?>
                        <img src="<?=$photo['SRC_SMALL']?>" alt="" />
                    <?}
                }
                else
                {?>

                <?}?>
            </div>
        </div>
    </div>

    <div class="hideFull detail-text detail-text-mobile">
        <div class="price"><?=format_price($arResult['MIN_PRICE']['VALUE'])?>&nbsp;<span class="rub">Р</span></div>
        <?if ($arResult['CAN_BUY']=='Y')
		{?>
			<div class='count'><input type="number" autocomplete="off" min="1" value="1"/></div>
		<?}?>
        <a href="#" class="inFavorite hideMobile favoriteBtn" data-favorite-id="<?=$arResult['ID']?>"></a>
        <div class="clear"></div>
        <div class="dbtn">
			<?if ($arResult['CAN_BUY']=='Y')
			{?>
				<a href="#" class="blackBtn buyBtn" data-id="<?=$arResult['ID']?>"><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></a>
				<a href="#" class="inFavorite hideFull favoriteBtn" data-favorite-id="<?=$arResult['ID']?>"></a>
			<?}
			else
			{?>
				<div class="nogoods">Товар отсутсвует на складе</div>
			<?}?>
        </div>
    </div>

    <div class="detail-text">
        <?
        $dprop = '';
        if ($arResult['DISPLAY_PROPERTIES'])
        {?>
            <table class="detail-table hideMobile">
                <?
                foreach($arResult['DISPLAY_PROPERTIES'] as $prop)
                {
                    $dprop .= '<tr><td>'.$prop['NAME'].'</td><td>'.$prop['DISPLAY_VALUE'].'</td></tr>';
                }
                echo $dprop;
                ?>
            </table>
        <?}
        $showDescription = !empty($arResult['PREVIEW_TEXT']) || !empty($arResult['DETAIL_TEXT']);
        if ($showDescription)
        {
            if (
                $arResult['PREVIEW_TEXT'] != ''
                && (
                    $arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'S'
                    || ($arParams['DISPLAY_PREVIEW_TEXT_MODE'] === 'E' && $arResult['DETAIL_TEXT'] == '')
                )
            )
            {
                echo $arResult['PREVIEW_TEXT_TYPE'] === 'html' ? $arResult['PREVIEW_TEXT'] : '<p>'.$arResult['PREVIEW_TEXT'].'</p>';
            }

            if ($arResult['DETAIL_TEXT'] != '')
            {
                echo $arResult['DETAIL_TEXT_TYPE'] === 'html' ? $arResult['DETAIL_TEXT'] : '<p>'.$arResult['DETAIL_TEXT'].'</p>';
            }
        }?>
        <div class="price hideMobile"><?=format_price($arResult['MIN_PRICE']['VALUE'])?>&nbsp;<span class="rub">Р</span></div>
        <?if ($arResult['CAN_BUY']=='Y')
		{?>
			<div class='count hideMobile'><input type="number" autocomplete="off" min="1" value="1"/></div>
		<?}?>
        <a href="#" class="inFavorite hideMobile favoriteBtn" data-favorite-id="<?=$arResult['ID']?>"></a>
        <div class="clear"></div>
        <div class="dbtn hideMobile">
			<?if ($arResult['CAN_BUY']=='Y')
			{?>
				<a href="#" class="blackBtn buyBtn " data-id="<?=$arResult['ID']?>"><?=$arParams['MESS_BTN_ADD_TO_BASKET']?></a>
				<a href="#" class="inFavorite hideFull favoriteBtn" data-favorite-id="<?=$arResult['ID']?>"></a>
			<?}
			else
			{?>
				<div class="nogoods">Товар отсутсвует на складе</div>
			<?}?>
        </div>
        <?if ($dprop)
        {?>
        <table class="detail-table hideFull">
            <?
            echo $dprop;
            ?>
        </table>
        <?}?>
        <noindex>
            <?
            $APPLICATION->IncludeComponent("bitrix:main.share", "", array(
                "HANDLERS" => [0=>'facebook', 1=>'vk'],
                "PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
                "PAGE_TITLE" => $arResult["~NAME"],
                "SHORTEN_URL_LOGIN" => '',
                "SHORTEN_URL_KEY" => '',
                "HIDE" => 'N',
            ),
                $component,
                array("HIDE_ICONS" => "Y")
            );
            ?>
        </noindex>
    </div>
</section>
<div class="clear"></div>
<?
if ($arResult['PROPERTIES']['ADDITIONAL']['VALUE']) {
    GLOBAL $arrFilterCatalog;
    $arrFilterCatalog['ID'] = $arResult['PROPERTIES']['ADDITIONAL']['VALUE'];
    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section",
        "catalog_additional",
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
            "DISPLAY_COMPARE" => "Y",
            "DISPLAY_TOP_PAGER" => "N",
            "ELEMENT_SORT_FIELD" => "RAND",
            "ELEMENT_SORT_FIELD2" => "name",
            "ELEMENT_SORT_ORDER" => "asc",
            "ELEMENT_SORT_ORDER2" => "asc",
            "ENLARGE_PRODUCT" => "STRICT",
            "FILTER_NAME" => "arrFilterCatalog",
            "HIDE_NOT_AVAILABLE" => "N",
            "HIDE_NOT_AVAILABLE_OFFERS" => "N",
            "IBLOCK_ID" => "3",
            "IBLOCK_TYPE" => "catalog",
            "INCLUDE_SUBSECTIONS" => "Y",
            "LABEL_PROP" => array(),
            "LABEL_PROP_MOBILE" => "",
            "LABEL_PROP_POSITION" => "top-left",
            "LAZY_LOAD" => "N",
            "LINE_ELEMENT_COUNT" => "3",
            "LOAD_ON_SCROLL" => "N",
            "MESSAGE_404" => "",
            "MESS_BTN_ADD_TO_BASKET" => "В корзину",
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
            "PAGE_ELEMENT_COUNT" => "18",
            "PARTIAL_PRODUCT_PROPERTIES" => "N",
            "PRICE_CODE" => array(
				0 => "Розничные",
            ),
            "PRICE_VAT_INCLUDE" => "Y",
            "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
            "PRODUCT_ID_VARIABLE" => "id",
            "PRODUCT_PROPERTIES" => array(
                0 => 'ICON'
            ),
            "PRODUCT_PROPS_VARIABLE" => "prop",
            "PRODUCT_QUANTITY_VARIABLE" => "quantity",
            "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
            "PRODUCT_SUBSCRIPTION" => "Y",
            "PROPERTY_CODE" => array(
                0 => "",
                1 => "",
            ),
            "PROPERTY_CODE_MOBILE" => array(),
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
            "COMPONENT_TEMPLATE" => "catalog_list_new",
            "COMPARE_PATH" => "",
            "MESS_BTN_COMPARE" => "Сравнить",
            "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
            'TITLE_H1' => 'С этим товаром покупают'
        ),
        false
    );
}
?>

<div class="back-detail">
    <div class="btn-block">
        <a href="<?=$arResult['SECTION']['SECTION_PAGE_URL']?>"><span><?=GetMessage('BACK');?></span></a>
    </div>
</div>
<?
/*if ($arParams['USE_COMMENTS'] === 'Y')
{?>
    <section class="comment">
        <?$APPLICATION->IncludeComponent(
            'bitrix:catalog.comments',
            '',
            array(
                'ELEMENT_ID' => $arResult['ID'],
                'ELEMENT_CODE' => '',
                'IBLOCK_ID' => $arParams['IBLOCK_ID'],
                'SHOW_DEACTIVATED' => $arParams['SHOW_DEACTIVATED'],
                'URL_TO_COMMENT' => '',
                'WIDTH' => '',
                'COMMENTS_COUNT' => '5',
                'BLOG_USE' => $arParams['BLOG_USE'],
                'FB_USE' => $arParams['FB_USE'],
                'FB_APP_ID' => $arParams['FB_APP_ID'],
                'VK_USE' => $arParams['VK_USE'],
                'VK_API_ID' => $arParams['VK_API_ID'],
                'CACHE_TYPE' => $arParams['CACHE_TYPE'],
                'CACHE_TIME' => $arParams['CACHE_TIME'],
                'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],
                'BLOG_TITLE' => '',
                'BLOG_URL' => $arParams['BLOG_URL'],
                'PATH_TO_SMILE' => '',
                'EMAIL_NOTIFY' => $arParams['BLOG_EMAIL_NOTIFY'],
                'AJAX_POST' => 'Y',
                'SHOW_SPAM' => 'Y',
                'SHOW_RATING' => 'N',
                'FB_TITLE' => '',
                'FB_USER_ADMIN_ID' => '',
                'FB_COLORSCHEME' => 'light',
                'FB_ORDER_BY' => 'reverse_time',
                'VK_TITLE' => '',
                'TEMPLATE_THEME' => $arParams['~TEMPLATE_THEME']
            ),
            $component,
            array('HIDE_ICONS' => 'Y')
        );?>
    </section>
<?}*/
if ($arParams['DISPLAY_COMPARE'])
{
	$jsParams['COMPARE'] = array(
		'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
		'COMPARE_DELETE_URL_TEMPLATE' => $arResult['~COMPARE_DELETE_URL_TEMPLATE'],
		'COMPARE_PATH' => $arParams['COMPARE_PATH']
	);
}
?>
<script>
	BX.message({
		ECONOMY_INFO_MESSAGE: '<?=GetMessageJS('CT_BCE_CATALOG_ECONOMY_INFO2')?>',
		TITLE_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_ERROR')?>',
		TITLE_BASKET_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_TITLE_BASKET_PROPS')?>',
		BASKET_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_BASKET_UNKNOWN_ERROR')?>',
		BTN_SEND_PROPS: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_SEND_PROPS')?>',
		BTN_MESSAGE_BASKET_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_BASKET_REDIRECT')?>',
		BTN_MESSAGE_CLOSE: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE')?>',
		BTN_MESSAGE_CLOSE_POPUP: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_CLOSE_POPUP')?>',
		TITLE_SUCCESSFUL: '<?=GetMessageJS('CT_BCE_CATALOG_ADD_TO_BASKET_OK')?>',
		COMPARE_MESSAGE_OK: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_OK')?>',
		COMPARE_UNKNOWN_ERROR: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_UNKNOWN_ERROR')?>',
		COMPARE_TITLE: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_COMPARE_TITLE')?>',
		BTN_MESSAGE_COMPARE_REDIRECT: '<?=GetMessageJS('CT_BCE_CATALOG_BTN_MESSAGE_COMPARE_REDIRECT')?>',
		PRODUCT_GIFT_LABEL: '<?=GetMessageJS('CT_BCE_CATALOG_PRODUCT_GIFT_LABEL')?>',
		PRICE_TOTAL_PREFIX: '<?=GetMessageJS('CT_BCE_CATALOG_MESS_PRICE_TOTAL_PREFIX')?>',
		RELATIVE_QUANTITY_MANY: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_MANY'])?>',
		RELATIVE_QUANTITY_FEW: '<?=CUtil::JSEscape($arParams['MESS_RELATIVE_QUANTITY_FEW'])?>',
		SITE_ID: '<?=SITE_ID?>'
	});

	var <?=$obName?> = new JCCatalogElement(<?=CUtil::PhpToJSObject($jsParams, false, true)?>);
</script>
<?
unset($actualItem, $itemIds, $jsParams);