<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Order\OrderService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Order\OrderResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class VirtualOrderController extends AController
{
    private OrderService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
        $this->service = new OrderService();
    }

    /**
     *  Оформление заказа
     *
     * @OA\Get (
     *     tags={"Оформление заказа"},
     *     path="/api/orders/show",
     *     summary="Оформление заказа начальная страница",
     *     description="Оформление заказа начальная страница",
     *     security={ {"api_key": {}} },
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/OrderShema")
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
     *  )
     */
    public function show(HttpRequest $request)
    {

        return OrderResponse::item($this->service->show($request));
    }
}