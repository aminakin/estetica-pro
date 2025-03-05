<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;

class StoreListResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="StoreListShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="title", type="string", readOnly="true"),
     *     @OA\Property(property="address", type="string", readOnly="true"),
     *     @OA\Property(property="description", type="string", readOnly="true"),
     *     @OA\Property(property="image_id", type="integer", readOnly="true"),
     *     @OA\Property(property="phone", type="string", readOnly="true"),
     *     @OA\Property(property="schedule", type="string", readOnly="true"),
     *     @OA\Property(property="gps_n", type="string", readOnly="true"),
     *     @OA\Property(property="gps_s", type="string", readOnly="true"),
     *     @OA\Property(property="issuing_center", type="string", readOnly="true"),
     *     @OA\Property(property="site_id", type="string", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'title' => $data['TITLE'],
            'address' => $data['ADDRESS'],
            'description' => $data['DESCRIPTION'],
            'image_id' => $data['IMAGE_ID'],
            'phone' => $data['PHONE'],
            'schedule' => $data['SCHEDULE'],
            'gps_n' => $data['GPS_N'],
            'gps_s' => $data['GPS_S'],
            'issuing_center' => $data['ISSUING_CENTER'],
            'site_id' => $data['SITE_ID'],
        ];
    }
}