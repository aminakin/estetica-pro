<?php

namespace ApiLib\Response\Catalog;

use ApiLib\Response\JsonResponseAbstract;

class PropResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="PropShema",
     *     type="object",
     *      @OA\Property(property="id", type="integer", readOnly="true"),
     *      @OA\Property(property="name", type="string", readOnly="true", example="Максимальная цена"),
     *      @OA\Property(property="code", type="string", readOnly="true", example="MAXIMUM_PRICE"),
     *      @OA\Property(property="type", type="string", readOnly="true", example="N"),
     *      @OA\Property(property="values", type="array", @OA\Items(type="object", ref="#/components/schemas/PropValueShema")),
     *
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'code' => $data['CODE'],
            'type' => $data['PROPERTY_TYPE'],
            'values' => $data['PROPERTY_TYPE'] === 'F' ? PropFileResponse::collection($data['VALUES']) : PropValueResponse::collection($data['VALUES']),
        ];
    }
}