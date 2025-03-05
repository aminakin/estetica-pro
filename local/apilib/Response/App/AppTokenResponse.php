<?php

namespace ApiLib\Response\App;

use ApiLib\Response\JsonResponseAbstract;

class AppTokenResponse extends JsonResponseAbstract
{

    public static function data($data): array
    {
        return [
            'status' => $data['status'],
            'user_token' => $data['DEVICE_TOKEN'],
            'device_id' => $data['DEVICE_ID'],
            'device_type' => $data['DEVICE_TYPE'],
            'date_auth' => $data['DATE_AUTH'],
            'user_id' => $data['USER_ID'],
        ];
    }
}
