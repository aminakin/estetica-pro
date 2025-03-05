<?php

namespace ApiLib\EntryPoints\Prop;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Prop\PropService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Catalog\PropValueEnumResponse;
use Bitrix\Main\Request;

class PropEnumController  extends AController
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
     *     path="/api/props/{id}/enums",
     *     summary="Получение списка значений свойства",
     *     description="Получение списка значений свойства",
     *     @OA\Parameter(
     *         description="id",
     *         in="path",
     *         name="id",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example="129",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/PropValueEnumShema")),
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
    public function index($id):array
    {

        return PropValueEnumResponse::collection($this->service->getEnums($id));
    }
}