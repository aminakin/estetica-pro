<?php

namespace ApiLib\EntryPoints\Catalog;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Product\ProductService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Catalog\OfferResponse;
use Bitrix\Main\Request;

class OffersController extends AController
{
    private $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new ProductService();
    }
    /**
     * @OA\Get (
     *     tags={"Предложения"},
     *     path="/api/products/{product_id}/offers",
     *     summary="Получение информации о предложениях товара",
     *     description="Получение информации о предложениях товара",
     *     @OA\Parameter(
     *         description="Product Id",
     *         in="path",
     *         name="product_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *          @OA\JsonContent(type="array",
     *              @OA\Items(type="object", ref="#/components/schemas/OfferShema"),
     *          )
     *     ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function index(int $productId)
    {

        return OfferResponse::collection($this->service->getProductSkuList($productId));
    }
}