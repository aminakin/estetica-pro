<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc;
\Bitrix\Main\UI\Extension::load("ui.fonts.ruble");

/**
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 * @var string $templateName
 * @var CMain $APPLICATION
 * @var CBitrixBasketComponent $component
 * @var CBitrixComponentTemplate $this
 * @var array $giftParameters
 */
$APPLICATION->SetTitle("Корзина");
$documentRoot = Main\Application::getDocumentRoot();
global $USER;
if (empty($arParams['TEMPLATE_THEME']))
{
	$arParams['TEMPLATE_THEME'] = Main\ModuleManager::isModuleInstalled('bitrix.eshop') ? 'site' : 'blue';
}

if ($arParams['TEMPLATE_THEME'] === 'site')
{
	$templateId = Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', $component->getSiteId());
	$templateId = preg_match('/^eshop_adapt/', $templateId) ? 'eshop_adapt' : $templateId;
	$arParams['TEMPLATE_THEME'] = Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', $component->getSiteId());
}

if (!empty($arParams['TEMPLATE_THEME']))
{
	if (!is_file($documentRoot.'/bitrix/css/main/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
	{
		$arParams['TEMPLATE_THEME'] = 'blue';
	}
}

if (!isset($arParams['DISPLAY_MODE']) || !in_array($arParams['DISPLAY_MODE'], array('extended', 'compact')))
{
	$arParams['DISPLAY_MODE'] = 'extended';
}

$arParams['USE_DYNAMIC_SCROLL'] = isset($arParams['USE_DYNAMIC_SCROLL']) && $arParams['USE_DYNAMIC_SCROLL'] === 'N' ? 'N' : 'Y';
$arParams['SHOW_FILTER'] = isset($arParams['SHOW_FILTER']) && $arParams['SHOW_FILTER'] === 'N' ? 'N' : 'Y';

$arParams['PRICE_DISPLAY_MODE'] = isset($arParams['PRICE_DISPLAY_MODE']) && $arParams['PRICE_DISPLAY_MODE'] === 'N' ? 'N' : 'Y';

if (!isset($arParams['TOTAL_BLOCK_DISPLAY']) || !is_array($arParams['TOTAL_BLOCK_DISPLAY']))
{
	$arParams['TOTAL_BLOCK_DISPLAY'] = array('top');
}

if (empty($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = 'props,sku,columns';
}

if (is_string($arParams['PRODUCT_BLOCKS_ORDER']))
{
	$arParams['PRODUCT_BLOCKS_ORDER'] = explode(',', $arParams['PRODUCT_BLOCKS_ORDER']);
}

$arParams['USE_PRICE_ANIMATION'] = isset($arParams['USE_PRICE_ANIMATION']) && $arParams['USE_PRICE_ANIMATION'] === 'N' ? 'N' : 'Y';
$arParams['EMPTY_BASKET_HINT_PATH'] = isset($arParams['EMPTY_BASKET_HINT_PATH']) ? (string)$arParams['EMPTY_BASKET_HINT_PATH'] : '/';
$arParams['USE_ENHANCED_ECOMMERCE'] = isset($arParams['USE_ENHANCED_ECOMMERCE']) && $arParams['USE_ENHANCED_ECOMMERCE'] === 'Y' ? 'Y' : 'N';
$arParams['DATA_LAYER_NAME'] = isset($arParams['DATA_LAYER_NAME']) ? trim($arParams['DATA_LAYER_NAME']) : 'dataLayer';
$arParams['BRAND_PROPERTY'] = isset($arParams['BRAND_PROPERTY']) ? trim($arParams['BRAND_PROPERTY']) : '';

if ($arParams['USE_GIFTS'] === 'Y')
{
	CBitrixComponent::includeComponentClass('bitrix:sale.products.gift.basket');

	$giftParameters = array(
		'SHOW_PRICE_COUNT' => 1,
		'PRODUCT_SUBSCRIPTION' => 'N',
		'PRODUCT_ID_VARIABLE' => 'id',
		'USE_PRODUCT_QUANTITY' => 'N',
		'ACTION_VARIABLE' => 'actionGift',
		'ADD_PROPERTIES_TO_BASKET' => 'Y',
		'PARTIAL_PRODUCT_PROPERTIES' => 'Y',

		'BASKET_URL' => $APPLICATION->GetCurPage(),
		'APPLIED_DISCOUNT_LIST' => $arResult['APPLIED_DISCOUNT_LIST'],
		'FULL_DISCOUNT_LIST' => $arResult['FULL_DISCOUNT_LIST'],

		'TEMPLATE_THEME' => $arParams['TEMPLATE_THEME'],
		'PRICE_VAT_INCLUDE' => $arParams['PRICE_VAT_SHOW_VALUE'],
		'CACHE_GROUPS' => $arParams['CACHE_GROUPS'],

		'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
		'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
		'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],

		'DETAIL_URL' => isset($arParams['GIFTS_DETAIL_URL']) ? $arParams['GIFTS_DETAIL_URL'] : null,
		'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
		'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
		'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
		'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
		'DISCOUNT_PERCENT_POSITION' => $arParams['DISCOUNT_PERCENT_POSITION'],
		'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
		'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
		'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
		'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

		'PRODUCT_ROW_VARIANTS' => '',
		'PAGE_ELEMENT_COUNT' => 0,
		'DEFERRED_PRODUCT_ROW_VARIANTS' => \Bitrix\Main\Web\Json::encode(
			SaleProductsGiftBasketComponent::predictRowVariants(
				$arParams['GIFTS_PAGE_ELEMENT_COUNT'],
				$arParams['GIFTS_PAGE_ELEMENT_COUNT']
			)
		),
		'DEFERRED_PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],

		'ADD_TO_BASKET_ACTION' => 'BUY',
		'PRODUCT_DISPLAY_MODE' => 'Y',
		'PRODUCT_BLOCKS_ORDER' => isset($arParams['GIFTS_PRODUCT_BLOCKS_ORDER']) ? $arParams['GIFTS_PRODUCT_BLOCKS_ORDER'] : '',
		'SHOW_SLIDER' => isset($arParams['GIFTS_SHOW_SLIDER']) ? $arParams['GIFTS_SHOW_SLIDER'] : '',
		'SLIDER_INTERVAL' => isset($arParams['GIFTS_SLIDER_INTERVAL']) ? $arParams['GIFTS_SLIDER_INTERVAL'] : '',
		'SLIDER_PROGRESS' => isset($arParams['GIFTS_SLIDER_PROGRESS']) ? $arParams['GIFTS_SLIDER_PROGRESS'] : '',
		'LABEL_PROP_POSITION' => $arParams['LABEL_PROP_POSITION'],

		'USE_ENHANCED_ECOMMERCE' => $arParams['USE_ENHANCED_ECOMMERCE'],
		'DATA_LAYER_NAME' => $arParams['DATA_LAYER_NAME'],
		'BRAND_PROPERTY' => $arParams['BRAND_PROPERTY']
	);
}

\CJSCore::Init(array('fx', 'popup', 'ajax'));

$this->addExternalCss('/bitrix/css/main/bootstrap.css');
$this->addExternalCss($templateFolder.'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css');

$this->addExternalJs($templateFolder.'/js/mustache.js');
$this->addExternalJs($templateFolder.'/js/action-pool.js');
$this->addExternalJs($templateFolder.'/js/filter.js');
$this->addExternalJs($templateFolder.'/js/component.js');

$mobileColumns = isset($arParams['COLUMNS_LIST_MOBILE'])
	? $arParams['COLUMNS_LIST_MOBILE']
	: $arParams['COLUMNS_LIST'];
$mobileColumns = array_fill_keys($mobileColumns, true);

$jsTemplates = new Main\IO\Directory($documentRoot.$templateFolder.'/js-templates');
/** @var Main\IO\File $jsTemplate */
foreach ($jsTemplates->getChildren() as $jsTemplate)
{
	include($jsTemplate->getPath());
}

$displayModeClass = $arParams['DISPLAY_MODE'] === 'compact' ? ' basket-items-list-wrapper-compact' : '';

if (empty($arResult['ERROR_MESSAGE']))
{
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'TOP')
	{
		$APPLICATION->IncludeComponent(
			'bitrix:sale.products.gift.basket',
			'.default',
			$giftParameters,
			$component
		);
	}

	if ($arResult['BASKET_ITEM_MAX_COUNT_EXCEEDED'])
	{
		?>
		<div id="basket-item-message">
			<?=Loc::getMessage('SBB_BASKET_ITEM_MAX_COUNT_EXCEEDED', array('#PATH#' => $arParams['PATH_TO_BASKET']))?>
		</div>
		<?
	}
	?>

		<?/*div class="personal-basket-header">
			<div class="personal-basket-header-title">Корзина</div>
			<div class="container" style="position: relative;">
				<a class="personal-basket-header-back" href="/">
					<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M6.9043 13L1.09588 6.94897" stroke="black" stroke-width="0.75" stroke-linecap="round"></path>
						<path d="M1.09082 6.87183L6.91308 1.00005" stroke="black" stroke-width="0.75" stroke-linecap="round"></path>
					</svg>
				</a>
			</div>
		</div*/?>
		<?/*<div class="notification-supercart" id="messageNoKirov" style="display: none !important;">
		<p>Для минимальной суммы заказа выберите товар еще на <b><span class="minPrice"></span></b> рублей. </p><p>Для бесплатной доставки выберите товар еще на <b><span class="freeDost"></span></b> рублей.</p>
		</div>*/?>
	<div id="basket-root" class="bx-basket bx-<?=$arParams['TEMPLATE_THEME']?> bx-step-opacity" style="opacity: 0;">
		<?
		if (
			$arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y'
			&& in_array('top', $arParams['TOTAL_BLOCK_DISPLAY'])
		)
		{
			?>
			<div class="row">
				<div class="col-xs-12" data-entity="basket-total-block"></div>
			</div>
			<?
		}
		?>

		<?/*div class="row">
			<div class="col-xs-12">
				<div class="alert alert-warning alert-dismissable" id="basket-warning" style="display: none;">
					<span class="close" data-entity="basket-items-warning-notification-close">&times;</span>
					<div data-entity="basket-general-warnings"></div>
					<div data-entity="basket-item-warnings">
						<?//=Loc::getMessage('SBB_BASKET_ITEM_WARNING'?>
					</div>
				</div>
				<div class="basket-attention"><span>!</span><?//= GetMessage('SBB_AUTENTION');?></div>
			</div>
		</div*/?>

		<div class="row">
			<div class="col-xs-12">
				<div class="basket-items-list-wrapper basket-items-list-wrapper-height-fixed basket-items-list-wrapper-light<?=$displayModeClass?>"
					id="basket-items-list-wrapper">
					<div class="basket-items-list-header" data-entity="basket-items-list-header">
						<div class="basket-items-search-field" data-entity="basket-filter">
							<div class="form has-feedback">
								<input type="text" class="form-control"
									placeholder="<?=Loc::getMessage('SBB_BASKET_FILTER')?>"
									data-entity="basket-filter-input">
								<span class="form-control-feedback basket-clear" data-entity="basket-filter-clear-btn"></span>
							</div>
						</div>
						<div class="basket-items-list-header-filter">
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item count active"
								data-entity="basket-items-count" data-filter="all" style="display: none;">
                            </a>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="similar" style="display: none;"></a>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="warning" style="display: none;"></a>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="delayed" style="display: none;"></a>
							<a href="javascript:void(0)" class="basket-items-list-header-filter-item"
								data-entity="basket-items-count" data-filter="not-available" style="display: none;"></a>
						</div>
					</div>
					<div class="basket-items-list-container" id="basket-items-list-container">
						<div class="basket-items-list-overlay" id="basket-items-list-overlay" style="display: none;"></div>
						<div class="basket-items-list" id="basket-item-list">
							<div class="basket-search-not-found" id="basket-item-list-empty-result" style="display: none;">
								<div class="basket-search-not-found-icon"></div>
								<div class="basket-search-not-found-text">
									<?=Loc::getMessage('SBB_FILTER_EMPTY_RESULT')?>
								</div>
							</div>
                            <div class="basket-items-list-table-inner-block">
                                <div class="basket-items-list-table-inner-block__product-left-block-wrapper">
                                    <div style="display:none" class="basket-items-list-table-inner-block__toggle-switch-all basket-toggle-switch basket-toggle-switch-all checked">
                                        <div class="basket-toggle-switch__circle"></div>
                                    </div>
                                    <div class="basket-items-list-table-inner-block__product-count">
                                        <span style="display:none" class="basket-items-list-table-inner-block__product-value">0</span>
                                        <span style="display:none" class="basket-items-list-table-inner-block__product-title"><?= num_word(0, ['товар','товара','товаров'], false) ?></span>
									</div>
                                </div>
                                <div class="basket-items-list-table-inner-block__basket-three-dot-menu">
                                    <div class="basket-items-list-table-inner-block__basket-three-dot-title">
                                        <div class="basket-items-list-table-inner-block__basket-dot"></div>
                                        <div class="basket-items-list-table-inner-block__basket-dot"></div>
                                        <div class="basket-items-list-table-inner-block__basket-dot"></div>
                                    </div>
                                    <div class="basket-items-list-table-inner-block__basket-menu-list">
                                        <div class="basket-items-list-table-inner-block__basket-menu-item deselect-all">Снять выбор со всех</div>
                                        <div class="basket-items-list-table-inner-block__basket-menu-item select-all">Выбрать все</div>
                                        <div class="basket-items-list-table-inner-block__basket-menu-item add-to-favorites
                                        <?= !$USER->IsAuthorized()?"linkOpen":""?>" <?= !$USER->IsAuthorized()?" href=\"#no_fav_popup\"":""?>>Добавить выбранные в Избранное</div>
                                        <?/*div class="basket-items-list-table-inner-block__basket-menu-item clear-basket">Очистить корзину</div*/?>
                                    </div>
                                </div>
                            </div>
							<div class="basket-items-list-table" id="basket-item-table"></div>
                            <div class="clear-basket-block">
                                <span class="clear-basket">Очистить корзину</span>
                            </div>
						</div>
                        <div class="basket-total" data-entity="basket-total-block"></div>
						
					</div>
				</div>
			</div>
		</div>
		<?if ($arParams['BASKET_WITH_ORDER_INTEGRATION'] !== 'Y' && in_array('bottom', $arParams['TOTAL_BLOCK_DISPLAY'])){?>
		<?}?>
	</div>
    <div class="notification-supercart" id="messageNoKirovMobile" style="display: none;">
        <p>Для минимальной суммы заказа выберите товар еще на <b><span class="minPrice"></span></b> рублей. </p><p>Для бесплатной доставки выберите товар еще на <b><span class="freeDost"></span></b> рублей.</p>
    </div>
	<?/*<div class="our-new-wrap">
	<div class="our-new">
		<?= GetMessage('SBB_BASKET_OUR_NEW');*/?>
	<?/*</div>
	<? $APPLICATION->IncludeComponent(
                "bitrix:catalog.top",
                "search",
                array(
                    "ACTION_VARIABLE" => "action",
                    "ADD_PICT_PROP" => "MORE_PHOTO",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "ADD_TO_BASKET_ACTION" => "ADD",
                    "BASKET_URL" => "/personal/basket.php",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
                    "COMPATIBLE_MODE" => "Y",
                    "COMPONENT_TEMPLATE" => "search",
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
                    "IBLOCK_ID" => "14",
                    "IBLOCK_TYPE" => "catalog",
                    "LABEL_PROP" => array(
                    ),
                    "LABEL_PROP_MOBILE" => "",
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
                        0 => "IZGIB",
                        1 => "TOLSHCHINA",
                        2 => "TSVET_UPAKOVKI",
                        3 => "DLINA",
                        4 => "TSVET",
                        5 => "",
                    ),
                    "OFFERS_SORT_FIELD" => "shows",
                    "OFFERS_SORT_FIELD2" => "shows",
                    "OFFERS_SORT_ORDER" => "asc",
                    "OFFERS_SORT_ORDER2" => "asc",
                    "OFFER_ADD_PICT_PROP" => "-",
                    "OFFER_TREE_PROPS" => array(
                        0 => "IZGIB",
                        1 => "TOLSHCHINA",
                        2 => "TSVET_UPAKOVKI",
                        3 => "DLINA",
                        4 => "TSVET",
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
                    "SHOW_PAGINATION" => "Y",
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
            ); */?>
	<?/*</div>*/?>
	<div class="popular" data-entity="parent-container">
<div class="catalog-block-header" data-entity="header" data-showed="false">
                            Вам может понравиться                        </div>
	<div class="detail-section">
                        <?
                        $APPLICATION->IncludeComponent(
                            'bitrix:catalog.section',
                            'detail-page',
                            array (
							  'IBLOCK_TYPE' => 'catalog',
							  'IBLOCK_ID' => '14',
							  'SECTION_ID' => 0,
							  'SECTION_CODE' => 'enigma',
							  'ELEMENT_SORT_FIELD' => 'shows',
							  'ELEMENT_SORT_ORDER' => 'desc',
							  'ELEMENT_SORT_FIELD2' => 'sort',
							  'ELEMENT_SORT_ORDER2' => 'asc',
							  'PROPERTY_CODE' => 
							  array (
							    0 => '',
							    1 => 'ICON',
							    2 => '',
							  ),
							  'PROPERTY_CODE_MOBILE' => 
							  array (
							  ),
							  'INCLUDE_SUBSECTIONS' => 'A',
							  'BASKET_URL' => '/personal/cart/',
							  'ACTION_VARIABLE' => 'action',
							  'PRODUCT_ID_VARIABLE' => 'id',
							  'SECTION_ID_VARIABLE' => 'SECTION_ID',
							  'PRODUCT_QUANTITY_VARIABLE' => 'quantity',
							  'PRODUCT_PROPS_VARIABLE' => 'prop',
							  'CACHE_TYPE' => 'A',
							  'CACHE_TIME' => '36000000',
							  'CACHE_FILTER' => 'N',
							  'CACHE_GROUPS' => 'Y',
							  'DISPLAY_COMPARE' => 'N',
							  'PRICE_CODE' => 
							  array (
							    0 => 'Типовое соглашение с клиентами',
							  ),
							  'USE_PRICE_COUNT' => 'N',
							  'SHOW_PRICE_COUNT' => '1',
							  'PAGE_ELEMENT_COUNT' => 4,
							  'FILTER_IDS' => 
							  array (
							    0 => 53613,
							  ),
							  'SET_TITLE' => 'N',
							  'SET_BROWSER_TITLE' => 'N',
							  'SET_META_KEYWORDS' => 'N',
							  'SET_META_DESCRIPTION' => 'N',
							  'SET_LAST_MODIFIED' => 'N',
							  'ADD_SECTIONS_CHAIN' => 'N',
							  'PRICE_VAT_INCLUDE' => 'Y',
							  'USE_PRODUCT_QUANTITY' => 'Y',
							  'ADD_PROPERTIES_TO_BASKET' => 'Y',
							  'PARTIAL_PRODUCT_PROPERTIES' => 'Y',
							  'PRODUCT_PROPERTIES' => 
							  array (
							  ),
							  'OFFERS_CART_PROPERTIES' => 
							  array (
							    0 => 'IZGIB',
							    1 => 'TOLSHCHINA',
							    2 => 'TSVET_UPAKOVKI',
							    3 => 'DLINA',
							    4 => 'TSVET',
							    5 => 'TOLSHCHINA_1',
							    6 => 'RAZMER',
							    7 => 'AROMAT',
							  ),
							  'OFFERS_FIELD_CODE' => 
							  array (
							    0 => '',
							    1 => '',
							  ),
							  'OFFERS_PROPERTY_CODE' => 
							  array (
							    0 => 'IZGIB',
							    1 => 'TOLSHCHINA',
							    2 => 'TSVET_UPAKOVKI',
							    3 => 'DLINA',
							    4 => 'TSVET',
							    5 => 'TOLSHCHINA_1',
							    6 => 'DLINA_1',
							    7 => 'RAZMER',
							    8 => 'AROMAT',
							    9 => 'IRGIB',
							    10 => 'TOLSHINA',
							    11 => '65',
							    12 => '',
							  ),
							  'OFFERS_SORT_FIELD' => 'shows',
							  'OFFERS_SORT_ORDER' => 'asc',
							  'OFFERS_SORT_FIELD2' => 'shows',
							  'OFFERS_SORT_ORDER2' => 'asc',
							  'OFFERS_LIMIT' => '0',
							  'SECTION_URL' => '/catalog/#SECTION_CODE#/',
							  'DETAIL_URL' => '/catalog/#SECTION_CODE#/#ELEMENT_CODE#/',
							  'USE_MAIN_ELEMENT_SECTION' => 'N',
							  'CONVERT_CURRENCY' => 'N',
							  'CURRENCY_ID' => '',
							  'HIDE_NOT_AVAILABLE' => 'N',
							  'HIDE_NOT_AVAILABLE_OFFERS' => 'L',
							  'LABEL_PROP' => 
							  array (
							    0 => 'NEW',
							    1 => 'AKTSIONNYY',
							    2 => 'SALE_RESNITSY',
							  ),
							  'LABEL_PROP_MOBILE' => 
							  array (
							    0 => 'NEW',
							    1 => 'AKTSIONNYY',
							    2 => 'SALE_RESNITSY',
							  ),
							  'LABEL_PROP_POSITION' => 'top-left',
							  'ADD_PICT_PROP' => 'MORE_PHOTO',
							  'PRODUCT_DISPLAY_MODE' => 'Y',
							  'PRODUCT_BLOCKS_ORDER' => 'price,quantityLimit,sku,quantity,buttons,compare,props',
							  'PRODUCT_ROW_VARIANTS' => '[{\'VARIANT\':\'3\',\'BIG_DATA\':false}]',
							  'ENLARGE_PRODUCT' => 'STRICT',
							  'ENLARGE_PROP' => '',
							  'SHOW_SLIDER' => 'N',
							  'SLIDER_INTERVAL' => '3000',
							  'SLIDER_PROGRESS' => 'N',
							  'DISPLAY_TOP_PAGER' => 'N',
							  'DISPLAY_BOTTOM_PAGER' => 'N',
							  'HIDE_SECTION_DESCRIPTION' => 'Y',
							  'RCM_TYPE' => 'personal',
							  'RCM_PROD_ID' => 53613,
							  'SHOW_FROM_SECTION' => 'Y',
							  'OFFER_ADD_PICT_PROP' => '-',
							  'OFFER_TREE_PROPS' => 
							  array (
							    0 => 'IZGIB',
							    1 => 'TOLSHCHINA',
							    2 => 'TSVET_UPAKOVKI',
							    3 => 'DLINA',
							    4 => 'TSVET',
							    5 => 'RAZMER',
							    6 => 'AROMAT',
							  ),
							  'PRODUCT_SUBSCRIPTION' => 'Y',
							  'SHOW_DISCOUNT_PERCENT' => 'Y',
							  'DISCOUNT_PERCENT_POSITION' => 'bottom-right',
							  'SHOW_OLD_PRICE' => 'Y',
							  'SHOW_MAX_QUANTITY' => 'N',
							  'MESS_SHOW_MAX_QUANTITY' => 'Наличие',
							  'RELATIVE_QUANTITY_FACTOR' => '',
							  'MESS_RELATIVE_QUANTITY_MANY' => '',
							  'MESS_RELATIVE_QUANTITY_FEW' => '',
							  'MESS_BTN_BUY' => 'Купить',
							  'MESS_BTN_ADD_TO_BASKET' => 'В корзину',
							  'MESS_BTN_SUBSCRIBE' => 'Сообщить о поступлении',
							  'MESS_BTN_DETAIL' => 'Подробнее',
							  'MESS_NOT_AVAILABLE' => 'Сообщить о поступлении',
							  'MESS_BTN_COMPARE' => 'Сравнение',
							  'USE_ENHANCED_ECOMMERCE' => 'N',
							  'DATA_LAYER_NAME' => '',
							  'BRAND_PROPERTY' => '',
							  'TEMPLATE_THEME' => 'site',
							  'ADD_TO_BASKET_ACTION' => 
							  array (
							    0 => 'ADD',
							  ),
							  'SHOW_CLOSE_POPUP' => 'Y',
							  'COMPARE_PATH' => '/catalog/compare.php?action=#ACTION_CODE#',
							  'COMPARE_NAME' => 'CATALOG_COMPARE_LIST',
							  'BACKGROUND_IMAGE' => '',
							  'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
							),
                            $component
                        );
                        ?>
                        </div>
                    </div>
	<?
	if (!empty($arResult['CURRENCIES']) && Main\Loader::includeModule('currency'))
	{
		CJSCore::Init('currency');

		?>
		<script>
			BX.Currency.setCurrencies(<?=CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true)?>);
		</script>
		<?
	}

	$signer = new \Bitrix\Main\Security\Sign\Signer;
	$signedTemplate = $signer->sign($templateName, 'sale.basket.basket');
	$signedParams = $signer->sign(base64_encode(serialize($arParams)), 'sale.basket.basket');
	$messages = Loc::loadLanguageFile(__FILE__);
	?>
	<script>
		BX.message(<?=CUtil::PhpToJSObject($messages)?>);
		BX.Sale.BasketComponent.init({
			result: <?=CUtil::PhpToJSObject($arResult, false, false, true)?>,
			params: <?=CUtil::PhpToJSObject($arParams)?>,
			template: '<?=CUtil::JSEscape($signedTemplate)?>',
			signedParamsString: '<?=CUtil::JSEscape($signedParams)?>',
			siteId: '<?=CUtil::JSEscape($component->getSiteId())?>',
			templateFolder: '<?=CUtil::JSEscape($templateFolder)?>',
            dotButton: '.basket-items-list-table-inner-block__basket-three-dot-title',
			shareButton: '.basket-checkout-section-share',
            dotMenu: '.basket-items-list-table-inner-block__basket-three-dot-menu'
		});

	</script>

  <div class="include_popup">
  <? //$APPLICATION->IncludeFile('/include/popup-bonus.php'); ?>
  <? //$APPLICATION->IncludeFile('/include/popup-delivery.php'); ?>
  </div>
  <?
	if ($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
	{
		$APPLICATION->IncludeComponent(
			'bitrix:sale.products.gift.basket',
			'.default',
			$giftParameters,
			$component
		);
	}
}
elseif ($arResult['EMPTY_BASKET'])
{
	include(Main\Application::getDocumentRoot().$templateFolder.'/empty.php');
}
else
{
	ShowError($arResult['ERROR_MESSAGE']);
}


use Bitrix\Sale;
$basket = Sale\Basket::loadItemsForFUser(Sale\Fuser::getId(), Bitrix\Main\Context::getCurrent()->getSite());

if($_GET['addbasketproducts'] == 'y'){
    $add = false;
    foreach($_GET as $prodid=>$peodqty){
        if(intval($prodid)){
            $res = CIBlockElement::GetByID($prodid);
			if($ar_res = $res->GetNext()){
				if($ar_res['ACTIVE'] != 'N' ){
					$ar_prod = CCatalogProduct::GetByID($prodid);
					if($ar_prod['QUANTITY'] > 0 ){
                        $result = Add2BasketByProductID(
                           $prodid,
                           $peodqty
                       );
                        $add = true;
					}
				}
        	}
    	}
	}
    if ($add == true) {
        sleep(2);
        header('Location: https://estetica-pro.ru/personal/cart/');
    }
}?>