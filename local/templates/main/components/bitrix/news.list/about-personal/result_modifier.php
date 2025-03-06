<?
foreach($arResult["ITEMS"] as $arItem){
    $res = CIBlockSection::GetByID($arItem['IBLOCK_SECTION_ID']);
    if($ar_res = $res->GetNext()){}
    if($arItem['IBLOCK_SECTION_ID'] == $arItem['IBLOCK_SECTION_ID']){
        $arResult['SECTION_ITEMS'][$arItem['IBLOCK_SECTION_ID']]['ITEMS'][] = $arItem;
        $arResult["SECTION_ITEMS"][$arItem['IBLOCK_SECTION_ID']]["NAME"] = $ar_res['NAME'];
    }
}
?>