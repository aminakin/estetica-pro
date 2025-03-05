<?php

namespace ApiLib\Response\Basket;

use ApiLib\Response\JsonResponseAbstract;

class BasketChangeResponse extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON добавление товара в корзину
     *
     * @OA\Schema (
     *     schema="BasketChangeShema",
     *     type="object",
     *     @OA\Property(property="success", type="string", readOnly="true", example="success"),
     *     @OA\Property(property="id", type="integer", readOnly="true", example="18887"),
     *     @OA\Property(property="message", type="mixed", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'success' => $data['success'],
            'id' => $data['id'],
            'message' => $data['message']
        ];
    }
}