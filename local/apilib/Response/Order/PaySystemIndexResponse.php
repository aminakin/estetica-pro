<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\Basket\BasketItemsResponse;
use ApiLib\Response\JsonResponseAbstract;
use Bitrix\Sale\Order;

class PaySystemIndexResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="PaySystemIndexShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="code", type="string"),
     *     @OA\Property(property="description", type="string"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'code' => $data['CODE'],
            'name' => $data['NAME'],
            'description' => $data['DESCRIPTION'],
        ];
    }
}