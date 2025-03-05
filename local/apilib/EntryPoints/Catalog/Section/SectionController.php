<?php

namespace ApiLib\EntryPoints\Catalog\Section;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Section\SectionService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Section\SectionResponse;
use Bitrix\Main\Request;

class SectionController extends AController
{
    private SectionService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new SectionService();
    }

    /**
     * @OA\Get (
     *     tags={"Категории"},
     *     path="/api/sections/{parent_id}",
     *     summary="Получение списка категорий по родителю",
     *     description="Получение списка категорий по родителю",
     *     @OA\Parameter(
     *         description="Parent id",
     *         in="path",
     *         name="parent_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="1059",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *          @OA\JsonContent(type="array",
     *              @OA\Items(ref="#/components/schemas/SectionShema"),
     *          ),
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     */
    public function index(int $parent)
    {
        $sections = $this->service->index($parent);

        return SectionResponse::collection($sections);
    }

    /**
     * @OA\Get (
     *     tags={"Категории"},
     *     path="/api/sections/show/{parent}",
     *     summary="Получение категории",
     *     description="Получение категории",
     *     @OA\Parameter(
     *         description="Parent id",
     *         in="path",
     *         name="parent",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="1059",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *          @OA\JsonContent(type="object",
     *              ref="#/components/schemas/SectionShema",
     *          ),
     *      ),
     *      @OA\Response(response=401, description="Unauthorized"),
     *      @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(int $id)
    {
        $section = $this->service->show($id);

        return SectionResponse::item($section);
    }
}