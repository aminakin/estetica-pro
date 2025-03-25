<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

// debug($arResult['ITEM']);
// debug($arResult['UF_NO_DANATE']);

$arResult['ITEM']['NO_DANATE'] = false;
$db_list = CIBlockSection::GetList(array('SORT' => "ASC"), ["IBLOCK_ID" => $arResult['ITEM']["IBLOCK_ID"], "ID" => $arResult['ITEM']['IBLOCK_SECTION_ID']], true, ["UF_NO_DANATE"]);
if ($ar_result = $db_list->GetNext()) {
	$arResult['ITEM']['NO_DANATE'] = is_null($ar_result['UF_NO_DANATE']) || !$ar_result['UF_NO_DANATE'] ? false : true;
}

if($arResult['ITEM']['NO_DANATE'] == false){
	$arResult['ITEM']['NO_DANATE'] = $arResult['ITEM']['PROPERTIES']['RETAIL_PRODUCT']['VALUE'] == 'Y' ? true : false;
}

if ($arResult['ITEM']['PROPERTIES']['BEZNACHISLENIYABONUSA']['VALUE'] !== 'Да') {
	if ($arResult['ITEM']['JS_OFFERS'] == NULL) {
		$arResult['ITEM']['ITEM_PRICES'][$arResult['ITEM']['ITEM_PRICE_SELECTED']]['ITEM_BONUS'] = infoBonus($arResult['ITEM']['ITEM_PRICES'][$arResult['ITEM']['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']);
	} else {
		foreach ($arResult['ITEM']['JS_OFFERS'] as &$offer) {
			$offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['ITEM_BONUS'] = infoBonus($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']);
		}
	}
}
