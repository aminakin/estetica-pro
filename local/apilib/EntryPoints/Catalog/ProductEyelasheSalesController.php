<?php

namespace ApiLib\EntryPoints\Catalog;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Product\ProductService;
use ApiLib\API\Settings\BitrixSettings;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Catalog\ProductIndexResponse;
use ApiLib\Response\Catalog\ProductResponse;
use ApiLib\Response\Catalog\ProductFavoritesResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class ProductEyelasheSalesController extends AController
{
    private ProductService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new ProductService();
        $this->addCheckAccess('/api/products/favorite-toggle/{id}', AuthAccess::class)->checkAccess();
    }

    /**
     * Запрос получения списка товаров с фильтром, сортировкой и пагинацией
     *
     * @OA\Get (
     *     tags={"Товары"},
     *     path="/api/products/eyelashe_sales",
     *     summary="Получение списка товаров для ресниц со скидкой",
     *     description="Получение списка товаров для ресниц со скидкой",
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
        $request->set('prop-' . BitrixSettings::getApiVarByName('SALE_EYELASHE_PROP_ID'), BitrixSettings::getApiVarByName('SALE_EYELASHE_PROP_VALUE'));
        $response = $this->service->index($request->getValues());

        return ProductIndexResponse::item($response);
    }
}