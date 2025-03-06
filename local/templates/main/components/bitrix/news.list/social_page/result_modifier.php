<?php
$arrNoviy = [];
foreach ($arResult['ITEMS'] as $id=>$item) {?>
    <? $rsParentSection = CIBlockSection::GetByID($item['IBLOCK_SECTION_ID']);
    if ($arParentSection = $rsParentSection->GetNext()){
        $arResult['ITEMS'][$id]['NAME_SECTION'] = $arParentSection['NAME'];
        $arrNoviy[$arParentSection['NAME']][] = $item;
    }
    ?>
<?}
$arResult['NEW_ITEMS'] = $arrNoviy;?>