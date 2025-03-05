<?php

namespace ApiLib\Response\Order\OrderProp;

use ApiLib\Response\JsonResponseAbstract;

class PrintResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="PrintShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true"),
     *     @OA\Property(property="value", type="string", readOnly="true"),
     *     @OA\Property(property="show_group_name", type="string", readOnly="true")
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'value' => $data['VALUE'],
            'show_group_name' => $data['SHOW_GROUP_NAME'],
        ];
    }
}