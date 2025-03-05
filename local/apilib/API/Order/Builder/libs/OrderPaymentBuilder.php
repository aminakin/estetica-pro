<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Order;
use Bitrix\Sale\Shipment;

class OrderPaymentBuilder
{
    private OrderBuilderInterface $orderBuilder;

    private HttpRequest $request;

    private array $arParams;

    private $arUserResult;

    private $arPaySystemServiceAll=[];

    private $arActivePaySystems=[];

    const PAY_SYSTEM_BLOCK = 'PAY_SYSTEM';

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder)
    {
        $this->orderBuilder = $orderBuilder;
        $this->request = $this->orderBuilder->getRequest();
        $this->arParams = &$this->orderBuilder->getParams();
        $this->arUserResult = &$this->orderBuilder->getUserResult();
        $this->arPaySystemServiceAll = &$this->orderBuilder->getPaySystemServiceAll();
        $this->arActivePaySystems = &$this->orderBuilder->getActivePaySystem();
    }

    public function initPayment(Order $order)
    {
        [$sumToSpend, $innerPaySystemList] = $this->getInnerPaySystemInfo($order);

        if ($sumToSpend > 0)
        {
            $innerPayment = $this->getInnerPayment($order);
            if (!empty($innerPayment))
            {
                if ($this->arUserResult['PAY_CURRENT_ACCOUNT'] === 'Y')
                {
                    $innerPayment->setField('SUM', $sumToSpend);
                }
                else
                {
                    $innerPayment->delete();
                    $innerPayment = null;
                }

                $this->arPaySystemServiceAll = $this->arActivePaySystems = $innerPaySystemList;
            }
        }

        $innerPaySystemId = \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId();
        $extPaySystemId = (int)$this->arUserResult['PAY_SYSTEM_ID'];

        $paymentCollection = $order->getPaymentCollection();
        $remainingSum = $order->getPrice() - $paymentCollection->getSum();
        if ($remainingSum > 0 || $order->getPrice() == 0)
        {
            /** @var \Bitrix\Sale\Payment $extPayment */
            $extPayment = $paymentCollection->createItem();
            $extPayment->setField('SUM', $remainingSum);

            $extPaySystemList = \Bitrix\Sale\PaySystem\Manager::getListWithRestrictions($extPayment);

            // we already checked restrictions for inner pay system (could be different by price restrictions)
            if (empty($innerPaySystemList[$innerPaySystemId]))
            {
                unset($extPaySystemList[$innerPaySystemId]);
            }
            elseif (empty($extPaySystemList[$innerPaySystemId]))
            {
                $extPaySystemList[$innerPaySystemId] = $innerPaySystemList[$innerPaySystemId];
            }

            $this->arPaySystemServiceAll = $this->arActivePaySystems = $extPaySystemList;

            if ($extPaySystemId !== 0 && array_key_exists($extPaySystemId, $this->arPaySystemServiceAll))
            {
                $selectedPaySystem = $this->arPaySystemServiceAll[$extPaySystemId];
            }
            else
            {
                reset($this->arPaySystemServiceAll);

                if (key($this->arPaySystemServiceAll) == $innerPaySystemId)
                {
                    if (count($this->arPaySystemServiceAll) > 1)
                    {
                        next($this->arPaySystemServiceAll);
                    }
                    elseif ($sumToSpend > 0)
                    {
                        $extPayment->delete();
                        $extPayment = null;

                        /** @var \Bitrix\Sale\Payment $innerPayment */
                        $innerPayment = $this->getInnerPayment($order);
                        if (empty($innerPayment))
                        {
                            $innerPayment = $paymentCollection->getInnerPayment();
                            if (!$innerPayment)
                            {
                                $innerPayment = $paymentCollection->createInnerPayment();
                            }
                        }

                        $sumToPay = $remainingSum > $sumToSpend ? $sumToSpend : $remainingSum;
                        $innerPayment->setField('SUM', $sumToPay);
                    }
                    else
                    {
                        unset($this->arActivePaySystems[$innerPaySystemId]);
                        unset($this->arPaySystemServiceAll[$innerPaySystemId]);
                    }
                }

                $selectedPaySystem = current($this->arPaySystemServiceAll);

                if (!empty($selectedPaySystem) && $extPaySystemId != 0)
                {
                    // TODO: ошибки потом посмотрим!
                    $this->orderBuilder->addWarning(Loc::getMessage('PAY_SYSTEM_CHANGE_WARNING'), $this->orderBuilder::PAY_SYSTEM_BLOCK);
                }
            }

            if (!empty($selectedPaySystem))
            {
                if ($selectedPaySystem['ID'] != $innerPaySystemId)
                {
                    $extPayment->setFields([
                        'PAY_SYSTEM_ID' => $selectedPaySystem['ID'],
                        'PAY_SYSTEM_NAME' => $selectedPaySystem['NAME'],
                    ]);

                    $this->arUserResult['PAY_SYSTEM_ID'] = $selectedPaySystem['ID'];
                }
            }
            elseif (!empty($extPayment))
            {
                $extPayment->delete();
                $extPayment = null;
            }
        }

        if (!empty($this->arUserResult['PREPAYMENT_MODE']))
        {
            $this->showOnlyPrepaymentPs($this->arUserResult['PAY_SYSTEM_ID']);
        }
    }

    protected function getInnerPaySystemInfo(Order $order, $recalculate = false)
    {
        $arResult =& $this->orderBuilder->getArResult();

        $sumToSpend = 0;
        $arPaySystemServices = [];

        if ($this->arParams['PAY_FROM_ACCOUNT'] === 'Y' && $order->isAllowPay())
        {
            $innerPaySystemId = \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId();
            $innerPayment = $order->getPaymentCollection()->getInnerPayment();

            if (!$innerPayment)
            {
                $innerPayment = $order->getPaymentCollection()->createInnerPayment();
            }

            if (!$innerPayment)
            {
                return [0, $arPaySystemServices];
            }

            $this->orderBuilder->loadUserAccount($order);
            $userBudget = (float)$arResult['USER_ACCOUNT']['CURRENT_BUDGET'];

            // finding correct inner pay system price ranges to setField()
            $sumRange = \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::getPriceRange($innerPayment, $innerPaySystemId);
            if (!empty($sumRange))
            {
                if (
                    (empty($sumRange['MIN']) || $sumRange['MIN'] <= $userBudget)
                    && (empty($sumRange['MAX']) || $sumRange['MAX'] >= $userBudget)
                )
                {
                    $sumToSpend = $userBudget;
                }

                if (!empty($sumRange['MAX']) && $sumRange['MAX'] <= $userBudget)
                {
                    $sumToSpend = $sumRange['MAX'];
                }
            }
            else
            {
                $sumToSpend = $userBudget;
            }

            $sumToSpend = $sumToSpend >= $order->getPrice() ? $order->getPrice() : $sumToSpend;

            if ($this->arParams['ONLY_FULL_PAY_FROM_ACCOUNT'] === 'Y' && $sumToSpend < $order->getPrice())
            {
                $sumToSpend = 0;
            }

            if (!empty($arResult['USER_ACCOUNT']) && $sumToSpend > 0)
            {
                // setting inner payment price
                $innerPayment->setField('SUM', $sumToSpend);
                // getting allowed pay systems by restrictions
                $arPaySystemServices = \Bitrix\Sale\PaySystem\Manager::getListWithRestrictions($innerPayment);
                // delete inner pay system if restrictions has not passed
                if (!isset($arPaySystemServices[$innerPaySystemId]))
                {
                    $innerPayment->delete();
                    $sumToSpend = 0;
                }
            }
            else
            {
                $innerPayment->delete();
            }
        }

        if ($sumToSpend > 0)
        {
            $arResult['PAY_FROM_ACCOUNT'] = 'Y';
            $arResult['CURRENT_BUDGET_FORMATED'] = SaleFormatCurrency($arResult['USER_ACCOUNT']['CURRENT_BUDGET'], $order->getCurrency());
        }
        else
        {
            $arResult['PAY_FROM_ACCOUNT'] = 'N';
            unset($arResult['CURRENT_BUDGET_FORMATED']);
        }

        return [$sumToSpend, $arPaySystemServices];
    }

    public function getInnerPayment(Order $order)
    {
        /** @var \Bitrix\Sale\Payment $payment */
        foreach ($order->getPaymentCollection() as $payment)
        {
            if ($payment->getPaymentSystemId() == \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId())
                return $payment;
        }

        return null;
    }

    protected function showOnlyPrepaymentPs($paySystemId)
    {
        if (empty($this->arPaySystemServiceAll) || intval($paySystemId) == 0)
            return;

        foreach ($this->arPaySystemServiceAll as $key => $psService)
        {
            if ($paySystemId != $psService['ID'])
            {
                unset($this->arPaySystemServiceAll[$key]);
                unset($this->arActivePaySystems[$key]);
            }
        }
    }


    /***************************************/
    public function recalculatePayment(Order $order)
    {
        $res = $order->getShipmentCollection()->calculateDelivery();

        if (!$res->isSuccess())
        {
            $shipment = $this->orderBuilder->getDeliveryBuilder()->getCurrentShipment($order);

            if (!empty($shipment))
            {
                $errMessages = '';
                $errors = $res->getErrorMessages();

                if (!empty($errors))
                {
                    foreach ($errors as $message)
                    {
                        $errMessages .= $message.'<br />';
                    }
                }
                else
                {
                    $errMessages = Loc::getMessage('SOA_DELIVERY_CALCULATE_ERROR');
                }

                $r = new \Bitrix\Sale\Result();
                $r->addError(new \Bitrix\Sale\ResultWarning(
                    $errMessages,
                    'SALE_DELIVERY_CALCULATE_ERROR'
                ));

                \Bitrix\Sale\EntityMarker::addMarker($order, $shipment, $r);
                $shipment->setField('MARKED', 'Y');
            }
        }

        [$sumToSpend, $innerPaySystemList] = $this->getInnerPaySystemInfo($order, true);

        $innerPayment = $this->getInnerPayment($order);
        if (!empty($innerPayment))
        {
            if ($this->arUserResult['PAY_CURRENT_ACCOUNT'] === 'Y' && $sumToSpend > 0)
            {
                $innerPayment->setField('SUM', $sumToSpend);
            }
            else
            {
                $innerPayment->delete();
                $innerPayment = null;
            }

            if ($sumToSpend > 0)
            {
                $this->arPaySystemServiceAll = $innerPaySystemList;
                $this->arActivePaySystems += $innerPaySystemList;
            }
        }

        /** @var \Bitrix\Sale\Payment $innerPayment */
        $innerPayment = $this->getInnerPayment($order);
        /** @var \Bitrix\Sale\Payment $extPayment */
        $extPayment = $this->getExternalPayment($order);

        $remainingSum = empty($innerPayment) ? $order->getPrice() : $order->getPrice() - $innerPayment->getSum();
        if ($remainingSum > 0 || $order->getPrice() == 0)
        {
            $paymentCollection = $order->getPaymentCollection();
            $innerPaySystemId = \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId();
            $extPaySystemId = (int)$this->arUserResult['PAY_SYSTEM_ID'];

            if (empty($extPayment))
            {
                $extPayment = $paymentCollection->createItem();
            }

            $extPayment->setField('SUM', $remainingSum);

            $extPaySystemList = \Bitrix\Sale\PaySystem\Manager::getListWithRestrictions($extPayment);
            // we already checked restrictions for inner pay system (could be different by price restrictions)
            if (empty($innerPaySystemList[$innerPaySystemId]))
            {
                unset($extPaySystemList[$innerPaySystemId]);
            }
            elseif (empty($extPaySystemList[$innerPaySystemId]))
            {
                $extPaySystemList[$innerPaySystemId] = $innerPaySystemList[$innerPaySystemId];
            }

            $this->arPaySystemServiceAll = $extPaySystemList;
            $this->arActivePaySystems += $extPaySystemList;

            if ($extPaySystemId !== 0 && array_key_exists($extPaySystemId, $this->arPaySystemServiceAll))
            {
                $selectedPaySystem = $this->arPaySystemServiceAll[$extPaySystemId];
            }
            else
            {
                reset($this->arPaySystemServiceAll);

                if (key($this->arPaySystemServiceAll) == $innerPaySystemId)
                {
                    if (count($this->arPaySystemServiceAll) > 1)
                    {
                        next($this->arPaySystemServiceAll);
                    }
                    elseif ($sumToSpend > 0)
                    {
                        $extPayment->delete();
                        $extPayment = null;

                        /** @var \Bitrix\Sale\Payment $innerPayment */
                        $innerPayment = $this->getInnerPayment($order);
                        if (empty($innerPayment))
                        {
                            $innerPayment = $paymentCollection->getInnerPayment();
                            if (!$innerPayment)
                            {
                                $innerPayment = $paymentCollection->createInnerPayment();
                            }
                        }

                        $sumToPay = $remainingSum > $sumToSpend ? $sumToSpend : $remainingSum;
                        $innerPayment->setField('SUM', $sumToPay);

                        if ($order->getPrice() - $paymentCollection->getSum() > 0)
                        {
                            // TODO: Посмотреть ероры
                            $this->orderBuilder->addWarning(Loc::getMessage('INNER_PAYMENT_BALANCE_ERROR'), $this->orderBuilder::PAY_SYSTEM_BLOCK);

                            $r = new \Bitrix\Sale\Result();
                            $r->addError(new \Bitrix\Sale\ResultWarning(
                                Loc::getMessage('INNER_PAYMENT_BALANCE_ERROR'),
                                'SALE_INNER_PAYMENT_BALANCE_ERROR'
                            ));

                            \Bitrix\Sale\EntityMarker::addMarker($order, $innerPayment, $r);
                            $innerPayment->setField('MARKED', 'Y');
                        }
                    }
                    else
                    {
                        unset($this->arActivePaySystems[$innerPaySystemId]);
                        unset($this->arPaySystemServiceAll[$innerPaySystemId]);
                    }
                }

                $selectedPaySystem = current($this->arPaySystemServiceAll);

                if (!empty($selectedPaySystem) && $extPaySystemId != 0)
                {
//                    TODO: потом посмотрим ошибки
                    $this->orderBuilder->addWarning(Loc::getMessage('PAY_SYSTEM_CHANGE_WARNING'), $this->orderBuilder::PAY_SYSTEM_BLOCK);
                }
            }

            if (!empty($this->arPaySystemServiceAll)
                && !array_key_exists((int)$selectedPaySystem['ID'], $this->arPaySystemServiceAll)
            )
            {
                // TODO: потом посмотрим ошибки
                $this->orderBuilder->addError(Loc::getMessage('P2D_CALCULATE_ERROR'), $this->orderBuilder::PAY_SYSTEM_BLOCK);
                $this->orderBuilder->addError(Loc::getMessage('P2D_CALCULATE_ERROR'), $this->orderBuilder::DELIVERY_BLOCK);
            }

            if (!empty($selectedPaySystem))
            {
                if ($selectedPaySystem['ID'] != $innerPaySystemId)
                {
                    $codSum = 0;
                    $service = \Bitrix\Sale\PaySystem\Manager::getObjectById($selectedPaySystem['ID']);
                    if ($service !== null)
                    {
                        $codSum = $service->getPaymentPrice($extPayment);
                    }

                    $extPayment->setFields([
                        'PAY_SYSTEM_ID' => $selectedPaySystem['ID'],
                        'PAY_SYSTEM_NAME' => $selectedPaySystem['NAME'],
                        'PRICE_COD' => $codSum,
                    ]);

                    $this->arUserResult['PAY_SYSTEM_ID'] = $selectedPaySystem['ID'];
                }
            }
            elseif (!empty($extPayment))
            {
                $extPayment->delete();
                $extPayment = null;
            }

            if (!empty($this->arUserResult['PREPAYMENT_MODE']))
            {
                $this->showOnlyPrepaymentPs($this->arUserResult['PAY_SYSTEM_ID']);
            }
        }

        if (!empty($innerPayment) && !empty($extPayment) && $remainingSum == 0)
        {
            $extPayment->delete();
            $extPayment = null;
        }
    }

    public function getExternalPayment(Order $order)
    {
        /** @var \Bitrix\Sale\Payment $payment */
        foreach ($order->getPaymentCollection() as $payment)
        {
            if ($payment->getPaymentSystemId() != \Bitrix\Sale\PaySystem\Manager::getInnerPaySystemId())
                return $payment;
        }

        return null;
    }
}