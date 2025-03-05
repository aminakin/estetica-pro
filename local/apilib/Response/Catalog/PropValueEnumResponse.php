<?php

namespace ApiLib\Response\Catalog;

use ApiLib\Response\JsonResponseAbstract;

class PropValueEnumResponse extends JsonResponseAbstract
{

    /**
     * @OA\Schema (
     *     schema="PropValueEnumShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true",),
     *     @OA\Property(property="value", type="string", readOnly="true", example="10-11",),
     *     @OA\Property(property="property_id", type="integer", readOnly="true", example="129",),
     *     @OA\Property(property="default", type="string", readOnly="true", example="N",),
     *     @OA\Property(property="sort", type="integer", readOnly="true", example="1600",),
     * )
     */
    public static function data($data): array
    {

        return [
            'id' => $data['ID'],
            'value' => $data['VALUE'],
            'property_id' => $data['PROPERTY_ID'],
            'default' => $data['DEF'],
            'sort' => $data['SORT'],
        ];
    }
}