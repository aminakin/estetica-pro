<?php

namespace ApiLib\Response\Catalog;

use ApiLib\Response\JsonResponseAbstract;

class PaginationResponse extends JsonResponseAbstract
{

    /**
     * @OA\Schema (
     *     schema="PaginationShema",
     *     type="object",
     *     @OA\Property(property="limit", type="integer", readOnly="true", example="30"),
     *     @OA\Property(property="offset", type="integer", readOnly="true", example="0"),
     *     @OA\Property(property="count", type="integer", readOnly="true", example="769"),
     * )
     */
    public static function data($data): array
    {
        return [
            'limit' => $data['limit'],
            'offset' => $data['offset'],
            'count' => $data['count'],
        ];
    }
}