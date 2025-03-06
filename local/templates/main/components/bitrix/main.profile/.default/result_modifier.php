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

for ($i = 1900; $i <= date('Y'); $i++) {
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
];

$orderData = \Bitrix\Sale\Order::getList([
    'select' => ['ID'],
    'filter' => ['=USER_ID' => $USER->GetID()],
    'order' => ['ID' => 'DESC'],
    'limit' => 1
]);

$arResult['DELIVERY_BLOCK'] = [];

$lastOrderId = false;

if ($order = $orderData->fetch()) {
    $lastOrderId = $order['ID'];
    $order = Order::load($lastOrderId);
}

if ($lastOrderId) {
    $db_propVals = CSaleOrderUserPropsValue::GetList(array("ID" => "ASC"), array("USER_PROPS_ID" => GetContragentID($lastOrderId)));

    while ($arPropValues = $db_propVals->Fetch()) {
        if ($arPropValues['PROP_CODE'] == 'CITY' || $arPropValues['PROP_CODE'] == 'STREET' || $arPropValues['PROP_CODE'] == 'HOUSE' || $arPropValues['PROP_CODE'] == 'INDEX') {
            if ($arPropValues['PROP_CODE'] == 'CITY') {
                if ($arPropValues['VALUE'] == null) {
                    $ipAddress = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
                    $locationId = \Bitrix\Sale\Location\GeoIp::getLocationId($ipAddress, 'ru');
                } else {
                    $locationId = $arPropValues['VALUE'];
                }
                $city = CSaleLocation::GetByID($locationId);
                $arPropValues['VALUE'] = $city['CITY_NAME'];
            }

            $arResult['DELIVERY_BLOCK'][$arPropValues['PROP_SORT']] = [
                'NAME' => $arPropValues['PROP_NAME'],
                'VALUE' => $arPropValues['VALUE'],
                'ID' => $arPropValues['ID'],
                'TYPE' => $arPropValues['PROP_TYPE'],
                'CODE' => $arPropValues['PROP_CODE'],
            ];
        }
    }

    ksort($arResult['DELIVERY_BLOCK']);
}

$uEmail = $USER->getEmail();
$params = ['EMAIL' => $uEmail];

$contactId = \Bitrix\Sender\ContactTable::addIfNotExist(['EMAIL' => $uEmail]);
$contact = new \Bitrix\Sender\Entity\Contact($contactId);
$data = $contact->getData();
$arResult['HAS_SUBSCRIBE'] = count($data['SUB_LIST']) > 0;

function GetContragentID($orderID)
{
    if ($arOrder = CSaleOrder::GetByID($orderID)) {
        $rsOrderProps = CSaleOrderProps::GetList(array(), array('PERSON_TYPE_ID' => $arOrder['PERSON_TYPE_ID'], 'IS_EMAIL' => 'Y'));
        if ($arOrderProp = $rsOrderProps->Fetch()) {
            $rsProps = CSaleOrderPropsValue::GetList(array('SORT' => 'ASC'), array('ORDER_ID' => $orderID, 'ORDER_PROPS_ID' => $arOrderProp['ID']));
            if ($arProp = $rsProps->Fetch()) {
                $rsUP = CSaleOrderUserPropsValue::GetList(array(), array('ORDER_PROPS_ID' => $arOrderProp['ID'],
                    'VALUE' => $arProp['VALUE'],
                    'PROP_PERSON_TYPE_ID' => $arOrder['PERSON_TYPE_ID']));
                if ($arUP = $rsUP->Fetch()) {
                    return $arUP['USER_PROPS_ID'];
                }
            }
        }
    }
}