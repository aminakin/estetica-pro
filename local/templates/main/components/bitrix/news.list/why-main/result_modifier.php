<?
foreach($arResult["ITEMS"] as $arItem){
    $res = CIBlockSection::GetByID($arItem['IBLOCK_SECTION_ID']);
    if($ar_res = $res->GetNext())
         $pictureSection = $ar_res['PICTURE'];
}
$arResult['PICTURE_SECTION_URL'] = CFile::GetPath($pictureSection);
?>