<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Delivery\DeliveryService;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Main\Request;

class DeliveryController extends AController
{
    private DeliveryService $service;

    public function __construct(Request $request = null)
    {
        Loader::includeModule('sale');
        $this->service = new DeliveryService();
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /** @OA\Get (
     *     tags={"Доставка список"},
     *     path="/api/orders/deliveries",
     *     summary="Доставка список",
     *     description="Доставка список",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="delivery id",
     *         in="query",
     *         name="delivery_id",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="array",
     *           @OA\Items(ref="#/components/schemas/DeliveryIndexShema"),
     *     ),
     *    ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function index(HttpRequest $request)
    {

        return $this->service->index($request);
    }
}