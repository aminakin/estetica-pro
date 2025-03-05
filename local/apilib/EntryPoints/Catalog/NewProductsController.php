<?php

namespace ApiLib\EntryPoints\Catalog;

use ApiLib\API\NewProduct\NewProductService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Catalog\ProductIndexResponse;
use ApiLib\Response\Catalog\ProductResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class NewProductsController extends AController
{
    private NewProductService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new NewProductService();
    }

    /**
     * Запрос получения списка товаров с фильтром, сортировкой и пагинацией
     *
     * @OA\Get (
     *     tags={"Товары из нового каталога"},
     *     path="/api/new_products",
     *     summary="Получение списка товаров",
     *     description="Получение списка товаров",
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
        // TODO: need validation and DTO
        $response = $this->service->index($request->getValues());

        return ProductIndexResponse::item($response);
    }
}
