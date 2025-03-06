<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

// debug($arResult['ITEM']);
// debug($arResult['UF_NO_DANATE']);

$arResult['ITEM']['NO_DANATE'] = false;
$db_list = CIBlockSection::GetList(Array('SORT' => "ASC"), ["IBLOCK_ID" => $arResult['ITEM']["IBLOCK_ID"], "ID" => $arResult['ITEM']['IBLOCK_SECTION_ID']], true, ["UF_NO_DANATE"]);
if($ar_result = $db_list->GetNext()) {
	$arResult['ITEM']['NO_DANATE'] = is_null($ar_result['UF_NO_DANATE']) || !$ar_result['UF_NO_DANATE'] ? false : true;
}

