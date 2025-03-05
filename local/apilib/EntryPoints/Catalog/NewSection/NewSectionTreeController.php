<?php

namespace ApiLib\EntryPoints\Catalog\NewSection;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\NewSection\NewSectionTreeService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Section\SectionTreeResponse;
use Bitrix\Main\Request;

class NewSectionTreeController extends AController
{
    private NewSectionTreeService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new NewSectionTreeService();
    }

    /**
     * @OA\Get (
     *     tags={"Новые Категории"},
     *     path="/api/new_sections/trees",
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
