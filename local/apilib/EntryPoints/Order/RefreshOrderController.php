<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Order\OrderService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Order\OrderResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class RefreshOrderController extends AController
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
     * @OA\Post (
     *     tags={"Оформление заказа"},
     *     path="/api/orders/show",
     *     summary="Оформление заказа рефреш",
     *     description="Оформление заказа рефреш",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="Refresh order",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      description="Delivery Id",
     *                      property="delivery_id",
     *                      type="integer",
     *                      example="46",
     *                  ),
     *                  @OA\Property(
     *                      description="City Id",
     *                      property="order_prop_5",
     *                      type="string",
     *                      example="0000583577",
     *                  ),
     *                  @OA\Property(
     *                      description="Location type",
     *                      property="location_type",
     *                      type="string",
     *                      example="code",
     *                  ),
     *                  @OA\Property(
     *                      description="Last name",
     *                      property="order_prop_34",
     *                      type="string",
     *                      example="Фамилия",
     *                  ),
     *                  @OA\Property(
     *                      description="Name",
     *                      property="order_prop_35",
     *                      type="string",
     *                      example="Имя",
     *                  ),
     *                  @OA\Property(
     *                      description="Middle name",
     *                      property="order_prop_36",
     *                      type="string",
     *                      example="Отчество",
     *                  ),
     *                  @OA\Property(
     *                      description="If auth",
     *                      property="order_prop_43",
     *                      type="string",
     *                      example="Y",
     *                  ),
     *                  @OA\Property(
     *                      description="E-mail",
     *                      property="order_prop_2",
     *                      type="string",
     *                      example="mail@yandex.ru",
     *                  ),
     *                  @OA\Property(
     *                      description="Phone",
     *                      property="order_prop_3",
     *                      type="string",
     *                      example="89998887766",
     *                  ),
     *                  @OA\Property(
     *                      description="Person Type",
     *                      property="person_type",
     *                      type="integer",
     *                      example="1",
     *                  ),
     *                  @OA\Property(
     *                      description="Recent delivery value",
     *                      property="recent_delivery_value",
     *                      type="integer",
     *                      example="0000583577",
     *                  ),
     *                  @OA\Property(
     *                      description="Zip code",
     *                      property="order_prop_4",
     *                      type="string",
     *                      example="644044",
     *                  ),
     *                  @OA\Property(
     *                      description="Zip property changed",
     *                      property="zip_property_changed",
     *                      type="string",
     *                      example="644044",
     *                  ),
     *                  @OA\Property(
     *                      description="Street",
     *                      property="order_prop_6",
     *                      type="string",
     *                      example="Ленина",
     *                  ),
     *                  @OA\Property(
     *                      description="House",
     *                      property="order_prop_7",
     *                      type="string",
     *                      example="5",
     *                  ),
     *                  @OA\Property(
     *                      description="Appartment",
     *                      property="order_prop_9",
     *                      type="string",
     *                      example="12",
     *                  ),
     *                  @OA\Property(
     *                      description="Pay system id",
     *                      property="pay_system_id",
     *                      type="integer",
     *                      example="11",
     *                  ),
     *                  @OA\Property(
     *                      description="Order description",
     *                      property="order_description",
     *                      type="string",
     *                      example="",
     *                  ),
     *                  @OA\Property(
     *                      description="No call manager",
     *                      property="order_prop_42",
     *                      type="string",
     *                      example="N",
     *                  ),
     *                  required={"order_prop_2", "order_prop_3", "person_type", "order_prop_36", "order_prop_35", "order_prop_34", "location_type", "order_prop_5", "delivery_id", "order_prop_6", "order_prop_7", "pay_system_id"}
     *              ),
     *          ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/OrderShema")
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *     @OA\Response( response=400, description="Bad request",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      ),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function show(HttpRequest $request)
    {

        return OrderResponse::item($this->service->refresh($request));
    }


}