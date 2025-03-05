<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilder;
use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Sale\Order;
use Bitrix\Sale;

class OrderEventBuilder
{
    private OrderBuilder $orderBuilder;

    private array $arUserResult;

    private array $arResult;

    private array $arParams;

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder)
    {
        /** @var  orderBuilder OrderBuilder*/
        $this->orderBuilder = $orderBuilder;
        $this->arUserResult = &$this->orderBuilder->getUserResult();
        $this->arResult = &$this->orderBuilder->getArResult();
        $this->arParams = &$this->orderBuilder->getParams();
    }

    public function executeEvent($eventName = '', $order = null)
    {
        $arModifiedResult = $this->arUserResult;

        foreach (GetModuleEvents("sale", $eventName, true) as $arEvent)
            ExecuteModuleEventEx($arEvent, [&$this->arResult, &$arModifiedResult, &$this->arParams, true]);

        if (!empty($order))
            $this->synchronize($arModifiedResult, $order);
    }

    protected function synchronize($arModifiedResult, Order $order)
    {
        $modifiedFields = self::arrayDiffRecursive($arModifiedResult, $this->arUserResult);

        if (!empty($modifiedFields))
            $this->synchronizeOrder($modifiedFields, $order);
    }

    public static function arrayDiffRecursive($arr1, $arr2)
    {
        $modified = [];

        foreach ($arr1 as $key => $value)
        {
            if (array_key_exists($key, $arr2))
            {
                if (is_array($value) && is_array($arr2[$key]))
                {
                    $arDiff = self::arrayDiffRecursive($value, $arr2[$key]);
                    if (!empty($arDiff))
                    {
                        $modified[$key] = $arDiff;
                    }
                }
                elseif ($value != $arr2[$key])
                {
                    $modified[$key] = $value;
                }
            }
            else
            {
                $modified[$key] = $value;
            }
        }

        return $modified;
    }

    protected function synchronizeOrder($modifiedFields, Order $order)
    {
        if (!empty($modifiedFields) && is_array($modifiedFields))
        {
            $recalculatePayment = $modifiedFields['CALCULATE_PAYMENT'] === true;
            unset($modifiedFields['CALCULATE_PAYMENT']);
            $recalculateDelivery = false;

            if (!empty($modifiedFields['PERSON_TYPE_ID']))
            {
                $order->setPersonTypeId($modifiedFields['PERSON_TYPE_ID']);
            }

            $propertyCollection = $order->getPropertyCollection();

            foreach ($modifiedFields as $field => $value)
            {
                switch ($field)
                {
                    case 'PAY_SYSTEM_ID':
                        $recalculatePayment = true;
                        break;
                    case 'PAY_CURRENT_ACCOUNT':
                        $recalculatePayment = true;
                        break;
                    case 'DELIVERY_ID':
                        $recalculateDelivery = true;
                        break;
                    case 'ORDER_PROP':
                        if (is_array($value))
                        {
                            /** @var Sale\PropertyValue $property */
                            foreach ($propertyCollection as $property)
                            {
                                if (array_key_exists($property->getPropertyId(), $value))
                                {
                                    $property->setValue($value[$property->getPropertyId()]);
                                    $arProperty = $property->getProperty();
                                    if ($arProperty['IS_LOCATION'] === 'Y' || $arProperty['IS_ZIP'] === 'Y')
                                    {
                                        $recalculateDelivery = true;
                                    }
                                }
                            }
                        }

                        break;
                    case 'ORDER_DESCRIPTION':
                        $order->setField('USER_DESCRIPTION', $value);
                        break;
                    case 'DELIVERY_LOCATION':
                        $codeValue = \CSaleLocation::getLocationCODEbyID($value);
                        if ($property = $propertyCollection->getDeliveryLocation())
                        {
                            $property->setValue($codeValue);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $codeValue;
                        }

                        $recalculateDelivery = true;
                        break;
                    case 'DELIVERY_LOCATION_BCODE':
                        if ($property = $propertyCollection->getDeliveryLocation())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        $recalculateDelivery = true;
                        break;
                    case 'DELIVERY_LOCATION_ZIP':
                        if ($property = $propertyCollection->getDeliveryLocationZip())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        $recalculateDelivery = true;
                        break;
                    case 'TAX_LOCATION':
                        $codeValue = \CSaleLocation::getLocationCODEbyID($value);
                        if ($property = $propertyCollection->getTaxLocation())
                        {
                            $property->setValue($codeValue);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $codeValue;
                        }

                        break;
                    case 'TAX_LOCATION_BCODE':
                        if ($property = $propertyCollection->getTaxLocation())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                    case 'PAYER_NAME':
                        if ($property = $propertyCollection->getPayerName())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                    case 'USER_EMAIL':
                        if ($property = $propertyCollection->getUserEmail())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                    case 'PROFILE_NAME':
                        if ($property = $propertyCollection->getProfileName())
                        {
                            $property->setValue($value);
                            $this->arUserResult['ORDER_PROP'][$property->getPropertyId()] = $value;
                        }

                        break;
                }

                $this->arUserResult[$field] = $value;
            }

            if ($recalculateDelivery)
            {
                if ($shipment = $this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($order))
                {
                    $this->orderBuilder->getDeliveryBuilder()->initDelivery($shipment);
                    $recalculatePayment = true;
                }
            }

            if ($recalculatePayment)
            {
                $this->orderBuilder->getPaymentBuilder()->recalculatePayment($order);
            }
        }
    }
}