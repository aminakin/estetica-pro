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

if ($arResult['ITEM']['NO_DANATE'] == false) {
	$arResult['ITEM']['NO_DANATE'] = $arResult['ITEM']['PROPERTIES']['RETAIL_PRODUCT']['VALUE'] == 'Y' ? true : false;
}

if (strpos($APPLICATION->GetCurDir(), 'new-catalog') || strpos($APPLICATION->GetCurDir(), 'search')) {
	$element = \Bitrix\Iblock\Elements\ElementCatalogTable::getList([
		'select' => ['ID', 'BEZNACHISLENIYABONUSA_' => 'BEZNACHISLENIYABONUSA', 'NEW_' => 'NEW', 'KHIT_PRODAZH_' => 'KHIT_PRODAZH'],
		'filter' => [
			'ID' => $arResult['ITEM']['ID'],
		],
		"cache" => ["ttl" => 3600],
	]);

	while ($arElement = $element->fetch()) {
		$arResult['ITEM']['BEZNACHISLENIYABONUSA_VALUE'] = $arElement['BEZNACHISLENIYABONUSA_VALUE'];
		$arResult['ITEM']['NEW_VALUE'] = $arElement['NEW_VALUE'];
		$arResult['ITEM']['KHIT_PRODAZH_VALUE'] = $arElement['KHIT_PRODAZH_VALUE'];
	}

	if ($arResult['ITEM']['BEZNACHISLENIYABONUSA'] == 696) {
		$arResult['ITEM']['PROPERTIES']['BEZNACHISLENIYABONUSA']['VALUE'] = 'Да';
	}

	if ($arResult['ITEM']['NEW_VALUE'] == 361) {
		$arResult['ITEM']['LABEL_ARRAY_VALUE']['NEW'] = 'Да';	
	}

	if ($arResult['ITEM']['KHIT_PRODAZH_VALUE'] == 771) {
		$arResult['ITEM']['LABEL_ARRAY_VALUE']['KHIT_PRODAZH'] = 'Да';	
	}
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

