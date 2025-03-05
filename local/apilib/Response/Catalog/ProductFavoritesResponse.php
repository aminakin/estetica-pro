<?php

namespace ApiLib\Response\Catalog;

use ApiLib\Response\JsonResponseAbstract;

class ProductFavoritesResponse extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON для избранного товара
     *
     * @OA\Schema (
     *     schema="ProductsFavoritesShema",
     *     type="object",
     *     @OA\Property(property="success", type="boolean", readOnly="true"),
     *     @OA\Property(property="message", type="string", readOnly="true", example="Продукт №123 добавлен в избранное!"),
     * )
     */
    public static function data($data): array
    {
        return [
            'success' => $data['success'],
            'message' => $data['message']
        ];
    }
}