<?php

namespace ApiLib\Response\Catalog;

use ApiLib\Response\JsonResponseAbstract;

class PropValueResponse extends JsonResponseAbstract
{

    /**
     * @OA\Schema (
     *     schema="PropValueShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="value", type="mix", readOnly="true", example="null",),
     *     @OA\Property(property="value_type", type="string", readOnly="true", example="text",),
     *     @OA\Property(property="value_enum", type="string", readOnly="true", example="1055",),
     *     @OA\Property(property="value_num", type="string", readOnly="true", example="1055.0000",),
     *     @OA\Property(property="description", type="string", readOnly="true", example="",),
     * )
     */
    public static function data($data): array
    {

        return [
            'id' => $data['ID'],
            'value' => $data['VALUE'],
            'value_type' => $data['VALUE_TYPE'],
            'value_enum' => $data['VALUE_ENUM'],
            'value_num' => $data['VALUE_NUM'],
            'description' => $data['DESCRIPTION'],
        ];
    }
}