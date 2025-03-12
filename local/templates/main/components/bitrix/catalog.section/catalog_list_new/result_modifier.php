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
foreach($arResult["ITEMS"] as $key => $arItem){
    if ($sectionsNamesMap[$arItem["IBLOCK_SECTION_ID"]]) {
        $arResult["ITEMS"][$key]['SECTION_NAME'] = $sectionsNamesMap[$arItem["IBLOCK_SECTION_ID"]];
    }
}


