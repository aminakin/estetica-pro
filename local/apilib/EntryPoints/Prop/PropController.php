<?php

namespace ApiLib\EntryPoints\Prop;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Prop\PropService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Catalog\PropResponse;
use Bitrix\Main\Request;

class PropController  extends AController
{
    private PropService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new PropService();
    }

    /**
     * @OA\Get (
     *     tags={"Свойства"},
     *     path="/api/props/iblocks/{iblock_id}",
     *     summary="Получение списка свойств",
     *     description="Получение списка свойств",
     *     @OA\Parameter(
     *         description="Iblock Id",
     *         in="path",
     *         name="iblock_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="14",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PropShema")),
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
    public function index($iblock): array
    {
        $props = $this->service->getProps(['iblock' => $iblock], true);

        return PropResponse::collection($props);
    }

    /**
     * @OA\Get (
     *     tags={"Свойства"},
     *     path="/api/props/iblocks/{iblock_id}/show/{id}",
     *     summary="Получение свойства",
     *     description="Получение свойства",
     *     @OA\Parameter(
     *         description="Iblock Id",
     *         in="path",
     *         name="iblock_id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="14",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="id",
     *         in="path",
     *         name="id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="135",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/PropShema"),
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
    public function show($iblock, $id): array
    {
        $prop = $this->service->getProps(['iblock' => $iblock, 'propsIds' => $id]);

        return PropResponse::item(current($prop));
    }
}