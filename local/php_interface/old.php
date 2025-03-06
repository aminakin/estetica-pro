<?php


/* АКЦИЯ 17.01.22 - 23.01.22
На этот период сделать условия бесплатной доставки по России при заказе от 4000( с учётом списанных бонусов)
*/
//AddEventHandler('sale', 'OnSaleComponentOrderCreated', ['OnSaleComponentOrderCreated', 'CheckSummForSale'], 100000);
//AddEventHandler('sale', 'OnSaleDeliveryServiceCalculate', ['OnSaleComponentOrderCreated', 'ChangeVisualDelivery'], 100000);
//Main\EventManager::getInstance()->addEventHandler(
//    'sale',
//    'OnSaleOrderBeforeSaved',
//    array('OnSaleComponentOrderCreated', 'ChangeDelivery')
//);

class OnSaleComponentOrderCreated{

    /**
     * @param $order
     * @param $arUserResult
     * @param $request
     * @param $arParams
     * @param $arResult
     * @param $arDeliveryServiceAll
     * @param $arPaySystemServiceAll
     * @return void
     * @throws Main\ArgumentException
     * @throws Main\ArgumentNullException
     * @throws Main\ArgumentOutOfRangeException
     * @throws Main\NotImplementedException
     * @throws Main\NotSupportedException
     * @throws Main\ObjectException
     * @throws Main\ObjectNotFoundException
     */
public function CheckSummForSale($order, &$arUserResult, $request, &$arParams, &$arResult, &$arDeliveryServiceAll, &$arPaySystemServiceAll){

    global $USER;

    $basket = $order->getBasket();

    if (!$basket->getOrder()) {
        global $USER;
        $resUs = $USER instanceof CUser ? $USER->GetID() : null;
        $userId = $resUs ?: CSaleUser::GetAnonymousUserID();
        $fake_order = Bitrix\Sale\Order::create("s1", $userId);
        $fake_order->appendBasket($basket);
    } else {
        $fake_order = $basket->getOrder();
    }

    $discounts = $fake_order->getDiscount();
    $discounts->setExecuteModuleFilter(array('all', 'catalog', 'sale'));
    $discounts->calculate();
    // $order->doFinalAction(true);
    $arDiscounts = $discounts->getApplyResult(true);
    $arPravila = $arDiscounts['PRICES']['BASKET'];

    $basketItems = $basket->getBasketItems();


    $orderSum = 0;
    foreach ($basketItems as $basketItem) {

        $itemPrice = self::getFinalPriceInCurrency($basketItem->getProductId());
        if ((int)$arPravila[$basketItem->getBasketCode()]['PRICE'] < (int)self::getFinalPriceInCurrency($basketItem->getProductId())) {
            $itemPrice = (int)$arPravila[$basketItem->getBasketCode()]['PRICE'];
        }

        $orderSum += $itemPrice * (int)$basketItem->getQuantity();
    }

    $payBonus = $arResult['PAY_BONUS'] ?? 0;
    $realOrderSum = $orderSum - $payBonus;
    $_SESSION['SAVED_ORDER_BASKET_PRICE'] = $realOrderSum;


}

/**
 * @param Event $event
 * @return void
 */
//    public function OnSaleDeliveryServiceCalculate(Bitrix\Main\Event $event){
//
//        $baseResult = $event->getParameter('RESULT');
//        if(isset($_SESSION['SAVED_ORDER_BASKET_PRICE']) && $_SESSION['SAVED_ORDER_BASKET_PRICE'] >= 4000)
//        {
//            $baseResult->setDeliveryPrice(0);
//        }
//        $event->addResult(
//            new \Bitrix\Main\EventResult(
//                \Bitrix\Main\EventResult::SUCCESS, array('RESULT' => $baseResult)
//            )
//        );
//    }

/**
 * @param $result
 * @param $shipment
 * @param $deliveryID
 * @return void
 */
public function ChangeVisualDelivery($result, $shipment, $deliveryID){
    if(isset($_SESSION['SAVED_ORDER_BASKET_PRICE']) && $_SESSION['SAVED_ORDER_BASKET_PRICE'] >= 4000 && $shipment){
        $shipment->setFields(array('PRICE_DELIVERY' => 0, 'BASE_PRICE_DELIVERY' => 0));
    }
}

/**
 * change in order
 * @param Event $event
 * @return void
 */
public function ChangeDelivery(Main\Event $event){

    $deliveryPrice = 0;
    $order = $event->getParameter("ENTITY");
    $shipmentCollection = $order->getShipmentCollection();
    foreach ($shipmentCollection as $shipment) {
        if (!$shipment->isSystem())
            if(isset($_SESSION['SAVED_ORDER_BASKET_PRICE']) && $_SESSION['SAVED_ORDER_BASKET_PRICE'] >= 4000){

                $deliveryPrice = $order->getDeliveryPrice();

                $shipment->setFields(array('PRICE_DELIVERY' => 0, 'BASE_PRICE_DELIVERY' => 0));
                $shipment->setBasePriceDelivery(0, false);
            }
    }

    /* стоимость оплаты поменть так же на суму доставки поменять  */
    if(isset($_SESSION['SAVED_ORDER_BASKET_PRICE']) && $_SESSION['SAVED_ORDER_BASKET_PRICE'] >= 4000){
        if ($deliveryPrice > 0){
            $paymentCollection = $order->getPaymentCollection();

//                AddMessage2Log(var_export($order->getPrice(), true));
            foreach ($paymentCollection as $payment) {
                $payment->setField('SUM', $_SESSION['SAVED_ORDER_BASKET_PRICE']);
            }

        }
    }

}

/**
 * get item price with all sales
 *
 * @param $item_id
 * @param $sale_currency
 * @return void
 */
public function getFinalPriceInCurrency($item_id, $sale_currency = 'RUB')
{
    CModule::IncludeModule("iblock");
    CModule::IncludeModule("catalog");
    CModule::IncludeModule("sale");
    global $USER;

    $currency_code = 'RUB';

    // Простой товар, без торговых предложений (для количества равному 1)
    $price = CCatalogProduct::GetOptimalPrice($item_id, 1, $USER->GetUserGroupArray(), 'N');

    // Получили цену?
    if (!$price || !isset($price['PRICE'])) {
        return false;
    }

    // Меняем код валюты, если нашли
    if (isset($price['CURRENCY'])) {
        $currency_code = $price['CURRENCY'];
    }
    if (isset($price['PRICE']['CURRENCY'])) {
        $currency_code = $price['PRICE']['CURRENCY'];
    }

    // Получаем итоговую цену
    $final_price = $price['PRICE']['PRICE'];

    // Ищем скидки и пересчитываем цену товара с их учетом
    $arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", 2);
    if (is_array($arDiscounts) && sizeof($arDiscounts) > 0) {
        $final_price = CCatalogProduct::CountPriceWithDiscount($final_price, $currency_code, $arDiscounts);
    }


    // Если необходимо, конвертируем в нужную валюту
    if ($currency_code != $sale_currency) {
        $final_price = CCurrencyRates::ConvertCurrency($final_price, $currency_code, $sale_currency);
    }

    return $final_price;

}
}
?>