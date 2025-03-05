<?php

namespace ApiLib\Response\Bonuse;

use ApiLib\Response\Catalog\PaginationResponse;
use ApiLib\Response\JsonResponseAbstract;

class BonuseIndexResponse extends JsonResponseAbstract
{

    public static function data($data): array
    {
        return [
            'items' => BonuseResponse::collection($data['ITEMS']),
            'pagination' => PaginationResponse::item($data['pagination']),
            'lgb_subscribe' => data_get($data, 'UF_LGB_SUBSCRIBE'),
            'user_bonus' => data_get($data, 'USER_BONUS'),
            'ref_link' => data_get($data, 'REF_LINK'),
            'coupon' => data_get($data, 'COUPON'),
            'view_ref_link' => data_get($data, 'VIEW_REF_LINK'),
            'view_ref_coupon' => data_get($data, 'VIEW_REF_COUPON'),
            'view_subscribe' => data_get($data, 'VIEW_SUBSCRIBE'),
        ];
    }
}