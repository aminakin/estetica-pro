<?php

namespace ApiLib\Response\Order\Grid;

use ApiLib\Response\JsonResponseAbstract;

class GridResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="GridShema",
     *     type="object",
     *     @OA\Property(property="headers", type="array", example="[]"),
     *     @OA\Property(property="headers_hidden", type="array", example="[]"),
     *     @OA\Property(property="rows", type="array", @OA\Items(type="object", ref="#/components/schemas/GridItemShema")),
     * )
     */
    public static function data($data): array
    {
        return [
            'headers' => $data['HEADERS'],
            'headers_hidden' => $data['HEADERS_HIDDEN'],
            'rows' => GridItemResponse::collection($data['ROWS']),
        ];
    }
}