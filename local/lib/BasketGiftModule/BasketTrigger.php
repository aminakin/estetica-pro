<?php

namespace Local\BasketGiftModule;

use Bitrix\Main\Context;
use Bitrix\Main\Loader;
use Bitrix\Sale\Basket;
use Bitrix\Sale\Fuser;

class BasketTrigger implements BasketTriggerInterface
{
    protected static $basket;
    protected static $sales;

    public static function checkBasketItems()
    {

        self::$basket = self::getBasket();

        self::$sales = SalesIblock::getSales();

        $basketItemsIds = [];
        $basketItemsQuantities = [];
        foreach (self::$basket as $basketItem) {
            if ($basketItem->getPrice() > 1 && $basketItem->getQuantity() > 0 && !$basketItem->isDelay() && $basketItem->canBuy()) {
                $basketItemsIds[$basketItem->getProductId()] = $basketItem->getProductId();
                $basketItemsQuantities[$basketItem->getProductId()] = (int)$basketItem->getQuantity();
            }
        }

        foreach (self::$sales as $sale) {
            if(in_array((int)$sale['TRIGGER_PRODUCT_VALUE'], $basketItemsIds)){
                return $sale;
            }
        }

        return false;
    }

    /**
     * Товары из корзины
     */
    private static function getBasket()
    {
        Loader::includeModule('sale');
        return self::$basket = Basket::loadItemsForFUser(Fuser::getId(), Context::getCurrent()->getSite());
    }
}