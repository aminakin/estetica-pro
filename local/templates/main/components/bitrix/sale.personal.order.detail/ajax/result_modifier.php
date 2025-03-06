<?php
/**
 * @param array $arParams
 * @param array $arResult
 * @global CMain $APPLICATION
 */

use Bitrix\Main\Localization\Loc;

$date = strtotime($arResult['DATE_INSERT_FORMATED']);
$day = date("d", $date);
$month = date("m", $date);

$arResult['ORDER_DAY_CREATED'] = $day;
$arResult['ORDER_MONTH_CREATED'] = $month;

foreach ($arResult['ORDER_PROPS'] as $prop) {
    $arResult['USER_ORDER_PROPS'][$prop['CODE']] = $prop['VALUE'];
}
$address[] = $arResult['USER_ORDER_PROPS']['INDEX'];
$address[] = $arResult['USER_ORDER_PROPS']['CITY'];

if ($arResult['USER_ORDER_PROPS']['STREET'] != '-')
    $address[] = Loc::getMessage('ABBREVIATION_STREET') . ' ' . $arResult['USER_ORDER_PROPS']['STREET'];

if ($arResult['USER_ORDER_PROPS']['HOUSE'] != '-')
    $address[] = Loc::getMessage('ABBREVIATION_HOUSE') . ' ' . $arResult['USER_ORDER_PROPS']['HOUSE'];

if ($arResult['USER_ORDER_PROPS']['APARTMENT'] != '-')
    $address[] = Loc::getMessage('ABBREVIATION_APARTMENT') . ' ' . $arResult['USER_ORDER_PROPS']['APARTMENT'];

$arResult['ORDER_ADDRESS'] = implode(', ', $address);

$payment = [];
foreach ($arResult['PAYMENT'] as $item) {
    $payment[$item['PAY_SYSTEM_ID']] = $item['PAY_SYSTEM_NAME'];

    if ($item['PAID'] != 'Y' && $item['IS_CASH'] != 'Y') {
        $arResult['PAY'][] = $item['BUFFERED_OUTPUT'];
    }
}

$arResult['ORDER_PAYMENT'] = implode(', ', $payment);

$arResult['ORDER_DELIVERY'] = [
    'NAME' => $arResult['DELIVERY']['NAME'],
    'DESCRIPTION' => $arResult['DELIVERY']['DESCRIPTION'],
];

//$arResult['PRICE_FORMATED'] //Стоимость заказа
//$arResult['PRICE_DELIVERY_FORMATED'] // Стоимость доставки
//$arResult['SUM_PAID_FORMATED'] //Оплачено
//$arResult['SUM_REST_FORMATED'] //К оплате
