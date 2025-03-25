<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
\Bitrix\Main\Loader::includeModule('iblock');

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */
global $addCurCurrency;
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();

do {
    $old_price = $arResult['PROPERTIES']['STARAYA_TSENA_1']['VALUE'];
    $old_price = str_replace(',', '.', $old_price);
    $old_price = preg_replace('/[^\d.]*/i', '', $old_price);

    //вся цепочка разделов товара
    $arResult['TRUE_NAV_CHAIN'] =[];
    $sections = CIBlockElement::GetElementGroups($arResult['ID']);
    while($ar_group = $sections->Fetch()){
        //debug($ar_group);
        $nav = CIBlockSection::GetNavChain(false, $ar_group["ID"]);
        while($arSectionPath = $nav->GetNext()){


            $db_list = CIBlockSection::GetList(Array('SORT' => "ASC"), ["IBLOCK_ID" => $arResult["IBLOCK_ID"], "ID" => $arSectionPath["ID"]], true, ["UF_NO_DANATE"]);
            if($ar_result = $db_list->GetNext()) {
                $arResult['UF_NO_DANATE'][$arSectionPath['ID']] = is_null($ar_result['UF_NO_DANATE']) || !$ar_result['UF_NO_DANATE'] ? 'N' : 'Y';
            }

            $arResult['TRUE_NAV_CHAIN'][]  = $arSectionPath["ID"];
        }
        //$arResult['TRUE_NAV_CHAIN'][]  = $ar_group["ID"];
    }

    $arResult['NO_DANATE'] = false;
    if (in_array('Y', $arResult['UF_NO_DANATE'])) {
        $arResult['NO_DANATE'] = true;
    }

    if($arResult['NO_DANATE'] == false){
        $arResult['NO_DANATE'] = $arResult['PROPERTIES']['RETAIL_PRODUCT']['VALUE'] == 'Y' ? true : false;
    }
    
    
    /*----*/
    
    if (isset($arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']])
        && $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICE'] < $old_price
    ) {

        if (empty($old_price)) {
            continue;
        }
        if ($arResult['PROPERTIES']['AKTSIONNYY']['VALUE'] != "Да" && $arResult['PROPERTIES']['SALE_RESNITSY']['VALUE'] != "Да") {
            continue;
        }

        $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']
            = $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICE'] = $old_price;

        $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRINT_RATIO_BASE_PRICE']
            = $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRINT_PRICE'] = SaleFormatCurrency(
            $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICE'],
            $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['CURRENCY']
        );

        $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PERCENT'] = round(100 -
            $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['RATIO_PRICE']
            / $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICE'] * 100, 0);
        
    } else {
        $price = [];
        $action = [];
        foreach ($arResult['OFFERS'] as &$offer) {
            $old_price = $offer['PROPERTIES']['STARAYA_TSENA_1']['VALUE'];
            $old_price = str_replace(',', '.', $old_price);
            $old_price = preg_replace('/[^\d.]*/i', '', $old_price);
            $price[$offer['ID']] = $old_price;
            if($offer['PROPERTIES']['AKTSIONNYY']['VALUE'] == "Да"){
                $action[$offer['ID']] = $offer['PROPERTIES']['AKTSIONNYY']['VALUE'];
            }
            if($offer['PROPERTIES']['SALE_RESNITSY']['VALUE'] == "Да"){
                $action[$offer['ID']] = $offer['PROPERTIES']['SALE_RESNITSY']['VALUE'];
            }
            
            if (empty($old_price)) {
                continue;
            }
            if ($offer['PROPERTIES']['AKTSIONNYY']['VALUE'] != "Да" && $offer['PROPERTIES']['SALE_RESNITSY']['VALUE'] != "Да") {
                continue;
            }

            if ($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] >= $old_price)
                continue;
            if ($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE'] != $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_PRICE']) {
                continue;
            }

            $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']
                = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] = $old_price;

            $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRINT_RATIO_BASE_PRICE']
                = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRINT_PRICE'] = SaleFormatCurrency(
                $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'],
                $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['CURRENCY']
            );

            $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PERCENT'] = round(100 -
                $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_PRICE']
                / $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] * 100, 0);
        }
        foreach ($arResult['JS_OFFERS'] as &$offer) {

            $old_price = $price[$offer['ID']];

            if (empty($old_price)) {
                continue;
            }
            if ($action[$offer['ID']] != "Да") {
                continue;
            }

            if ($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] >= $old_price)
                continue;
            if ($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE'] != $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_PRICE']) {
                continue;
            }
            $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']
                = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] = $old_price;

            $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRINT_RATIO_BASE_PRICE']
                = $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRINT_PRICE'] = SaleFormatCurrency(
                $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'],
                $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['CURRENCY']
            );

            $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PERCENT'] = round(100 -
                $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_PRICE']
                / $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] * 100, 0);
        }
    }

    // получение бонусов
    if($arResult['PROPERTIES']['BEZNACHISLENIYABONUSA']['VALUE'] !== 'Да'){
        if($arResult['JS_OFFERS'] == NULL){
            $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['ITEM_BONUS'] = infoBonus($arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']);
        }else{
            foreach ($arResult['JS_OFFERS'] as &$offer) {
                $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['ITEM_BONUS'] = infoBonus($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']);
            }
        }
    } 

    if(!empty($arResult['PROPERTIES']['FOOTNOTES']["VALUE"])){
        foreach($arResult['PROPERTIES']['FOOTNOTES']["VALUE"] as $footnote){
            $element = \Bitrix\Iblock\Elements\ElementFootnotesTable::getList([
                'select' => ['DETAIL_TEXT'],
                'filter' => ['ACTIVE' => 'Y', 'ID' => $footnote]
            ])->fetch();
            
            $arResult['FOOTNOTES'][] = $element['DETAIL_TEXT'];
        }
    }
    
} while (false);