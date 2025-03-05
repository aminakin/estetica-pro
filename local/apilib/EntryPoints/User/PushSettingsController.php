<?php

namespace ApiLib\EntryPoints\User;

use ApiLib\API\User\PushSettingsService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\User\PushSettingsIndexResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class PushSettingsController extends AController
{
    private PushSettingsService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->service = new PushSettingsService();
    }

    /**
     *  Настройка push
     *
     * @OA\Get (
     *     tags={"Настройка push"},
     *     path="/api/my_account/push",
     *     summary="Cтатус push уведомления",
     *     description="Включены или нет push уведомления",
     *     security={ {"api_key": {}} },
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/PushSettingsStatusShema")
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found"),
     *     @OA\Response( response=400, description="Bad request",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      )
     *  )
     */
    public function index()
    {
        $response = $this->service->index();

        return PushSettingsIndexResponse::item($response);
    }

    /**
     * Настройка push изменение
     *
     * @OA\Put (
     *     tags={"Настройка push"},
     *     path="/api/my_account/push",
     *     summary="Настройка push изменение",
     *     description="Настройка push изменение",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="Value",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="Value",
     *                     property="value",
     *                     type="bool"
     *                 ),
     *                 required={"value"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/PushSettingsStatusShema")
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *     @OA\Response( response=400, description="Bad request",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      )
     *  )
     */
    public function update(HttpRequest $request)
    {
        $response = $this->service->update($request->getValues());

        return PushSettingsIndexResponse::item($response);
    }
}
