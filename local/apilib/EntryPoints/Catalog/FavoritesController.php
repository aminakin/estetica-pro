<?php

namespace ApiLib\EntryPoints\Catalog;

use ApiLib\API\Product\ProductService;
use ApiLib\Response\Catalog\ProductFavoritesResponse;
use ApiLib\Response\Catalog\ProductResponse;
use Bitrix\Main\HttpRequest;

class FavoritesController
{
    private ProductService $service;

    public function __construct()
    {
        $this->service = new ProductService();
    }

    /**
     * Запрос получения списка товаров в избранном
     *
     * @OA\Get (
     *     tags={"Товары в избранном"},
     *     path="/api/products/favorites",
     *     summary="Получение списка товаров в избранном",
     *     description="Получение списка товаров в избранном",
     *     @OA\Parameter(
     *         description="Section Id",
     *         in="path",
     *         name="section_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="1069",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Prop Id",
     *         in="path",
     *         name="prop-135",
     *         required=false,
     *         @OA\Schema(
     *             type="mix",
     *             example="364",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Prop Id array values",
     *         in="path",
     *         name="prop-128[]",
     *         required=false,
     *         @OA\Schema(
     *             type="mix",
     *             example="prop-128[]=312&prop-128[]=311",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Prop Id gt ge lt le ne",
     *         in="path",
     *         name="prop-226-gt",
     *         required=false,
     *         @OA\Schema(
     *             type="mix",
     *             example="1000",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Sort",
     *         in="path",
     *         name="sort",
     *         required=false,
     *         @OA\Schema(
     *             type="mix",
     *             example="prop-117",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Limit",
     *         in="path",
     *         name="limit",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="30",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="Offset",
     *         in="path",
     *         name="offset",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="0",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *          @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/ProductsCardShema")),
     *          @OA\Property(property="pagination", ref="#/components/schemas/PaginationShema"),
     *     ),
     *      ),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function index(HttpRequest $request)
    {
        $data = $this->service->favoriteIndex($request);
        return ProductResponse::collection($data['items']);
    }

    /**
     *   Добавление в избранное
     *
     * @OA\Get (
     *     tags={"Товары"},
     *     path="/api/products/favorite-toggle/{id}",
     *     summary="Добавление в товара в избранное",
     *     description="Добавление в товара в избранное",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="Product Id",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example="49861",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/ProductsFavoritesShema")
     *      ),
     *    @OA\Response(response=404, description="Not Found"),
     *     @OA\Response( response=400, description="Bad request",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      ),
     *
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *  )
     */
    public function toggleProductFavorite(int $id){
        $response = $this->service->toggleFavorites($id);

        return ProductFavoritesResponse::item($response);
    }

    /**
     * Список id товаров в избранном
     *
     * @OA\Get (
     *     tags={"Список id товаров в избранном"},
     *     path="/api/products/favorites/ids",
     *     summary="Список id товаров в избранном",
     *     description="Список id товаров в избранном",
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="array", example="[111,222]"),
     *      ),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function indexIds()
    {
        return $this->service->favoriteIndexIds();
    }
}
