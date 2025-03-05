<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Order\OrderService;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;
use Bitrix\Sale\PaySystem\BaseServiceHandler;
use ApiLib\API\PaySystem\ServiceService;
use Bitrix\Sale\Order;

class OrderPaymentLinkController extends AController
{

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
        $this->service = new OrderService();
    }

    /**
     * Запрос получения ссылки для оплаты
     *
     * @OA\Get (
     *     tags={"Ссылка на оплату заказа"},
     *     path="/api/orders/payment_link/{order_id}",
     *     summary="Получение ссылки для оплаты",
     *     description="Получение ссылки для оплаты",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="Order Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *          @OA\JsonContent(type="object",
     *               @OA\Property(property="link", type="string", readOnly="true"),
     *          ),
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not found",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *  )
     */
    public function show(HttpRequest $request, $order_id) {
        \CModule::IncludeModule('sale');
        \CModule::IncludeModule('sberbank.ecom2');

        $order = Order::load($order_id);
        $paymentCollection = $order->getPaymentCollection();
        /** @var \Bitrix\Sale\Payment $payment */
        foreach ($paymentCollection as $payment) {
            $payArray = $payment->toArray();

            $payArray['ID'] = $payArray['PAY_SYSTEM_ID'];
            if ($payArray['ID'] != 8)
                AdvancedResponse::response(404, ['TYPE' => 'ERROR', 'MESSAGE' => 'Payment link not fount'],  [['name' => 'Content-Type', 'value' => 'application/json']]);

            $payArray['ACTION_FILE'] = 'sberbank_ecom2';
            $service = new ServiceService($payArray);

            return ['link' => $service->initiatePay($payment, null, BaseServiceHandler::STRING)];
        }

        AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => 'unknown error'],  [['name' => 'Content-Type', 'value' => 'application/json']]);
    }
}