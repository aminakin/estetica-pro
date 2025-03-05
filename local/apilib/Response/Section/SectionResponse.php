<?php

namespace ApiLib\Response\Section;

use ApiLib\Response\JsonResponseAbstract;

class SectionResponse extends JsonResponseAbstract
{

    /**
     * @OA\Schema (
     *     schema="SectionShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true", example="1709"),
     *     @OA\Property(property="name", type="string", readOnly="true", example="Пакеты для стерилизации"),
     *     @OA\Property(property="picture", type="string", readOnly="true", example="/path/to/img"),
     *     @OA\Property(property="sort", type="integer", readOnly="true", example="150"),
     *     @OA\Property(property="description", type="string", readOnly="true", example="qwerg"),
     *     @OA\Property(property="description_type", type="string", readOnly="true", example="text"),
     *     @OA\Property(property="parent_id", type="integer", readOnly="true", example="1059"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'picture' => $data['PICTURE'],
            'sort' => $data['SORT'],
            'description' => $data['DESCRIPTION'],
            'description_type' => $data['DESCRIPTION_TYPE'],
            'parent_id' => $data['IBLOCK_SECTION_PARENT_SECTION_ID'],
        ];
    }
}