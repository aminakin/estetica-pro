<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Order\OrderPaymentService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Order\OrderPaymentResponse;
use Bitrix\Main\Request;

class OrderPaymentController extends AController
{
    private OrderPaymentService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->service = new OrderPaymentService();
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /** @OA\Get (
     *     tags={"Оплата заказа"},
     *     path="/api/orders/payments/{order_id}",
     *     summary="Оплата заказа",
     *     description="Оплата заказа",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="order id",
     *         in="path",
     *         name="order_id",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/OrderPaymenShema")
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function show($orderId)
    {
        return OrderPaymentResponse::item($this->service->show($orderId));
    }
}