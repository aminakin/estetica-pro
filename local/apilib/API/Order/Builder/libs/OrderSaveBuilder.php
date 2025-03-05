<?php

namespace ApiLib\API\Order\Builder\libs;

use ApiLib\API\Order\Builder\OrderBuilderInterface;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Sale;

class OrderSaveBuilder
{
    private OrderBuilderInterface $orderBuilder;

    private HttpRequest $request;

    private array $arParams;

    private array $arResult;

    private $arUserResult;

    private Sale\Order $order;

    public function setOrderBuilder(OrderBuilderInterface $orderBuilder)
    {
        $this->orderBuilder = $orderBuilder;
        $this->request = $this->orderBuilder->getRequest();
        $this->arParams = &$this->orderBuilder->getParams();
        $this->arUserResult = &$this->orderBuilder->getUserResult();
        $this->arResult = &$this->orderBuilder->getResult();
        $this->order = &$this->orderBuilder->getEntryOrder();
    }

    public function saveOrder($saveToSession = false)
    {
        $arResult =& $this->arResult;

        $this->initStatGid();
        $this->initAffiliate();

        $res = $this->order->save();
        if ($res->isSuccess())
        {
            $arResult["ORDER_ID"] = $res->getId();
            $arResult["ACCOUNT_NUMBER"] = $this->order->getField('ACCOUNT_NUMBER');

            if ($this->arParams['USER_CONSENT'] === 'Y')
            {
                \Bitrix\Main\UserConsent\Consent::addByContext(
                    $this->arParams['USER_CONSENT_ID'], 'sale/order', $arResult['ORDER_ID']
                );
            }

            $fUserId = Sale\Fuser::getId();
            $siteId = $this->orderBuilder->getSiteId();
            Sale\BasketComponentHelper::clearFUserBasketPrice($fUserId, $siteId);
            Sale\BasketComponentHelper::clearFUserBasketQuantity($fUserId, $siteId);
        }
        else
        {
            $this->orderBuilder->addError($res, 'MAIN');
        }

//        if ($arResult['HAVE_PREPAYMENT'] && empty($arResult['ERROR']))
//        {
//            $this->prepayOrder();
//        }

        if (empty($arResult['ERROR']))
        {
            $this->saveProfileData();
        }

        if (empty($arResult['ERROR']))
        {
            $this->addStatistic();

            if ($saveToSession)
            {
                if (!is_array($_SESSION['SALE_ORDER_ID']))
                {
                    $_SESSION['SALE_ORDER_ID'] = [];
                }

                $_SESSION['SALE_ORDER_ID'][] = $res->getId();
            }
        }

        foreach (GetModuleEvents('sale', 'OnSaleComponentOrderOneStepComplete', true) as $arEvent)
        {
            ExecuteModuleEventEx($arEvent, [$arResult['ORDER_ID'], $this->order->getFieldValues(), $this->arParams]);
        }
    }

    protected function initStatGid()
    {
        if (Loader::includeModule("statistic"))
            $this->order->setField('STAT_GID', \CSearchStatistic::GetEventParam());
    }

    protected function initAffiliate()
    {
        $affiliateID = \CSaleAffiliate::GetAffiliate();
        if ($affiliateID > 0)
        {
            $dbAffiliate = \CSaleAffiliate::GetList([], ["SITE_ID" => $this->orderBuilder->getSiteId(), "ID" => $affiliateID]);
            $arAffiliates = $dbAffiliate->Fetch();
            if (count($arAffiliates) > 1)
                $this->order->setField('AFFILIATE_ID', $affiliateID);
        }
    }

//    protected function prepayOrder()
//    {
//        if ($this->prePaymentService && $this->prePaymentService->isPrePayable() && $this->request->get('paypal') == 'Y')
//        {
//            /** @var Payment $payment */
//            $payment = $this->getExternalPayment($this->order);
//            if ($payment)
//            {
//                $this->prePaymentService->setOrderDataForPrePayment(
//                    [
//                        'ORDER_ID' => $this->order->getId(),
//                        'PAYMENT_ID' => $payment->getId(),
//                        'ORDER_PRICE' => $payment->getSum(),
//                        'DELIVERY_PRICE' => $this->order->getDeliveryPrice(),
//                        'TAX_PRICE' => $this->order->getTaxPrice(),
//                    ]
//                );
//
//                $orderData = [];
//                /** @var Sale\BasketItem $item */
//                foreach ($this->order->getBasket() as $item)
//                    $orderData['BASKET_ITEMS'][] = $item->getFieldValues();
//
//                $this->prePaymentService->payOrderByPrePayment($orderData);
//            }
//        }
//    }

    protected function saveProfileData()
    {
        $arResult =& $this->arResult;
        $profileId = 0;
        $profileName = '';
        $properties = [];

        if (isset($arResult['ORDER_PROP']) && is_array($arResult['ORDER_PROP']['USER_PROFILES']))
        {
            foreach ($arResult['ORDER_PROP']['USER_PROFILES'] as $profile)
            {
                if ($profile['CHECKED'] === 'Y')
                {
                    $profileId = (int)$profile['ID'];
                    break;
                }
            }
        }

        $propertyCollection = $this->order->getPropertyCollection();
        if (!empty($propertyCollection))
        {
            if ($profileProp = $propertyCollection->getProfileName())
                $profileName = $profileProp->getValue();

            /** @var Sale\PropertyValue $property */
            foreach ($propertyCollection as $property)
            {
                $properties[$property->getField('ORDER_PROPS_ID')] = $property->getValue();
            }
        }

        \CSaleOrderUserProps::DoSaveUserProfile(
            $this->order->getUserId(),
            $profileId,
            $profileName,
            $this->order->getPersonTypeId(),
            $properties,
            $arResult["ERROR"]
        );
    }

    protected function addStatistic()
    {
        if (Loader::includeModule("statistic"))
        {
            $event1 = "eStore";
            $event2 = "order_confirm";
            $event3 = $this->order->getId();
            $money = $this->order->getPrice();
            $currency = $this->order->getCurrency();

            $e = $event1."/".$event2."/".$event3;

            if (!is_array($_SESSION["ORDER_EVENTS"]) || (is_array($_SESSION["ORDER_EVENTS"]) && !in_array($e, $_SESSION["ORDER_EVENTS"])))
            {
                CStatistic::Set_Event($event1, $event2, $event3, $goto = "", $money, $currency);
                $_SESSION["ORDER_EVENTS"][] = $e;
            }
        }
    }
}