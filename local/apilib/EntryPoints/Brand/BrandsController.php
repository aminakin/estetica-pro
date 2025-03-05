<?php

namespace ApiLib\EntryPoints\Brand;

use ApiLib\API\Brand\BrandService;
use ApiLib\API\Product\ProductService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Brand\BrandIndexResponse;
use Bitrix\Main\Request;

class BrandsController extends AController
{
    private BrandService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new BrandService();
    }

    /**
     * Запрос получения списка секций брендов
     *
     * @OA\Get (
     *     tags={"Бренды"},
     *     path="/api/brands",
     *     summary="Получение списка брендов",
     *     description="Получение списка брендов",
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/BrandsShema")
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

        return BrandIndexResponse::collection($response);
    }
}