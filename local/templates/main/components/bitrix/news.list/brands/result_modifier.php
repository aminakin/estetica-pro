<?php
if (CModule::IncludeModule('iblock'));
{
    foreach ($arResult['ITEMS'] as $id => $item)
    {
        $cat = CIBlockElement::GetList([],['IBLOCK_ID'=>3, 'ACTIVE'=>'Y', 'PROPERTY_BRAND'=>$item['ID']], false, ['nTopCount'=>1]);
        if ($cat->SelectedRowsCount()<1){
            unset($arResult['ITEMS'][$id]);
        }
    }
}