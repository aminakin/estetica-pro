<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Order\OrderService;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class OrderCanceledController extends AController
{
    private OrderService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
        $this->service = new OrderService();
    }

    /**
     *  Список заказов
     *
     * @OA\Get (
     *     tags={"Отмена заказа"},
     *     path="/api/orders/canceled/{order_id}",
     *     summary="Отмена заказа",
     *     description="Отмена заказа",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="Order canceled",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      description="order id",
     *                      property="order_id",
     *                      type="integer",
     *                  ),
     *                  required={"order_id"},
     *              ),
     *          ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *          @OA\Property(property="status", type="string", readOnly="true"),
     *      )
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found"),
     *     @OA\Response( response=400, description="Bad request",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      )
     * )
     */
    public function update(HttpRequest $request, $orderId)
    {
        return ['status' => $this->service->updateCanceled($request, $orderId)?'success':'fail'];
    }

}