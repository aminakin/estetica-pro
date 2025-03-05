<?php

namespace ApiLib\Responce\Iblock;

use ApiLib\Responce\JsonRecponceAbstract;

class ExampleRecponceResource extends JsonRecponceAbstract
{
    /**
     * Пример ответа в JSON b shema для swagger, свою очередь shema может использовать другую shema
     * например type="object", ref="#/components/schemas/ExapleItem"
     *
     * @OA\Schema (
     *     schema="ExampleShemaName",
     *     type="object",
     *      @OA\Property(property="id", type="integer", readOnly="true"),
     *      @OA\Property(property="name", type="string", readOnly="true", example="Имя"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => 1,
            'name' => $data['name']
        ];
    }
}