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
/** @var CBitrixBasketComponent $component */

use Bitrix\Main\Loader;

Loader::includeModule('catalog');
Loader::includeModule('iblock');
global $hrefDoc;

$curPage = $hrefDoc . '?' . $arParams["ACTION_VARIABLE"] . '=';
$arUrls = array(
	"delete" => $curPage . "delete&id=#ID#",
	"delay" => $curPage . "delay&id=#ID#",
	"add" => $curPage . "add&id=#ID#",
);
unset($curPage);
$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]); ?>

<form class="basket" name="basket_form" id="basket_form_small" method="POST" action="#">
	<? if (count($arResult["GRID"]["ROWS"]) > 0): ?>
		<a href='?clear' class="clear_basket"> <?= GetMessage('SALE_DELETE'); ?></a>
		<div class="basket-head">
			<div class="basket-head__col">
				<?= GetMessage('SALE_YOUR_CART'); ?>
				<span>
					<? $couter = count($arResult["GRID"]["ROWS"]);
					if ($couter > 0) {
						echo $couter;
					} ?>
				</span>
				<span><?= GetMessage('SALE_MEASURE'); ?></span>
			</div>
			<div class="basket-head__col">
				<?= GetMessage('SALE_QUANTITY'); ?>
			</div>
			<div class="basket-head__col">
				<?= GetMessage('SALE_PRICE'); ?>
			</div>
		</div>
	<? endif; ?>

	<div class="basket-items">
		<?

		$kol = 0;
		foreach ($arResult["GRID"]["ROWS"] as $k => $arItem) { ?>
			<? if ($arItem["DELAY"] == "N" && $arItem["CAN_BUY"] == "Y"): ?>
				<? $kol = $kol + 1; ?>

				<div class="basket-items__item basket-item" id="<?= $arItem['ID'] ?>" data-item-name="<?= $arItem['NAME'] ?>" data-item-brand="" data-item-price="<?= $arItem['PRICE'] ?>" data-item-currency="RUB">
					<a href="#" class="basket-item__delete delete_fav" data-id="<?= $arItem['ID'] ?>" data-type="bask" data-product="<?= $arItem['PRODUCT_ID'] ?>">
						<span class="basket-item__delete-text"><?= GetMessage('SALE_DELETE'); ?></span>
						<span class="basket-item__delete-img"><?= classBuildSCG::buildSVG('cart', $templateFolder . "/images"); ?></span>
					</a>
					<div class="basket-item__row">
						<div class="basket-item__col">
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="basket-item__image" style="background-image: url(<?= CFile::GetPath($arItem["DETAIL_PICTURE"]) ?>)"></a>
							<a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="basket-item__name"><?= $arItem['NAME'] ?></a>
						</div>
						<div class="basket-item__col">
							<? $ar_res  = CCatalogProduct::GetByID($arItem['PRODUCT_ID']); ?>
							<input type="number" class="cart-element" autocomplete="off" max="<?= $ar_res['QUANTITY']; ?>" min="1" value="<?= $arItem['QUANTITY'] ?>"
								id="<?= $arItem['ID'] ?>" name="QUANTITY_INPUT_<?= $arItem["ID"] ?>" />
						</div>
						<div class="basket-item__col">
							<div class="basket-item__price">
								<span>
									<?= CurrencyFormat($arItem['PRICE'], "RUB"); ?>
								</span>
							</div>
						</div>
						<div style="display: none;" class="price"> <span style="font-family: 'Myriad Pro', sans-serif;vertical-align: sub; margin: 0 3px "> * </span> <span class="price-value"><?= format_price($arItem['PRICE']) ?></span> Р</div>
					</div>
					<div class="clear"></div>
				</div>
			<? endif; ?>
		<? } ?>
	</div>
