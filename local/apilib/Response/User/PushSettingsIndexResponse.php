<?php

namespace ApiLib\Response\User;

use ApiLib\Response\JsonResponseAbstract;

class PushSettingsIndexResponse extends JsonResponseAbstract
{

    /***
     * @OA\Schema (
     *     schema="PushSettingsStatusShema",
     *     type="object",
     *     @OA\Property(property="value", type="bool", readOnly="true", example="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            "success" => "success",
            "value" => $data
        ];
    }
}
