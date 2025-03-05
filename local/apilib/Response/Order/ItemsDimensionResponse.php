<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;

class ItemsDimensionResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="ItemsDimensionShema",
     *     type="object",
     *     @OA\Property(property="width", type="integer", readOnly="true"),
     *     @OA\Property(property="height", type="integer", readOnly="true"),
     *     @OA\Property(property="length", type="integer", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'width' => $data['WIDTH'],
            'height' => $data['HEIGHT'],
            'length' => $data['LENGTH'],
        ];
    }
}