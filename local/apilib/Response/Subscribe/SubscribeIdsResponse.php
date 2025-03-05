<?php

namespace ApiLib\Response\Subscribe;

use ApiLib\Response\JsonResponseAbstract;

class SubscribeIdsResponse extends JsonResponseAbstract
{

    public static function data($data): array
    {
        return $data;
    }
}
