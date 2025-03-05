<?php

namespace ApiLib\Response\Iblock;

use ApiLib\Response\JsonResponseAbstract;

class SliderResponseResource extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON для слайдера
     *
     * @OA\Schema (
     *     schema="SliderShema",
     *     type="object",
     *      @OA\Property(property="id", type="integer", readOnly="true"),
     *      @OA\Property(property="link", type="string", readOnly="true", example="example.com/"),
     *      @OA\Property(property="imgSrc", type="string", readOnly="true", example="example.com/img.png"),
     *      @OA\Property(property="activeFrom", type="string", readOnly="true", example="2017-09-18 11:45:11"),
     *      @OA\Property(property="activeTo", type="string", readOnly="true", example="2017-09-18 11:45:11"),
     *      @OA\Property(property="sort", type="integer", readOnly="true", example="500"),
     *
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => (int)$data['ID'],
            'link' => $data['LINK_VALUE'],
            'imgSrc' => $data['PREVIEW_PICTURE'],
            'activeFrom' => $data['ACTIVE_FROM'],
            'activeTo' => $data['ACTIVE_TO'],
            'sort' => $data['SORT'],
        ];
    }
}
