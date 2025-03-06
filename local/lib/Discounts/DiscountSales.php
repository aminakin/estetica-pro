<?php

namespace Local\Discounts;

use Local\BasketGiftModule\SalesIblock;

class DiscountSales extends DiscountCore
{

    /**
     * акции  n + 1 из админки
     */
    public function moduleGifts(){
        if (parent::$isAdmin){

            $salesList = SalesIblock::getSales();

            $basketItemsIds = [];
            $basketItemsQuantities = [];
            foreach (parent::$basket as $basketItem) {
                if ($basketItem->getPrice() > 1 && $basketItem->getQuantity() > 0 && !$basketItem->isDelay() && $basketItem->canBuy()) {
                    $basketItemsIds[$basketItem->getProductId()] = $basketItem->getProductId();
                    $basketItemsQuantities[$basketItem->getProductId()] = (int)$basketItem->getQuantity();
                }
            }

            foreach ($salesList as $sale) {
                if(in_array((int)$sale['TRIGGER_PRODUCT_VALUE'], $basketItemsIds) &&
                    $basketItemsQuantities[(int)$sale['TRIGGER_PRODUCT_VALUE']] >= (int)$sale['TRIGGER_QUANTITY_VALUE']){

                    $giftCount = intdiv($basketItemsQuantities[(int)$sale['TRIGGER_PRODUCT_VALUE']], (int)$sale['TRIGGER_QUANTITY_VALUE']);

                    parent::addGift((int)$sale['TRIGGER_PRODUCT_VALUE'], $giftCount);
                }
            }
            parent::saveBasket();
        }
    }
}