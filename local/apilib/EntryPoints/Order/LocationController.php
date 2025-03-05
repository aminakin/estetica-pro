<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Order\LocationService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Order\LocationIndexResponse;
use ApiLib\Response\Order\LocationResponse;
use Bitrix\Main\Context;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class LocationController extends AController
{
    private LocationService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new LocationService();
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /** @OA\Get (
     *     tags={"Оформление заказа"},
     *     path="/api/orders/locations",
     *     summary="Отбор местоположения",
     *     description="Отбор местоположения",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="Search string",
     *         in="query",
     *         name="q",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="id",
     *         in="query",
     *         name="id",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="code",
     *         in="query",
     *         name="code",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Limit",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Offset",
     *         in="query",
     *         name="offset",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *          @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/LocationShema")),
     *          @OA\Property(property="pagination", ref="#/components/schemas/PaginationShema"),
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
        return LocationIndexResponse::item($this->service->index($request));
    }
}