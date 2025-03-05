<?php

namespace ApiLib\Response\Bonuse;

use ApiLib\Response\Catalog\PropResponse;
use ApiLib\Response\JsonResponseAbstract;

class BonuseResponse extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON для истории бонусов
     *
     * @OA\Schema (
     *     schema="BonusesCardShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true", example="Списание баллов по заказу №9619"),
     *     @OA\Property(property="date_create", type="string", readOnly="true", example="2022-04-26T16:48:07+03:00"),
     *     @OA\Property(property="props", type="array", @OA\Items(type="object", ref="#/components/schemas/PropShema")),
     * )
     */
    public static function data($data): array
    {

        return [
            'id' => data_get($data, 'ID'),
            'name' => data_get($data, 'NAME'),
            'date_create' => data_get($data, 'DATE_CREATE'),
            'props' => PropResponse::collection($data['PROPS'])
        ];
    }
}