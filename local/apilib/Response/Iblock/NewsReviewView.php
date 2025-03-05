<?php

namespace ApiLib\Response\Iblock;

use ApiLib\Response\JsonResponseAbstract;

class NewsReviewView extends JsonResponseAbstract
{
    /**
     * Ответ для комментариев
     *
     * @OA\Schema (
     *     schema="RewiewItem",
     *     type="object",
     *      @OA\Property(property="id", type="integer", readOnly="true"),
     *      @OA\Property(property="date", type="date", readOnly="true"),
     *      @OA\Property(property="name", type="string", readOnly="true", example="ФИО"),
     *      @OA\Property(property="imgSrc", type="string", readOnly="true", example="example.com/img.png"),
     *      @OA\Property(property="text", type="string", readOnly="true", example="Текст тестовой новости")
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'date' =>  FormatDate("x", MakeTimeStamp($data['TIMESTAMP_X'])),
            'name' => $data['NAME'],
            'imgSrc' => $data['PREVIEW_PICTURE'],
            'text' => $data['PREVIEW_TEXT']
        ];
    }
}