<?php

namespace ApiLib\Response\Basket;

use ApiLib\Response\JsonResponseAbstract;

class BasketItemsResponse extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON товаровв в корзине
     *
     * @OA\Schema (
     *     schema="BasketItemsShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true", example="Ресницы Extreme Look чёрные 18 линий (изгиб LD)"),
     *     @OA\Property(property="quantity", type="float", readOnly="true", example="1.0000"),
     *     @OA\Property(property="max_quantity", type="float", readOnly="true", example="1.0000"),
     *     @OA\Property(property="price", type="float", readOnly="true", example="790"),
     *     @OA\Property(property="base_price", type="float", readOnly="true", example="990"),
     *     @OA\Property(property="discount_price", type="float", readOnly="true", example="200"),
     *     @OA\Property(property="product_id", type="integer", readOnly="true", example="38913"),
     *     @OA\Property(property="delay", type="string", readOnly="true", example="N"),
     *     @OA\Property(property="can_buy", type="string", readOnly="true", example="Y"),
     *     @OA\Property(property="weight", type="float", readOnly="true", example="0.00"),
     *     @OA\Property(property="notes", type="string", readOnly="true", example="Цена"),
     * )
     */
    public static function data($data): array
    {

        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'quantity' => (float)$data['QUANTITY'],
            'max_quantity' => (float)$data['max_quantity'],
            'price' => $data['PRICE'],
            'base_price' => $data['BASE_PRICE'],
            'discount_price' => $data['DISCOUNT_PRICE'],
            'product_id' => $data['PRODUCT_ID'],
            'delay' => $data['DELAY'],
            'can_buy' => $data['CAN_BUY'],
            'weight' => $data['WEIGHT'],
            'notes' => $data['NOTES'],
            'preview_src' => $data['preview_src'],
        ];
    }
}