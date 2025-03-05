<?php

namespace ApiLib\EntryPoints\App;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\App\AppTokenService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\App\AppTokenResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class AppTokenController extends AController
{
    private  AppTokenService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->service = new AppTokenService();
    }

    /**
     * Заносив ID устройства и токен в БД
     *
     * @OA\Post (
     *     tags={"Токен для уведомений"},
     *     path="/api/app_token",
     *     summary="Добавляем токен для уведомлений",
     *     description="Добавляем токен для уведомлений",
     *    @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="User token",
     *                     property="user_token",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="device id",
     *                     property="device_id",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="Device type",
     *                     property="device_type",
     *                     type="string",
     *                 ),
     *                 required={"user_token", "device_id", "device_type"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *          @OA\Property(property="status", type="string", example="updated|failed|registered"),
     *          @OA\Property(property="user_token", type="string", example="740f4707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bb78ad"),
     *          @OA\Property(property="device_id", type="string", example="740f4707bebcf74f9b7c25d48e3358945f6aa01da5ddb387462c7eaf61bb78ad"),
     *          @OA\Property(property="device_type", type="string", example="APPLE"),
     *          @OA\Property(property="date_auth", type="string", example="11.04.2023 12:56:19"),
     *          @OA\Property(property="user_id", type="integer", example="44"),
     *     ),
     *    )
     *  )
     */
    public function store(HttpRequest $request)
    {
        $response = $this->service->store($request);

        return AppTokenResponse::item($response);
    }
}
