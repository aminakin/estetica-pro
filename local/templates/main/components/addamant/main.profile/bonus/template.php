<?
/**
 * @param array $arParams
 * @param array $arResult
 * @global CMain $APPLICATION
 */

use Bitrix\Sale;
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
$APPLICATION->SetTitle("Личный кабинет");
Loc::loadMessages(__FILE__);

global $USER;
/*
$arResult['arUser']['NAME'];-------------------- Имя
$arResult['arUser']['LAST_NAME'];--------------- Фамилия
$arResult['arUser']['SECOND_NAME'];------------- Отчество
$arResult['arUser']['UF_BONUS_CART'];----------- Номер бонусной карты
$arResult['arUser']['UF_CURPERCENT'];----------- Текущий процент накоплений
$arResult['arUser']['UF_PURCHVOLUME'];---------- Текущая сумма покупок
$arResult['arUser']['UF_NEXTLEVEL'];------------ Сумма покупок до повышения процента
$arResult['arUser']['UF_QUANTITY_DATE'];-------- Количество / дата списания:
$arResult['arUser']['UF_LOGICTIM_BONUS'];------- Количество бонусных баллов
*/
?>
<div class="main-profile__bonus bonus">
    <? $APPLICATION->IncludeComponent(
	"bitrix:news.list", 
	"bonuses", 
	array(
		"ACTIVE_DATE_FORMAT" => "",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"DETAIL_URL" => "",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "Y",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "39",
		"IBLOCK_TYPE" => "data",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "20",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array(
			0 => "FIRST_ICON",
			1 => "FIRST_TITLE",
			2 => "FIRST_DESC",
			3 => "FIRST_LINK_NAME",
			4 => "FIRST_LINK",
			5 => "SECOND_ICON",
			6 => "SECOND_TITLE",
			7 => "SECOND_DESC",
			8 => "SECOND_LINK_NAME",
			9 => "SECOND_LINK",
			10 => "THIRD_ICON",
			11 => "THIRD_TITLE",
			12 => "THIRD_DESC",
			13 => "THIRD_LINK_NAME",
			14 => "THIRD_LINK",
			15 => "PERCENT",
			16 => "",
		),
		"SET_BROWSER_TITLE" => "Y",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "ACTIVE_FROM",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "DESC",
		"SORT_ORDER2" => "ASC",
		"STRICT_SECTION_CHECK" => "N",
		"COMPONENT_TEMPLATE" => "bonuses",
		"USER_BONUS" => $arResult["arUser"]["UF_LOGICTIM_BONUS"],
		"USER_SUM" => $arResult["arUser"]["UF_PURCHVOLUME"],
		"USER_PERCENT" => $arResult["arUser"]["UF_CURPERCENT"],
		"USER_LEFT_SUM" => $arResult["arUser"]["UF_NEXTLEVEL"]
	),
	false
); ?>
</div>
