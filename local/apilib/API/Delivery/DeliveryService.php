<?php

namespace ApiLib\API\Delivery;

use ApiLib\Response\Order\DeliveryIndexResponse;
use Bitrix\Main\HttpRequest;

class DeliveryService
{
    public function index(HttpRequest $request)
    {
        $params['filter'] = [
            'ACTIVE' => 'Y'
        ];
        if (!empty($request->get('delivery_id')))
            $params['filter']['ID'] = $request->get('delivery_id');

        $deliveries = \Bitrix\Sale\Delivery\Services\Manager::getList($params)->fetchAll();

        return DeliveryIndexResponse::collection($deliveries);
    }
}