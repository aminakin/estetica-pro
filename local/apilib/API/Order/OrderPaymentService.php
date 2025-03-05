<?php

namespace ApiLib\API\Order;

use Bitrix\Main\Loader;

class OrderPaymentService
{
    public function show($orderId)
    {
        Loader::includeModule('sale');

        $order = \Bitrix\Sale\Order::load($orderId);
        /** @var \Bitrix\Sale\PaymentCollection $paymentCollection */
        $paymentCollection = $order->getPaymentCollection();

        /** @var \Bitrix\Sale\Payment $payment */
        if($payment = $paymentCollection->current()) {
            $service = \Bitrix\Sale\PaySystem\Manager::getObjectById($payment->getPaymentSystemId());
            $result = $service->initiatePay($payment, null, \Bitrix\Sale\PaySystem\BaseServiceHandler::STRING);

        }

        $ps = $service->getFieldsValues();
        $ps['LOGOTIP_SRC'] = \CFile::ResizeImageGet($ps['LOGOTIP'], ['width' => 100, 'height' => 100], BX_RESIZE_IMAGE_PROPORTIONAL_ALT);
        $ps['PAYMENT_URL'] = $result->getPaymentUrl();
        $ps['IS_PAID'] = $payment->isPaid();

        return $ps;
    }
}