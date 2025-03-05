<?php

namespace ApiLib\Response\Catalog;

use ApiLib\Response\JsonResponseAbstract;

class ProductIndexResponse extends JsonResponseAbstract
{

    public static function data($data): array
    {
        return [
            'items' => ProductResponse::collection($data['items']),
            'pagination' => PaginationResponse::item($data['pagination'])
        ];
    }
}