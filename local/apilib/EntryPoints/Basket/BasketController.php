<?php

namespace ApiLib\EntryPoints\Basket;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Basket\BasketService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Basket\BasketChangeResponse;
use ApiLib\Response\Basket\BasketItemsResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class BasketController extends AController
{
    private BasketService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new BasketService();
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /**
     * Запрос на добавление товара в корзину
     *
     * @OA\Post (
     *     tags={"Корзина"},
     *     path="/api/baskets/store",
     *     summary="Добавление товара в корзину",
     *     description="Добавление товара в корзину",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="Login",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Product Id",
     *                     property="product_id",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     description="quantity",
     *                     property="quantity",
     *                     type="integer",
     *                 ),
     *                 required={"product_id", "quantity"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/BasketChangeShema")
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
    public function store(HttpRequest $request)
    {
        $response = $this->service->store($request->get('product_id'), $request->get('quantity'));

        return BasketChangeResponse::item($response);
    }

    /**
     * Запрос на получение товаров корзины
     *
     * @OA\Get (
     *     tags={"Корзина"},
     *     path="/api/baskets",
     *     summary="Получение товаров корзины",
     *     description="Получение товаров корзины",
     *     security={ {"api_key": {}} },
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="array",
     *          @OA\Items(type="object", ref="#/components/schemas/BasketItemsShema"))
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
    public function index()
    {
        $response = $this->service->index();

        return BasketItemsResponse::collection($response);
    }

    /**
     * @OA\Post (
     *     tags={"Корзина"},
     *     path="/api/baskets/update/{id}",
     *     summary="Изменение количества товара в корзину",
     *     description="Изменение количества товара в корзину",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="Login",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="quantity",
     *                     property="quantity",
     *                     type="integer",
     *                 ),
     *                 required={"quantity"}
     *             ),
     *         ),
     *     ),
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
     *      @OA\JsonContent(type="object", ref="#/components/schemas/BasketChangeShema")
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
    public function update(int $id, HttpRequest $request)
    {
        $response = $this->service->update($id, $request->get('quantity'));

        return BasketChangeResponse::item($response);
    }

    /**
     * @OA\Post (
     *     tags={"Корзина"},
     *     path="/api/baskets/destroy/{id}",
     *     summary="Удаление товара из корзины",
     *     description="Удаление товара из корзины",
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
     *      @OA\JsonContent(type="object", ref="#/components/schemas/BasketChangeShema")
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
        $response = $this->service->destroy($id);

        return BasketChangeResponse::item($response);
    }
}