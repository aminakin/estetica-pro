<?php

namespace Local\BasketGiftModule;

interface SalesIblockInterface
{
    /**
     * Получение списка акций из ИБ
     */
    public static function getSales();
}