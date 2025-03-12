<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

$sectionsNamesMap = [];
$sections = CIBlockSection::GetList(
    ['id' => 'asc'],
    ['IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'],
    false,
    ['ID', 'NAME']
);
while ($section = $sections->Fetch()) {
    $sectionsNamesMap[$section['ID']] = $section['NAME'];
}
$icons = [];
foreach($arResult["ITEMS"] as $key => $arItem){
    if (!$icons[$arItem['PROPERTIES']['ICON']['VALUE']])
    {
        $icon_db = CIBlockElement::GetByID($arItem['PROPERTIES']['ICON']['VALUE']);
        if ($ic = $icon_db->GetNextElement())
        {
            $f = $ic->GetFields();
            $p = $ic->GetProperties();
            $icons[$f['ID']] = ['NAME'=>$f['NAME'], 'COLOR'=>$p['COLOR']['VALUE']];
        }
    }
    if ($arItem["PREVIEW_PICTURE"])
    {
        $arResult["ITEMS"][$key]['RESIZED_IMAGE_SMALL'] = CFile::ResizeImageGet(
            $arItem['PREVIEW_PICTURE']['ID'],
            array('width' => 220, 'height' => 205),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
            false
        );
    }
    if ($arItem["DETAIL_PICTURE"])
    {
        $arResult["ITEMS"][$key]['RESIZED_IMAGE'] = CFile::ResizeImageGet(
            $arItem['DETAIL_PICTURE'],
            array('width' => 220, 'height' => 205),
            BX_RESIZE_IMAGE_PROPORTIONAL_ALT,
            false
        );

    }
    if ($sectionsNamesMap[$arItem["IBLOCK_SECTION_ID"]]) {
        $arResult["ITEMS"][$key]['SECTION_NAME'] = $sectionsNamesMap[$arItem["IBLOCK_SECTION_ID"]];
    }
}
$arResult['ICONS'] = $icons;
/*echo '<pre>';
print_r($arResult['ITEMS']);
echo '</pre>';*/