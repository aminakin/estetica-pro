<?php

namespace ApiLib\EntryPoints\Bonuse;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Bonuse\BonuseService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Bonuse\BonuseIndexResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class BonusesController extends AController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
        $this->service = new BonuseService();
    }

    /**
     * Запрос получения списка истории бонусов
     *
     * @OA\Get (
     *     tags={"Бонусы"},
     *     path="/api/bonuses",
     *     summary="Получение списка истории бонусов",
     *     description="Получение списка истории бонусов",
     *     security={ {"api_key": {}} },
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
     *          @OA\Property(property="items", type="array", @OA\Items(ref="#/components/schemas/BonusesCardShema")),
     *          @OA\Property(property="pagination", ref="#/components/schemas/PaginationShema"),
     *          @OA\Property(property="lgb_subscribe", type="integer", example="1"),
     *          @OA\Property(property="user_bonus", type="integer", example="206"),
     *          @OA\Property(property="ref_link", type="string", example="http://localhost:8080/?ref=2888"),
     *          @OA\Property(property="coupon", type="string", example=""),
     *          @OA\Property(property="view_ref_link", type="string", example="N"),
     *          @OA\Property(property="view_ref_coupon", type="string", example="N"),
     *          @OA\Property(property="view_subscribe", type="string", example="Y"),
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
    public function index(HttpRequest $request) {
        $response = $this->service->index($request->toArray());

        return BonuseIndexResponse::item($response);
    }

    /**
     * Запрос получения текущих бонусов
     *
     * @OA\Get (
     *     tags={"Бонусы"},
     *     path="/api/bonuses/show",
     *     summary="Текущие бонусы",
     *     description="Текущие бонусы",
     *     security={ {"api_key": {}} },
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *           @OA\Property(property="bonus", type="integer", example="206"),
     *           @OA\Property(property="bonus_cart", type="string", example="8000000010011"),
     *           @OA\Property(property="next_level", type="integer", example="40000"),
     *           @OA\Property(property="purchvolume", type="integer", example="35664"),
     *           @OA\Property(property="curpercent", type="integer", example="5"),
     *      )
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
    public function show() {
        return $this->service->show();
    }
}