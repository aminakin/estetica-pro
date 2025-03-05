<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;
use Bitrix\Sale\Shipment;

class OrderDeliveryBuilder
{
    private OrderBuilderInterface $orderBuilder;

    private HttpRequest $request;

    private array $arParams;

    private $arUserResult;

    private $arDeliveryServiceAll;

    private $arPaySystemServiceAll;

    private $arResult;

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder)
    {
        $this->orderBuilder = $orderBuilder;
        $this->request = $this->orderBuilder->getRequest();
        $this->arResult = &$this->orderBuilder->getResult();
        $this->arParams = &$this->orderBuilder->getParams();
        $this->arDeliveryServiceAll = &$this->orderBuilder->getDeliveryServiceAll();
        $this->arUserResult = &$this->orderBuilder->getUserResult();
        $this->arPaySystemServiceAll = &$this->orderBuilder->getPaySystemServiceAll();
    }

    public function initDelivery(Shipment $shipment)
    {
        $deliveryId = intval($this->arUserResult['DELIVERY_ID']);
        $this->initDeliveryServices($shipment);
        /** @var \Bitrix\Sale\ShipmentCollection $shipmentCollection */
        $shipmentCollection = $shipment->getCollection();
        $order = $shipmentCollection->getOrder();

        if (empty($this->arDeliveryServiceAll))
        {
            $shipment->delete();
            return;
        }

        if (isset($this->arDeliveryServiceAll[$deliveryId]))
        {
            $deliveryObj = $this->arDeliveryServiceAll[$deliveryId];
        }
        else
        {
            $deliveryObj = reset($this->arDeliveryServiceAll);

            if (!empty($deliveryId))
            {
                // TODO: Надо будет добавлять обработку ошибок!!!
                $this->orderBuilder->addWarning(Loc::getMessage("DELIVERY_CHANGE_WARNING"), $this->orderBuilder::DELIVERY_BLOCK);
            }

            $deliveryId = $deliveryObj->getId();
        }

        if ($deliveryObj->isProfile())
        {
            $name = $deliveryObj->getNameWithParent();
        }
        else
        {
            $name = $deliveryObj->getName();
        }

        $order->isStartField();

        $shipment->setFields([
            'DELIVERY_ID' => $deliveryId,
            'DELIVERY_NAME' => $name,
            'CURRENCY' => $order->getCurrency(),
        ]);
        $this->arUserResult['DELIVERY_ID'] = $deliveryId;

        $deliveryStoreList = \Bitrix\Sale\Delivery\ExtraServices\Manager::getStoresList($deliveryId);
        if (!empty($deliveryStoreList))
        {
            if ($this->arUserResult['BUYER_STORE'] <= 0 || !in_array($this->arUserResult['BUYER_STORE'], $deliveryStoreList))
            {
                $this->arUserResult['BUYER_STORE'] = current($deliveryStoreList);
            }

            $shipment->setStoreId($this->arUserResult['BUYER_STORE']);
        }

        $deliveryExtraServices = $this->arUserResult['DELIVERY_EXTRA_SERVICES'];
        if (is_array($deliveryExtraServices) && !empty($deliveryExtraServices[$deliveryId]))
        {
            $shipment->setExtraServices($deliveryExtraServices[$deliveryId]);
            $deliveryObj->getExtraServices()->setValues($deliveryExtraServices[$deliveryId]);
        }

        $shipmentCollection->calculateDelivery();

        $order->doFinalAction(true);
    }

    protected function initDeliveryServices(Shipment $shipment)
    {
        $services = \Bitrix\Sale\Delivery\Services\Manager::getRestrictedObjectsList($shipment);

        if (!in_array($this->arParams['SHOW_NOT_CALCULATED_DELIVERIES'], ['N', 'L']))
        {
            $this->arDeliveryServiceAll = $services;

            return;
        }

        $prevDeliveryId = $shipment->getDeliveryId();

        $result = [];
        foreach ($services as $deliveryId => $deliveryObj)
        {
            $mustBeCalculated = $this->arParams['DELIVERY_NO_AJAX'] === 'Y'
                || ($this->arParams['DELIVERY_NO_AJAX'] === 'H' && $deliveryObj->isCalculatePriceImmediately());

            if (!$mustBeCalculated)
            {
                $result[$deliveryId] = $deliveryObj;
            }

            $shipment->setField('DELIVERY_ID', $deliveryId);
            $calcResult = $deliveryObj->calculate($shipment);
            if (!$calcResult->isSuccess())
            {
                if ($this->arParams['SHOW_NOT_CALCULATED_DELIVERIES'] === 'N')
                {
                    continue;
                }

                if ($this->arParams['SHOW_NOT_CALCULATED_DELIVERIES'] === 'L')
                {
                    $problemDeliveries[$deliveryId] = $deliveryObj;
                    continue;
                }
            }

            $result[$deliveryId] = $deliveryObj;
        }

        if ($this->arParams['SHOW_NOT_CALCULATED_DELIVERIES'] === 'L' && !empty($problemDeliveries))
        {
            $result += $problemDeliveries;
        }

        $shipment->setField('DELIVERY_ID', $prevDeliveryId);

        $this->arDeliveryServiceAll = $result;
    }

    public function calculateDeliveries(Order $order)
    {
        $this->arResult['DELIVERY'] = [];

        if (!empty($this->arDeliveryServiceAll))
        {
            /** @var Order $orderClone */
            $orderClone = null;
            $anotherDeliveryCalculated = false;
            /** @var Shipment $shipment */
            $shipment = $this->getCurrentShipment($order);

            foreach ($this->arDeliveryServiceAll as $deliveryId => $deliveryObj)
            {
                $calcResult = false;
                $calcOrder = false;
                $arDelivery = [];

                if ((int)$shipment->getDeliveryId() === $deliveryId)
                {
                    $arDelivery['CHECKED'] = 'Y';
                    $mustBeCalculated = true;
                    $calcResult = $deliveryObj->calculate($shipment);
                    $calcOrder = $order;
                }
                else
                {
                    $mustBeCalculated = $this->arParams['DELIVERY_NO_AJAX'] === 'Y'
                        || ($this->arParams['DELIVERY_NO_AJAX'] === 'H' && $deliveryObj->isCalculatePriceImmediately());

                    if ($mustBeCalculated)
                    {
                        $anotherDeliveryCalculated = true;

                        if (empty($orderClone))
                        {
                            $orderClone = $this->orderBuilder->getOrderClone($order);
                        }

                        $orderClone->isStartField();

                        $clonedShipment = $this->getCurrentShipment($orderClone);
                        $clonedShipment->setField('DELIVERY_ID', $deliveryId);

                        $calculationResult = $orderClone->getShipmentCollection()->calculateDelivery();
                        if ($calculationResult->isSuccess())
                        {
                            $calcDeliveries = $calculationResult->get('CALCULATED_DELIVERIES');
                            $calcResult = reset($calcDeliveries);
                        }
                        else
                        {
                            $calcResult = new \Bitrix\Sale\Delivery\CalculationResult();
                            $calcResult->addErrors($calculationResult->getErrors());
                        }

                        $orderClone->doFinalAction(true);

                        $calcOrder = $orderClone;
                    }
                }

                if ($mustBeCalculated)
                {
                    if ($calcResult->isSuccess())
                    {
                        $arDelivery['PRICE'] = \Bitrix\Sale\PriceMaths::roundPrecision($calcResult->getPrice());
                        $arDelivery['PRICE_FORMATED'] = SaleFormatCurrency($arDelivery['PRICE'], $calcOrder->getCurrency());

                        $currentCalcDeliveryPrice = \Bitrix\Sale\PriceMaths::roundPrecision($calcOrder->getDeliveryPrice());
                        if ($currentCalcDeliveryPrice >= 0 && $arDelivery['PRICE'] != $currentCalcDeliveryPrice)
                        {
                            $arDelivery['DELIVERY_DISCOUNT_PRICE'] = $currentCalcDeliveryPrice;
                            $arDelivery['DELIVERY_DISCOUNT_PRICE_FORMATED'] = SaleFormatCurrency($arDelivery['DELIVERY_DISCOUNT_PRICE'], $calcOrder->getCurrency());
                        }

                        if ($calcResult->getPeriodDescription() <> '')
                        {
                            $arDelivery['PERIOD_TEXT'] = $calcResult->getPeriodDescription();
                        }
                    }
                    else
                    {
                        if (count($calcResult->getErrorMessages()) > 0)
                        {
                            foreach ($calcResult->getErrorMessages() as $message)
                            {
                                $arDelivery['CALCULATE_ERRORS'] .= $message.'<br>';
                            }
                        }
                        else
                        {
                            $arDelivery['CALCULATE_ERRORS'] = Loc::getMessage('SOA_DELIVERY_CALCULATE_ERROR');
                        }
                    }

                    $arDelivery['CALCULATE_DESCRIPTION'] = $calcResult->getDescription();
                }

                $this->arResult['DELIVERY'][$deliveryId] = $arDelivery;
            }

            // for discounts: last delivery calculation need to be on real order with selected delivery
            if ($anotherDeliveryCalculated)
            {
                $order->doFinalAction(true);
            }
        }

        $eventParameters = [
            $order, &$this->arUserResult, $this->request,
            &$this->arParams, &$this->arResult, &$this->arDeliveryServiceAll, &$this->arPaySystemServiceAll,
        ];
        foreach (GetModuleEvents('sale', 'OnSaleComponentOrderDeliveriesCalculated', true) as $arEvent)
        {
            ExecuteModuleEventEx($arEvent, $eventParameters);
        }
    }

    public function getCurrentShipment(Order $order)
    {
        /** @var Shipment $shipment */
        foreach ($order->getShipmentCollection() as $shipment)
        {
            if (!$shipment->isSystem())
                return $shipment;
        }

        return null;
    }
}