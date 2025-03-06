<?php
if (CModule::IncludeModule('iblock') && CModule::IncludeModule('catalog')) {
    foreach ($arResult['SEARCH'] as $id => $item) {
        if ($item['PARAM1'] == 'catalog' && $item['PARAM2'] == 3) {
            $arPrice = CCatalogProduct::GetOptimalPrice($item['ITEM_ID'], 1, $USER->GetUserGroupArray(), 'N');
            if ($arPrice['DISCOUNT_PRICE']) $arResult['SEARCH'][$id]['TITLE_FORMATED'] .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.format_price($arPrice['DISCOUNT_PRICE']).'&nbsp;<span class="rub">Р</span>';
        }
    }
}