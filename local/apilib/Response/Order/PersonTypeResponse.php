<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;

class PersonTypeResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="PersonTypeShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="lid", type="string", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true"),
     *     @OA\Property(property="code", type="string", readOnly="true"),
     *     @OA\Property(property="sort", type="integer", readOnly="true"),
     *     @OA\Property(property="active", type="string", readOnly="true"),
     *     @OA\Property(property="xml_id", type="string", readOnly="true"),
     *     @OA\Property(property="entity_registry_type", type="string", readOnly="true"),
     *     @OA\Property(property="checked", type="string", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'lid' => $data['LID'],
            'name' => $data['NAME'],
            'code' => $data['CODE'],
            'sort' => $data['SORT'],
            'active' => $data['ACTIVE'],
            'xml_id' => $data['XML_ID'],
            'entity_registry_type' => $data['ENTITY_REGISTRY_TYPE'],
            'checked' => $data['CHECKED'],
        ];
    }
}