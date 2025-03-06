<?php
/**
 * @param array $arParams
 * @param array $arResult
 * @global CMain $APPLICATION
 */

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Sale\Order;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
global $USER;

Loc::loadMessages(__FILE__);

CModule::IncludeModule('sale');


$days = [];
$months = [];
$years = [];

$days[0] = Loc::getMessage('DAY_0');

for ($i = 1; $i <= 31; $i++) {
    $d = $i < 10 ? '0' . $i : $i;
    $days[$d] = $d;
}

for ($i = 0; $i <= 12; $i++) {
    $months[$i < 10 && $i > 0 ? '0' . $i : $i] = Loc::getMessage('MONTH_' . $i);
}

$years[0] = Loc::getMessage('YEAR_0');

for ($i = 1950; $i <= date('Y'); $i++) {
    $years[$i] = $i;
}

$calendar = [
    'DAY' => $days,
    'MONTH' => $months,
    'YEAR' => $years,
];

$arResult['CALENDAR'] = $calendar;
$day = $month = $year = 0;

if ($arResult['arUser']['PERSONAL_BIRTHDAY'] !== null) {
    $date = strtotime($arResult['arUser']['PERSONAL_BIRTHDAY']);
    $day = date("d", $date);
    $month = date("m", $date);
    $year = date("Y", $date);
}

$arResult['BIRTHDAY'] = [
    'DAY' => $day,
    'MONTH' => $month,
    'YEAR' => $year,
];?>