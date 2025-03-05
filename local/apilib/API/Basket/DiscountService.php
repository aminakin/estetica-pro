<?php

namespace ApiLib\API\Basket;

use Bitrix\Sale\BasketBase;

class DiscountService
{
    private BasketBase $basket;

    public function __construct(BasketBase $basket)
    {
        $this->basket = $basket;
    }

    public function appleDiscount()
    {
        $context = new \Bitrix\Sale\Discount\Context\Fuser($this->basket->getFUserId());
        if ($discounts = \Bitrix\Sale\Discount::buildFromBasket($this->basket, $context)) {
            $r = $discounts->calculate();
            if (!$r->isSuccess()) {
                var_dump($r->getErrorMessages());
            }

            $result = $r->getData();
            if (isset($result['BASKET_ITEMS'])) {
                $r = $this->basket->applyDiscount($result['BASKET_ITEMS']);
                if (!$r->isSuccess()) {
                    var_dump($r->getErrorMessages());
                }
            }
        }
    }
}