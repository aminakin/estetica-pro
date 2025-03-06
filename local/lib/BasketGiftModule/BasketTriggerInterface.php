<?php

namespace Local\BasketGiftModule;

interface BasketTriggerInterface
{
    /**
     * Проверка совпадения условий корзины
     */
    public static function checkBasketItems();
}