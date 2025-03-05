<?php

namespace ApiLib\EntryPoints\Catalog;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Prop\PropService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Catalog\PropResponse;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Request;

class ProductPropsController extends AController
{
    private PropService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new PropService();
    }

    /**
     * @OA\Get (
     *     tags={"Свойства товара/предложения"},
     *     path="/api/products/{product_id}/props",
     *     summary="Получение информации о свойствах товара/предложения",
     *     description="Получение информации о свойствах товара/предложения",
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
     *              @OA\Items(type="object", ref="#/components/schemas/PropShema")
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
    public function index($productId)
    {

        return PropResponse::collection($this->service->getPropsValueByElements([$productId])[$productId]);
    }
}