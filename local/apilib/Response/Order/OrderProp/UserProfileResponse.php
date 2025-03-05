<?php

namespace ApiLib\Response\Order\OrderProp;

use ApiLib\Response\JsonResponseAbstract;

class UserProfileResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="UserProfileShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true"),
     *     @OA\Property(property="user_id", type="integer", readOnly="true"),
     *     @OA\Property(property="personal_type_id", type="integer", readOnly="true"),
     *     @OA\Property(property="date_update", type="string", readOnly="true"),
     *     @OA\Property(property="date_update_raw", type="string", readOnly="true"),
     *     @OA\Property(property="checked", type="string", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'user_id' => $data['USER_ID'],
            'personal_type_id' => $data['PERSON_TYPE_ID'],
            'date_update' => $data['DATE_UPDATE'],
            'date_update_raw' => $data['DATE_UPDATE_RAW'],
            'checked' => $data['CHECKED'],
        ];
    }
}