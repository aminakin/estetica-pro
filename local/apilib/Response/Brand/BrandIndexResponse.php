<?php

namespace ApiLib\Response\Brand;

use ApiLib\Response\Catalog\PropResponse;
use ApiLib\Response\JsonResponseAbstract;

class BrandIndexResponse extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON для бренда
     *
     * @OA\Schema (
     *     schema="BrandsShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true", example="name"),
     *     @OA\Property(property="code", type="string", readOnly="true", example="code"),
     *     @OA\Property(property="props", type="array", @OA\Items(type="object", ref="#/components/schemas/PropShema")),
     *     @OA\Property(property="activeFrom", type="string", readOnly="true", example="2017-09-18 11:45:11"),
     *     @OA\Property(property="activeTo", type="string", readOnly="true", example="2017-09-18 11:45:11"),
     *     @OA\Property(property="sort", type="integer", readOnly="true", example="500"),
     *
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'code' => $data['CODE'],
            'props' => PropResponse::collection($data['PROPS']),
            'activeFrom' => $data['ACTIVE_FROM'],
            'activeTo' => $data['ACTIVE_TO'],
            'sort' => $data['SORT'],
        ];
    }
}
