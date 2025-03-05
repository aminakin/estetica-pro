<?php

namespace ApiLib\EntryPoints\Catalog\Section;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Section\SectionTreeService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Section\SectionTreeResponse;
use Bitrix\Main\Request;

class SectionTreeController extends AController
{
    private SectionTreeService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new SectionTreeService();
    }

    /**
     * @OA\Get (
     *     tags={"Категории"},
     *     path="/api/sections/trees",
     *     summary="Получение дерева категорий",
     *     description="Получение дерева категорий",
     *     @OA\Response( response=200, description="OK",
     *          @OA\JsonContent(type="array",
     *              @OA\Items(ref="#/components/schemas/SectionTreeShema"
     *              ),
     *          ),
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     */
    public function index()
    {
        $sections = $this->service->index();

        return SectionTreeResponse::collection($sections);
    }
}