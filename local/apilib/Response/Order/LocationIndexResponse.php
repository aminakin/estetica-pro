<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\Catalog\PaginationResponse;
use ApiLib\Response\JsonResponseAbstract;

class LocationIndexResponse extends JsonResponseAbstract
{

    public static function data($data): array
    {
        return [
            'items' => LocationResponse::collection($data['items']),
            'pagination' => PaginationResponse::item($data['pagination'])
        ];
    }
}