<?php

namespace ApiLib\Response\Order\JsData;

use ApiLib\Response\JsonResponseAbstract;

class JsDataLogictimBonusArrPayBonusResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="JsDataLogictimBonusArrPayBonusShema",
     *     type="object",
     *     @OA\Property(property="product_id", type="integer"),
     *     @OA\Property(property="basket_id", type="integer"),
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="quantity", type="integer"),
     *     @OA\Property(property="base_price", type="integer"),
     *     @OA\Property(property="price", type="integer"),
     *     @OA\Property(property="discount_price", type="integer"),
     *     @OA\Property(property="price_position", type="integer"),
     * )
     */
    public static function data($data): array
    {
        return [
            'product_id' => $data['PRODUCT_ID'],
            'basket_id' => $data['BASKET_ID'],
            'name' => $data['NAME'],
            'quantity' => $data['QUANTITY'],
            'base_price' => $data['BASE_PRICE'],
            'price' => $data['PRICE'],
            'discount_price' => $data['DISCOUNT_PRICE'],
            'price_position' => $data['PRICE_POSITION'],
        ];
    }
}