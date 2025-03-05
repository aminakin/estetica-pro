<?php

namespace ApiLib\API\Payment;

use ApiLib\Response\Order\PaySystemIndexResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Sale\PaySystem\Manager;

class PaymentService
{
    public function index(HttpRequest $request)
    {
        $params['filter'] = [
            'ACTIVE' => 'Y'
        ];
        if (!empty($request->get('payment_id')))
            $params['filter']['ID'] = $request->get('payment_id');

        $payments = Manager::getList($params)->fetchAll();

        return PaySystemIndexResponse::collection($payments);
    }
}