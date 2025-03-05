<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Main\HttpRequest;
use Bitrix\Sale\Location\GeoIp;
use Bitrix\Sale\Order;
use Bitrix\Sale;
use Bitrix\Sale\Location\LocationTable;

class OrderPropsBuilder
{
    private OrderBuilderInterface $orderBuilder;

    private HttpRequest $request;

    private array $arParams;

    private $arUserResult;

    const PROPERTY_BLOCK = 'PROPERTY';

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder)
    {
        $this->orderBuilder = $orderBuilder;
        $this->request = $this->orderBuilder->getRequest();
        $this->arParams = &$this->orderBuilder->getParams();
        $this->arUserResult = &$this->orderBuilder->getUserResult();
    }

    public function initProperties(Order $order, $isPersonTypeChanged)
    {
        $arResult =& $this->orderBuilder->getArResult();
        $orderProperties = $this->getPropertyValuesFromRequest();

        // TODO: так понимаю местоположение предыдущего заказа. Пока убираю
        $orderProperties = $this->addLastLocationPropertyValues($orderProperties);
        // профили подгружаем
        $this->initUserProfiles($order, $isPersonTypeChanged);

        $firstLoad = $this->request->getRequestMethod() === 'GET';
        $justAuthorized = $this->request->get('do_authorize') === 'Y'
            || $this->request->get('do_register') === 'Y'
            || $this->request->get('SMS_CODE');

        $isProfileChanged = $this->arUserResult['PROFILE_CHANGE'] === 'Y';
        $haveProfileId = (int)$this->arUserResult['PROFILE_ID'] > 0;

        // TODO: пока не работае с профилем
        $shouldUseProfile = ($firstLoad || $isPersonTypeChanged || $isProfileChanged); //($firstLoad || $justAuthorized || $isPersonTypeChanged || $isProfileChanged);
        $willUseProfile =  $shouldUseProfile && $haveProfileId;

        $profileProperties = [];

        if ($haveProfileId)
        {
            $profileProperties = Sale\OrderUserProperties::getProfileValues((int)$this->arUserResult['PROFILE_ID']);
        }

        $ipAddress = '';

        if ($this->arParams['SPOT_LOCATION_BY_GEOIP'] === 'Y')
        {
            $ipAddress = \Bitrix\Main\Service\GeoIp\Manager::getRealIp();
        }

        foreach ($this->getFullPropertyList($order) as $property)
        {
            if ($property['USER_PROPS'] === 'Y')
            {
                if ($isProfileChanged && !$haveProfileId)
                {
                    $curVal = '';
                }
                elseif (
                    $willUseProfile
                    || (
                        !isset($orderProperties[$property['ID']])
                        && isset($profileProperties[$property['ID']])
                    )
                )
                {
                    $curVal = $profileProperties[$property['ID']];
                }
                elseif (isset($orderProperties[$property['ID']]))
                {
                    $curVal = $orderProperties[$property['ID']];
                }
                else
                {
                    $curVal = '';
                }
            }
            else
            {
                $curVal = isset($orderProperties[$property['ID']]) ? $orderProperties[$property['ID']] : '';
            }
// TODO: Опять что то не интересное для оплаты через что то там не нужное

//            if ($arResult['HAVE_PREPAYMENT'] && !empty($arResult['PREPAY_ORDER_PROPS'][$property['CODE']]))
//            {
//                if ($property['TYPE'] === 'LOCATION')
//                {
//                    $cityName = ToUpper($arResult['PREPAY_ORDER_PROPS'][$property['CODE']]);
//                    $arLocation = LocationTable::getList([
//                        'select' => ['CODE'],
//                        'filter' => ['NAME.NAME_UPPER' => $cityName],
//                    ])
//                        ->fetch()
//                    ;
//
//                    if (!empty($arLocation))
//                    {
//                        $curVal = $arLocation['CODE'];
//                    }
//                }
//                else
//                {
//                    $curVal = $arResult['PREPAY_ORDER_PROPS'][$property['CODE']];
//                }
//            }

            if ($property['TYPE'] === 'LOCATION' && empty($curVal) && !empty($ipAddress))
            {
                $locCode = GeoIp::getLocationCode($ipAddress);

                if (!empty($locCode))
                {
                    $curVal = $locCode;
                }
            }
            elseif ($property['IS_ZIP'] === 'Y' && empty($curVal) && !empty($ipAddress))
            {
                $zip = GeoIp::getZipCode($ipAddress);

                if (!empty($zip))
                {
                    $curVal = $zip;
                }
            }
            elseif ($property['IS_PHONE'] === 'Y' && !empty($curVal))
            {
                $curVal = $this->getNormalizedPhone($curVal);
            }

            if (empty($curVal))
            {
                // getting default value for all properties except LOCATION
                // (LOCATION - just for first load or person type change or new profile)
                if ($property['TYPE'] !== 'LOCATION' || !$willUseProfile)
                {
                    global $USER;

                    if ($shouldUseProfile && $USER->IsAuthorized())
                    {
                        $curVal = $this->getValueFromCUser($property);
                    }

                    if (empty($curVal) && !empty($property['DEFAULT_VALUE']))
                    {
                        $curVal = $property['DEFAULT_VALUE'];
                    }
                }
            }

            if ($property['TYPE'] === 'LOCATION')
            {
                if (
                    (!$shouldUseProfile || $this->request->get('PROFILE_ID') === '0')
                    && $this->request->get('LOCATION_TYPE') !== 'code'
                )
                {
                    $curVal = \CSaleLocation::getLocationCODEbyID($curVal);
                }
            }

            $this->arUserResult['ORDER_PROP'][$property['ID']] = $curVal;
        }

        $this->checkZipProperty($order, $willUseProfile);
        $this->checkAltLocationProperty($order, $willUseProfile, $profileProperties);

        foreach (GetModuleEvents('sale', 'OnSaleComponentOrderProperties', true) as $arEvent)
        {
            ExecuteModuleEventEx($arEvent, [&$this->arUserResult, $this->request, &$this->arParams, &$arResult]);
        }

        $this->setOrderProperties($order);
    }

    protected function addLastLocationPropertyValues($orderProperties)
    {
        $currentPersonType = (int)$this->arUserResult['PERSON_TYPE_ID'];
        $lastPersonType = (int)$this->arUserResult['PERSON_TYPE_OLD'];

        if (!empty($lastPersonType) && $currentPersonType !== $lastPersonType)
        {
            $propsByPersonType = [];

            $props = Sale\Property::getList([
                'select' => ['ID', 'PERSON_TYPE_ID', 'IS_LOCATION', 'IS_ZIP', 'DEFAULT_VALUE'],
                'filter' => [
                    [
                        'LOGIC' => 'OR',
                        '=IS_ZIP' => 'Y',
                        '=IS_LOCATION' => 'Y',
                    ],
                    [
                        '@PERSON_TYPE_ID' => [$currentPersonType, $lastPersonType],
                    ],
                ],
            ]);

            foreach ($props as $prop)
            {
                if ($prop['PERSON_TYPE_ID'] == $currentPersonType && !empty($prop['DEFAULT_VALUE']))
                {
                    continue;
                }

                if ($prop['IS_LOCATION'] === 'Y')
                {
                    $propsByPersonType[$prop['PERSON_TYPE_ID']]['IS_LOCATION'] = $prop['ID'];
                }
                else
                {
                    $propsByPersonType[$prop['PERSON_TYPE_ID']]['IS_ZIP'] = $prop['ID'];
                }
            }

            if (!empty($propsByPersonType[$lastPersonType]))
            {
                foreach ($propsByPersonType[$lastPersonType] as $code => $id)
                {
                    if (!empty($propsByPersonType[$currentPersonType][$code]))
                    {
                        $newId = $propsByPersonType[$currentPersonType][$code];

                        if (empty($orderProperties[$newId]) && !empty($orderProperties[$id]))
                        {
                            $orderProperties[$newId] = $orderProperties[$id];
                        }
                    }
                }
            }
        }

        return $orderProperties;
    }

    protected function initUserProfiles(Order $order, $isPersonTypeChanged)
    {
        $arResult =& $this->orderBuilder->getArResult();

        $justAuthorized = $this->request->get('do_authorize') === 'Y' || $this->request->get('do_register') === 'Y';
        $profileIsNotSelected = $this->arUserResult['PROFILE_CHANGE'] === false || $this->arUserResult['PROFILE_ID'] === false;
        $bFirst = false;

        $dbUserProfiles = \CSaleOrderUserProps::GetList(
            ['DATE_UPDATE' => 'DESC'],
            [
                'PERSON_TYPE_ID' => $order->getPersonTypeId(),
                'USER_ID' => $order->getUserId(),
            ]
        );
        while ($arUserProfiles = $dbUserProfiles->GetNext())
        {
            if (!$bFirst && ($profileIsNotSelected || $isPersonTypeChanged || $justAuthorized))
            {
                $bFirst = true;
                $this->arUserResult['PROFILE_ID'] = (int)$arUserProfiles['ID'];
            }

            if ((int)$this->arUserResult['PROFILE_ID'] === (int)$arUserProfiles['ID'])
            {
                $arUserProfiles['CHECKED'] = 'Y';
            }

            $arResult['ORDER_PROP']['USER_PROFILES'][$arUserProfiles['ID']] = $arUserProfiles;
        }
    }

    protected function getPropertyValuesFromRequest()
    {
        $orderProperties = [];

        foreach ($this->request as $k => $v)
        {
            if (mb_strpos($k, "ORDER_PROP_") !== false)
            {
                if (mb_strpos($k, "[]") !== false)
                    $orderPropId = intval(mb_substr($k, mb_strlen("ORDER_PROP_"), mb_strlen($k) - 2));
                else
                    $orderPropId = intval(mb_substr($k, mb_strlen("ORDER_PROP_")));

                if ($orderPropId > 0)
                    $orderProperties[$orderPropId] = $v;
            }
        }

        foreach ($this->request->getFileList() as $k => $arFileData)
        {
            if (mb_strpos($k, "ORDER_PROP_") !== false)
            {
                $orderPropId = intval(mb_substr($k, mb_strlen("ORDER_PROP_")));

                if (is_array($arFileData))
                {
                    foreach ($arFileData as $param_name => $value)
                    {
                        if (is_array($value))
                        {
                            foreach ($value as $nIndex => $val)
                            {
                                if ($arFileData["name"][$nIndex] <> '')
                                {
                                    $orderProperties[$orderPropId][$nIndex][$param_name] = $val;
                                }

                                if (!isset($orderProperties[$orderPropId][$nIndex]['ID']))
                                {
                                    $orderProperties[$orderPropId][$nIndex]['ID'] = '';
                                }
                            }
                        }
                        else
                        {
                            $orderProperties[$orderPropId][$param_name] = $value;

                            if (!isset($orderProperties[$orderPropId]['ID']))
                            {
                                $orderProperties[$orderPropId]['ID'] = '';
                            }
                        }
                    }
                }
            }
        }

        return $orderProperties;
    }

    protected function getNormalizedPhone($phone)
    {

        // TODO: Надо будет посмотреть условие

        if ($this->arParams['USE_PHONE_NORMALIZATION'] === 'Y')
        {
            $phone = NormalizePhone((string)$phone, 3);
        }

        return $phone;
    }

    protected function getValueFromCUser($property)
    {
        global $USER;

        $value = '';

        if ($property['IS_EMAIL'] === 'Y')
        {
            $value = $USER->GetEmail();
        }
        elseif ($property['IS_PAYER'] === 'Y')
        {
            $rsUser = \CUser::GetByID($USER->GetID());
            if ($arUser = $rsUser->Fetch())
            {
                $value = \CUser::FormatName(
                    \CSite::GetNameFormat(false),
                    [
                        'NAME' => $arUser['NAME'],
                        'LAST_NAME' => $arUser['LAST_NAME'],
                        'SECOND_NAME' => $arUser['SECOND_NAME'],
                    ],
                    false,
                    false
                );
            }
        }
        elseif ($property['IS_PHONE'] === 'Y')
        {
            $phoneRow = \Bitrix\Main\UserPhoneAuthTable::getRow([
                'select' => ['PHONE_NUMBER'],
                'filter' => ['=USER_ID' => $USER->GetID()],
            ]);

            if ($phoneRow)
            {
                $value = $phoneRow['PHONE_NUMBER'];
            }
            else
            {
                $rsUser = \CUser::GetByID($USER->GetID());

                if ($arUser = $rsUser->Fetch())
                {
                    if (!empty($arUser['PERSONAL_PHONE']))
                    {
                        $value = $arUser['PERSONAL_PHONE'];
                    }
                    elseif (!empty($arUser['PERSONAL_MOBILE']))
                    {
                        $value = $arUser['PERSONAL_MOBILE'];
                    }
                }
            }
        }
        elseif ($property['IS_ADDRESS'] === 'Y')
        {
            $rsUser = \CUser::GetByID($USER->GetID());
            if ($arUser = $rsUser->Fetch())
            {
                if (!empty($arUser['PERSONAL_STREET']))
                {
                    $value = $arUser['PERSONAL_STREET'];
                }
            }
        }

        return $value;
    }

    protected function checkZipProperty(Order $order, $loadFromProfile)
    {
        $propertyCollection = $order->getPropertyCollection();
        $zip = $propertyCollection->getDeliveryLocationZip();
        $location = $propertyCollection->getDeliveryLocation();
        if (!empty($zip) && !empty($location))
        {
            $locId = $location->getField('ORDER_PROPS_ID');
            $locValue = $this->arUserResult['ORDER_PROP'][$locId];

            // need to override flag for zip data from profile
            if ($loadFromProfile)
            {
                $this->arUserResult['ZIP_PROPERTY_CHANGED'] = 'Y';
            }

            $requestLocation = $this->request->get('RECENT_DELIVERY_VALUE');
            // reload zip when user manually choose another location
            if ($requestLocation !== null && $locValue !== $requestLocation)
            {
                $this->arUserResult['ZIP_PROPERTY_CHANGED'] = 'N';
            }

            // don't autoload zip property if user manually changed it
            if ($this->arUserResult['ZIP_PROPERTY_CHANGED'] !== 'Y')
            {
                $res = \Bitrix\Sale\Location\Admin\LocationHelper::getZipByLocation($locValue);

                if ($arZip = $res->fetch())
                {
                    if (!empty($arZip['XML_ID']))
                    {
                        $this->arUserResult['ORDER_PROP'][$zip->getField('ORDER_PROPS_ID')] = $arZip['XML_ID'];
                    }
                }
            }
        }
    }

    protected function checkAltLocationProperty(Order $order, $useProfileProperties, array $profileProperties)
    {
        $locationAltPropDisplayManual = $this->request->get('LOCATION_ALT_PROP_DISPLAY_MANUAL');
        $propertyCollection = $order->getPropertyCollection();
        /** @var \Bitrix\Sale\PropertyValue $property */
        foreach ($propertyCollection as $property)
        {
            if ($property->isUtil())
                continue;

            if ($property->getType() == 'LOCATION')
            {
                $propertyFields = $property->getProperty();
                if ((int)$propertyFields['INPUT_FIELD_LOCATION'] > 0)
                {
                    if ($useProfileProperties)
                    {
                        $deleteAltProp = empty($profileProperties[$propertyFields['INPUT_FIELD_LOCATION']]);
                    }
                    else
                    {
                        $deleteAltProp = !isset($locationAltPropDisplayManual[$propertyFields['ID']])
                            || !(bool)$locationAltPropDisplayManual[$propertyFields['ID']];

                        // check if have no city at all then show alternate property
                        if (
                            isset($locationAltPropDisplayManual[$propertyFields['ID']])
                            && !$this->haveCitiesInTree($this->arUserResult['ORDER_PROP'][$property->getPropertyId()])
                        )
                        {
                            $deleteAltProp = false;
                        }
                    }

                    if ($deleteAltProp)
                    {
                        unset($this->arUserResult['ORDER_PROP'][$propertyFields['INPUT_FIELD_LOCATION']]);
                    }
                }
            }
        }
    }

    protected function haveCitiesInTree($locationCode)
    {
        if (empty($locationCode))
            return false;

        $haveCities = false;
        $location = LocationTable::getRow(['filter' => ['=CODE' => $locationCode]]);

        if (!empty($location))
        {
            if ($location['TYPE_ID'] >= 5)
            {
                $haveCities = true;
            }
            else
            {
                $parameters = [
                    'filter' => [
                        '>=LEFT_MARGIN' => (int)$location['LEFT_MARGIN'],
                        '<=RIGHT_MARGIN' => (int)$location['RIGHT_MARGIN'],
                        '>=DEPTH_LEVEL' => (int)$location['DEPTH_LEVEL'],
                        '!CITY_ID' => null,
                    ],
                    'count_total' => true,
                ];
                $haveCities = LocationTable::getList($parameters)->getCount() > 0;
            }
        }

        return $haveCities;
    }

    protected function getFullPropertyList(Order $order)
    {
        $registry = Sale\Registry::getInstance(Sale\Registry::REGISTRY_TYPE_ORDER);
        /** @var \Bitrix\Sale\PropertyBase $propertyClassName */
        $propertyClassName = $registry->getPropertyClassName();

        $result = $propertyClassName::getList([
            'select' => [
                'ID', 'PERSON_TYPE_ID', 'NAME', 'TYPE', 'REQUIRED', 'DEFAULT_VALUE', 'SORT',
                'USER_PROPS', 'IS_LOCATION', 'PROPS_GROUP_ID', 'DESCRIPTION', 'IS_EMAIL', 'IS_PROFILE_NAME',
                'IS_PAYER', 'IS_LOCATION4TAX', 'IS_FILTERED', 'CODE', 'IS_ZIP', 'IS_PHONE', 'IS_ADDRESS',
                'ACTIVE', 'UTIL', 'INPUT_FIELD_LOCATION', 'MULTIPLE', 'SETTINGS',
            ],
            'filter' => [
                '=PERSON_TYPE_ID' => $order->getPersonTypeId(),
            ],
            'order' => ['SORT' => 'ASC'],
        ]);

        return $result->fetchAll();
    }

    protected function setOrderProperties(Order $order)
    {
        $propertyCollection = $order->getPropertyCollection();

        $res = $propertyCollection->setValuesFromPost(['PROPERTIES' => $this->arUserResult['ORDER_PROP']], []);

        if ($this->orderBuilder->getIsOrderConfirmed())
        {
            if (!$res->isSuccess())
            {
                $this->orderBuilder->addError($res, $this->orderBuilder::PROPERTY_BLOCK);
            }

            /** @var Sale\PropertyValue $propertyValue */
            foreach ($propertyCollection as $propertyValue)
            {
                if ($propertyValue->isUtil())
                {
                    continue;
                }

                $res = $propertyValue->verify();
                if (!$res->isSuccess())
                {
                    $this->orderBuilder->addError($res, $this->orderBuilder::PROPERTY_BLOCK);
                }

                $res = $propertyValue->checkRequiredValue($propertyValue->getPropertyId(), $propertyValue->getValue());
                if (!$res->isSuccess())
                {
                    $this->orderBuilder->addError($res, $this->orderBuilder::PROPERTY_BLOCK);
                }
            }
        }
    }
}