</form>
<? if ($kol > 0) { ?>
	<div class="bottom">
		<div class="inner">
			<div class="total">
				<div class="total-text">
					<?= GetMessage('SALE_ORDER_PRICES'); ?>
				</div>
				<div class="price" id="allSum_FORMATED">
					<?= CurrencyFormat($arResult["allSum"], 'RUB'); ?>
				</div>
			</div>
			<div class="bottom-buttons">
				<? if ($normalCount): ?>
					<?
					$buttons_style = '';
					$minsum_style = 'display:none';
					if ($arResult["allSum"] < 2000 && false) {
						$buttons_style = 'display:none';
						$minsum_style = '';
					}
					?>
					<a style="<?= $buttons_style ?>" href="/cart/" class="button button_transparent"><?= GetMessage('SALE_TO_CART'); ?></a>
					<?/*a style="<?=$buttons_style?>" href="/cart/order/" class="button button_filled"><?= GetMessage('SALE_ORDER');?></?a*/ ?>
					<div style="<?= $minsum_style ?>" class="minsum">
						<span class="minsum__text">
							<?= GetMessage('SALE_MIN_ORDER'); ?>
						</span>
						<span class="minsum__text">
							<?= GetMessage('SALE_YOU_NEED'); ?> <span class="minsum__value"><?= (2000 - $arResult["allSum"]) ?></span> ₽
						</span>
					</div>

				<? endif; ?>
			</div>
		</div>
	</div>

<? } else { ?>
	<div class="empty_basket">
		<?= classBuildSCG::buildSVG('empty-cart', $templateFolder . "/images"); ?>
		<p><?= GetMessage('SBB_EMPTY_BASKET_TITLE'); ?></p>
		<a href="/"><?= GetMessage('SBB_EMPTY_BASKET_CONTINE'); ?></a>
		<?/*<div class="our-new">
                <?= GetMessage('SBB_BASKET_OUR_NEW');?>
            </div>
			
            <?$APPLICATION->IncludeComponent(
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
					"CACHE_TYPE" => "A",
					"COMPATIBLE_MODE" => "Y",
					"COMPONENT_TEMPLATE" => "addamant",
					"CONVERT_CURRENCY" => "N",
					"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":{\"1\":{\"CLASS_ID\":\"CondIBProp:14:133\",\"DATA\":{\"logic\":\"Equal\",\"value\":361}}}}",
					"DETAIL_URL" => "",
					"DISABLE_INIT_JS_IN_COMPONENT" => "N",
					"DISCOUNT_PERCENT_POSITION" => "bottom-right",
					"DISPLAY_BOTTOM_PAGER" => "N",
					"DISPLAY_COMPARE" => "N",
					"DISPLAY_TOP_PAGER" => "N",
					"ELEMENT_SORT_FIELD" => "name",
					"ELEMENT_SORT_FIELD2" => "shows",
					"ELEMENT_SORT_ORDER" => "asc",
					"ELEMENT_SORT_ORDER2" => "asc",
					"ENLARGE_PRODUCT" => "STRICT",
					"FILTER_NAME" => "",
					"HIDE_NOT_AVAILABLE" => "Y",
					"HIDE_NOT_AVAILABLE_OFFERS" => "Y",
					"IBLOCK_ID" => IblockHelper::getIdByCode('catalog'),
					"IBLOCK_TYPE" => "catalog",
					"INCLUDE_SUBSECTIONS" => "Y",
					"LABEL_PROP" => array(
						0 => "NEW",
					),
					"LABEL_PROP_MOBILE" => array(
						0 => "NEW",
					),
					"LABEL_PROP_POSITION" => "top-left",
					"LAZY_LOAD" => "N",
					"LINE_ELEMENT_COUNT" => "1",
					"LOAD_ON_SCROLL" => "N",
					"MESSAGE_404" => "",
					"MESS_BTN_ADD_TO_BASKET" => "Добавить в корзину",
					"MESS_BTN_BUY" => "Купить",
					"MESS_BTN_DETAIL" => "Подробнее",
					"MESS_BTN_LAZY_LOAD" => "Показать ещё",
					"MESS_BTN_SUBSCRIBE" => "Подписаться",
					"MESS_NOT_AVAILABLE" => "Нет в наличии",
					"META_DESCRIPTION" => "-",
					"META_KEYWORDS" => "-",
					"OFFERS_CART_PROPERTIES" => array(
					),
					"OFFERS_FIELD_CODE" => array(
						0 => "",
						1 => "",
					),
					"OFFERS_LIMIT" => "0",
					"OFFERS_PROPERTY_CODE" => array(
						0 => "IZGIB",
						1 => "TOLSHCHINA",
						2 => "DLINA",
						3 => "",
					),
					"OFFERS_SORT_FIELD" => "created",
					"OFFERS_SORT_FIELD2" => "shows",
					"OFFERS_SORT_ORDER" => "asc",
					"OFFERS_SORT_ORDER2" => "asc",
					"OFFER_ADD_PICT_PROP" => "-",
					"OFFER_TREE_PROPS" => array(
						0 => "IZGIB",
						1 => "TOLSHCHINA",
						2 => "DLINA",
					),
					"PAGER_BASE_LINK_ENABLE" => "N",
					"PAGER_DESC_NUMBERING" => "N",
					"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
					"PAGER_SHOW_ALL" => "N",
					"PAGER_SHOW_ALWAYS" => "N",
					"PAGER_TEMPLATE" => "",
					"PAGER_TITLE" => "Товары",
					"PAGE_ELEMENT_COUNT" => "4",
					"PARTIAL_PRODUCT_PROPERTIES" => "N",
					"PRICE_CODE" => array(
						0 => "Типовое соглашение с клиентами",
					),
					"PRICE_VAT_INCLUDE" => "Y",
					"PRODUCT_BLOCKS_ORDER" => "price,quantityLimit,sku,quantity,buttons,compare,props",
					"PRODUCT_DISPLAY_MODE" => "Y",
					"PRODUCT_ID_VARIABLE" => "id",
					"PRODUCT_PROPERTIES" => array(
					),
					"PRODUCT_PROPS_VARIABLE" => "prop",
					"PRODUCT_QUANTITY_VARIABLE" => "quantity",
					"PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'3','BIG_DATA':false}]",
					"PRODUCT_SUBSCRIPTION" => "Y",
					"PROPERTY_CODE" => array(
						0 => "AKTSIONNYY",
						1 => "",
					),
					"PROPERTY_CODE_MOBILE" => array(
					),
					"RCM_PROD_ID" => "",
					"RCM_TYPE" => "personal",
					"SECTION_CODE" => "",
					"SECTION_ID" => "",
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
					"SET_META_KEYWORDS" => "Y",
					"SET_STATUS_404" => "N",
					"SET_TITLE" => "N",
					"SHOW_404" => "N",
					"SHOW_ALL_WO_SECTION" => "Y",
					"SHOW_CLOSE_POPUP" => "N",
					"SHOW_DISCOUNT_PERCENT" => "Y",
					"SHOW_FROM_SECTION" => "N",
					"SHOW_MAX_QUANTITY" => "N",
					"SHOW_OLD_PRICE" => "Y",
					"SHOW_PRICE_COUNT" => "1",
					"SHOW_SLIDER" => "Y",
					"SLIDER_INTERVAL" => "3000",
					"SLIDER_PROGRESS" => "N",
					"TEMPLATE_THEME" => "site",
					"USE_ENHANCED_ECOMMERCE" => "N",
					"USE_MAIN_ELEMENT_SECTION" => "N",
					"USE_PRICE_COUNT" => "N",
					"USE_PRODUCT_QUANTITY" => "Y"
				),
				false
			);*/ ?>
	</div>
	</div>
<? } ?>