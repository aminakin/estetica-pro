<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Избранное");

global $USER, $APPLICATION, $arrFilter;

use Bitrix\Main\Application;

if (!$USER->IsAuthorized()) // Для неавторизованного
{
	$countNumber = 0;
} else {
	$idUser = $USER->GetID();
	$rsUser = CUser::GetByID($idUser);
	$arUser = $rsUser->Fetch();
	$favorites = $arUser['UF_FAVORITES'];
	$countNumber = count($favorites);
}
$APPLICATION->AddViewContent('counter', $countNumber);
?>
<?
if ($USER->IsAuthorized()) {
?>
	<? $arrFilter['ID'] = $favorites; ?>
	<?
	if (count($favorites) > 0 && $favorites && is_array($favorites)) {
		?>
		<div class="row-margin row-margin-mobile favorites-section page_section_wrapper">

		<?
		if (!empty($favorites && is_array($favorites))) { ?>
			<div class="personal-favorites-header-menu-del">Удалить все</div>
		<? } ?>
			<div class="row">
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
						"ELEMENT_SORT_FIELD" => $arParams["SORT_BY1"],
						"ELEMENT_SORT_FIELD2" => $arParams["SORT_BY2"],
						"ELEMENT_SORT_ORDER" => $arParams["SORT_ORDER1"],
						"ELEMENT_SORT_ORDER2" => $arParams["SORT_ORDER2"],
						"ENLARGE_PRODUCT" => "STRICT",
						"FILTER_NAME" => "arrFilter",
						"HIDE_NOT_AVAILABLE" => "N",
						"HIDE_NOT_AVAILABLE_OFFERS" => "L",
						"IBLOCK_ID" => IblockHelper::getIdByCode('catalog'),
						"IBLOCK_TYPE" => "catalog",
						"INCLUDE_SUBSECTIONS" => "A",
						"LAZY_LOAD" => "Y",
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
						"PAGE_ELEMENT_COUNT" => "16",
						"PARTIAL_PRODUCT_PROPERTIES" => "Y",
						"PRICE_CODE" => array(
							0 => "Типовое соглашение с клиентами",
						),
						"PRICE_VAT_INCLUDE" => "Y",
						"PRODUCT_ID_VARIABLE" => "id",
						"PRODUCT_PROPERTIES" => array(),
						"PRODUCT_PROPS_VARIABLE" => "prop",
						"PRODUCT_QUANTITY_VARIABLE" => "quantity",
						"PRODUCT_SUBSCRIPTION" => "N",
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
						"CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
						"OFFERS_SORT_FIELD" => "sort",
						"OFFERS_SORT_ORDER" => "asc",
						"OFFERS_SORT_FIELD2" => "id",
						"OFFERS_SORT_ORDER2" => "desc",
						"LIST_PROPERTY_CODE" => array(
							0 => "",
							1 => "ICON",
							2 => "",
						),
						"PROPERTY_CODE_MOBILE" => array(),
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
						"LIST_PRODUCT_BLOCKS_ORDER" => "price,quantity,quantityLimit,buttons,compare,props,sku",
						"SLIDER_INTERVAL" => "3000",
						"SLIDER_PROGRESS" => "N",
						"PRODUCT_DISPLAY_MODE" => "Y",
						"ADD_PICT_PROP" => "MORE_PHOTO",
						"LABEL_PROP" => array(
							0 => "NEW",
							1 => "AKTSIONNYY",
						),
						"LABEL_PROP_MOBILE" => array(
							0 => "NEW",
							1 => "AKTSIONNYY",
						),
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
						"PRODUCT_BLOCKS_ORDER" => "price,quantity,props,buttons,sku,quantityLimit,compare",
						"DISCOUNT_PERCENT_POSITION" => "bottom-right",
						"OFFERS_FIELD_CODE" => array(
							0 => "",
							1 => "",
						),
						"MESS_NOT_AVAILABLE_SERVICE" => "Недоступно",
						"MESS_BTN_LAZY_LOAD" => "Показать ещё"
					),
					false
				); ?>
			</div>
		</div>
	<?
	} else { ?>
		<div class="container">
			<div class="personal-favorite-empty">
				<div class="personal-favorite-empty-product"></div>
				<div class="personal-favorite-empty-message">Ваш список товаров пуст</div>
				<div class="personal-favorite-empty-desc">Тут будут отображаться товары, добавленные в избранное.</div>
				<a href="#auth" class="enterLink linkOpen personal-favorite-empty-link">войти в личный кабинет</a>
			</div>
		</div>
	<? } ?>
<? } else { ?>
	<div class="container">
		<div class="personal-favorite-empty">
			<div class="personal-favorite-empty-product"></div>
			<div class="personal-favorite-empty-message">Для отображения списка необходимо войти или зарегистрироваться.</div>
			<div class="personal-favorite-empty-desc">Тут будут отображаться товары, добавленные в избранное.</div>
			<a href="#auth" class="enterLink linkOpen personal-favorite-empty-link">войти в личный кабинет</a>
		</div>
	</div>
<? } ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>