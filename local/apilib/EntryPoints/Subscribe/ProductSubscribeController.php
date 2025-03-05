<?php

namespace ApiLib\EntryPoints\Subscribe;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Subscribe\SubscribeService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Catalog\ProductIndexResponse;
use ApiLib\Response\Subscribe\SubscribeIdsResponse;
use ApiLib\Response\Subscribe\SubscribeSuccessResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class ProductSubscribeController extends AController
{
    private SubscribeService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
        $this->service = new SubscribeService();
    }

    /**
     *  Список подписок
     *
     * @OA\Get (
     *     tags={"Подписки на товары"},
     *     path="/api/products/subscribe",
     *     summary="Список подписок пользователя",
     *     description="Список подписок пользователя",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="Limit",
     *         in="query",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="30",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Offset",
     *         in="query",
     *         name="offset",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="0",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/ProductsCardShema")
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
    public function index(HttpRequest $request)
    {
        $response = $this->service->index($request->getValues());

        return ProductIndexResponse::item($response);
    }

    /**
     *  Список подписок ids
     *
     * @OA\Get (
     *     tags={"Подписки на товары"},
     *     path="/api/products/subscribe/ids",
     *     summary="Список подписок пользователя ids",
     *     description="Список подписок пользователя ids",
     *     security={ {"api_key": {}} },
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="array")
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
    public function ids()
    {
        $response = $this->service->ids();

        return SubscribeIdsResponse::item($response);
    }

    /**
     * Запрос на добавление подписки на товар
     *
     * @OA\Post (
     *     tags={"Подписки на товары"},
     *     path="/api/products/subscribe",
     *     summary="Добавление подписки на товар",
     *     description="Добавление подписки на товар",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="Product id",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Product Id",
     *                     property="product_id",
     *                     type="integer"
     *                 ),
     *                 required={"product_id"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/SubscribeStoreSuccessShema")
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
     *      )
     *  )
     */
    public function store(HttpRequest $request)
    {
        $response = $this->service->store($request->toArray());

        if (is_array($response)) {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $response['message']],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return SubscribeSuccessResponse::item($response);
    }

    /**
     * @OA\Delete  (
     *     tags={"Подписки на товары"},
     *     path="/api/products/subscribe/{id}",
     *     summary="Удаление подписки на товар",
     *     description="Удаление подписки на товар",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/SubscribeStoreSuccessShema")
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
    public function destroy(int $id)
    {
        $response = $this->service->delete($id);

        if (is_array($response)) {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $response['message']],  [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return SubscribeSuccessResponse::item($id);
    }
}
