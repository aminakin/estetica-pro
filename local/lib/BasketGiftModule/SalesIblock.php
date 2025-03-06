<?php

namespace Local\BasketGiftModule;

use Bitrix\Iblock\Iblock;

class SalesIblock implements SalesIblockInterface
{
    const SALES_API = 'basketsales';
    const SALES_ID = 44;

    const TTL = 86400;

    /**
     * @inheritDoc
     */
    public static function getSales()
    {
        //d7 get basket

        $salesIblock = Iblock::wakeUp(self::SALES_ID)->getEntityDataClass();

        return $salesIblock::getList([
            'select' => ['ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'TIMESTAMP_X', 'TRIGGER_PRODUCT_' => 'TRIGGER_PRODUCT', 'TRIGGER_QUANTITY_' => 'TRIGGER_QUANTITY'],
            'filter' => [
                '=ACTIVE' => 'Y'
            ],
            'cache' => [
                'ttl' => self::TTL,
            ]
        ])->fetchAll();
    }
}