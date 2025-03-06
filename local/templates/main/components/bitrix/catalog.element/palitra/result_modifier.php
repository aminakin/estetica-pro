<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var CBitrixComponentTemplate $this
 * @var CatalogElementComponent $component
 */
global $addCurCurrency;
$component = $this->getComponent();
$arParams = $component->applyTemplateModifications();
$old_price = $arResult['PROPERTIES']['STARAYA_TSENA_1']['VALUE'];
$old_price = str_replace(',', '.', $old_price);
$old_price = preg_replace('/[^\d.]*/i', '', $old_price);
//$old_price = CCurrencyRates::ConvertCurrency($old_price, CSaleLang::GetLangCurrency(SITE_ID), $addCurCurrency);

do {
    if (empty($old_price)) {
        continue;
    }
    if ($arResult['PROPERTIES']['AKTSIONNYY']['VALUE'] != "Да") {
        continue;
    }

    if (isset($arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']])
        && $arResult['ITEM_PRICES'][$arResult['ITEM_PRICE_SELECTED']]['PRICE'] < $old_price
    ) {

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
        foreach ($arResult['OFFERS'] as &$offer) {
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
} while (false);