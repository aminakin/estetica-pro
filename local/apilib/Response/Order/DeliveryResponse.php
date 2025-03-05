<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;

class DeliveryResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="DeliveryShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true"),
     *     @OA\Property(property="own_name", type="string", readOnly="true"),
     *     @OA\Property(property="description", type="string", readOnly="true"),
     *     @OA\Property(property="field_name", type="string", readOnly="true"),
     *     @OA\Property(property="currency", type="string", readOnly="true"),
     *     @OA\Property(property="sort", type="integer", readOnly="true"),
     *     @OA\Property(property="extra_services", type="array", readOnly="true", example="[]"),
     *     @OA\Property(property="store", type="array", readOnly="true", example="[]"),
     *     @OA\Property(property="logotip", type="array", readOnly="true", example="[]"),
     *     @OA\Property(property="checked", type="string", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'own_name' => $data['OWN_NAME'],
            'description' => $data['DESCRIPTION'],
            'field_name' => $data['FIELD_NAME'],
            'currency' => $data['CURRENCY'],
            'sort' => $data['SORT'],
            'extra_services' => $data['EXTRA_SERVICES'],
            'store' => $data['STORE'],
            'logotip' => $data['LOGOTIP'],
            'checked' => $data['CHECKED'],
        ];
    }
}