<?php

namespace ApiLib\Response\Subscribe;

use ApiLib\Response\JsonResponseAbstract;

class SubscribeSuccessResponse extends JsonResponseAbstract
{
    /***
     * @OA\Schema (
     *     schema="SubscribeStoreSuccessShema",
     *     type="object",
     *     @OA\Property(property="success", type="string", readOnly="true", example="success"),
     *     @OA\Property(property="id", type="integer", readOnly="true", example="18887"),
     * )
     */
    public static function data($data): array
    {
        return [
            "success" => "success",
            "id" => $data,
        ];
    }
}
