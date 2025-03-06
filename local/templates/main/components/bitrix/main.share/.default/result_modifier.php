<?php
if (CModule::IncludeModule('iblock'))
{
    $res = CIBlockElement::GetList([], ['IBLOCK_CODE'=>'soc'], false, false);
    $soc = [];
    while($el = $res->GetNext())
    {
        $soc[$el['CODE']]=$el['PREVIEW_TEXT'];
    }
    $arResult['SOC'] = $soc;
}