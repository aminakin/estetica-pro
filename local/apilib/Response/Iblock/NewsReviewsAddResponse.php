<?php

namespace ApiLib\Response\Iblock;

use ApiLib\Response\JsonResponseAbstract;

class NewsReviewsAddResponse extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON для метода создания коментариев
     *
     * @OA\Schema (
     *     schema="NewsReviewsAddShema",
     *     type="object",
     *      @OA\Property(property="id", type="integer", readOnly="true"),
     *
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => (int)$data->getId(),
        ];
    }
}