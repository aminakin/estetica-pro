<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogSectionComponent $component
 */

$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
global $addCurCurrency;
CModule::IncludeModule("sale");
foreach ($arResult['ITEMS'] as &$item) {
		$arSelect = Array('PROPERTY_MORE_PHOTO');
        $arFilter = Array("ID"=>$item['ID']);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
        while($ob = $res->GetNext()){
        $file_id = $ob['PROPERTY_MORE_PHOTO_VALUE'];
            $item['MORE_PHOTO'][0]['SRC'] = CFile::GetPath($file_id);
        }
        //var_export($item['MORE_PHOTO'][0]['SRC']);
        /*if($item['MORE_PHOTO'][0]['SRC'] != 'NULL'){
            
            $item['PREVIEW_PICTURE']['SRC'] = CFile::GetPath($file_id);
            var_export($item['PREVIEW_PICTURE']);
        }*/

    if (isset($item['OFFERS']) && count($item['OFFERS'])) {

        foreach ($item['OFFERS'] as &$offer) {
            $old_price = $offer['PROPERTIES']['STARAYA_TSENA_1']['VALUE'];
            $old_price = str_replace(',', '.', $old_price);
            $old_price = preg_replace('/[^\d.]*/i', '', $old_price);
            $price[$offer['ID']] = $old_price;
            $action[$offer['ID']] = $offer['PROPERTIES']['AKTSIONNYY']['VALUE'];

            if ($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] >= $old_price)
                continue;

            if (empty($old_price)) {
                continue;
            }

            if ($offer['PROPERTIES']['AKTSIONNYY']['VALUE'] != "Да") {
                continue;
            }

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
                / $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] * 100);
            //var_export($offer['ITEM_PRICES']);
        }
        foreach ($item['JS_OFFERS'] as &$offer) {

            $old_price = $price[$offer['ID']];

            if (empty($old_price)) {
                continue;
            }

            if ($action[$offer['ID']] != "Да") {
                continue;
            }

            if ($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] >= $old_price)
                continue;

            if ($offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] != $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']) {
                continue;
            }
            if (empty($old_price)) {
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
                / $offer['ITEM_PRICES'][$offer['ITEM_PRICE_SELECTED']]['PRICE'] * 100);
        }
    } else {

        $old_price = $item['PROPERTIES']['STARAYA_TSENA_1']['VALUE'];
        $old_price = str_replace(',', '.', $old_price);
        $old_price = preg_replace('/[^\d.]*/i', '', $old_price);

        if ($item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICE'] < $old_price) {

            $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['RATIO_BASE_PRICE']
                = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICE'] = $old_price;

            $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRINT_RATIO_BASE_PRICE']
                = $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRINT_PRICE'] = SaleFormatCurrency(
                $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICE'],
                $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['CURRENCY']
            );

            $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PERCENT'] = round(100 -
                $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['RATIO_PRICE']
                / $item['ITEM_PRICES'][$item['ITEM_PRICE_SELECTED']]['PRICE'] * 100);
        }
    }

}
//var_export($arResult['ITEM_ROWS']);