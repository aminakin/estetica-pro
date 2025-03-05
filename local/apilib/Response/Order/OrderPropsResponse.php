<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\Basket\BasketItemsResponse;
use ApiLib\Response\JsonResponseAbstract;
use Bitrix\Sale\Order;

class OrderPropsResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="OrderPropsShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="value", type="string"),
     *     @OA\Property(property="type", type="string"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'value' => $data['VALUE'],
            'type' => $data['TYPE'],
        ];
    }
}