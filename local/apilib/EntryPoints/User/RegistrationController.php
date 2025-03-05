<?php

namespace ApiLib\EntryPoints\User;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\API\User\RegistrationService;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;

class RegistrationController
{
    private RegistrationService $service;

    public function __construct()
    {
        $this->service = new RegistrationService();
    }

    /** @OA\Post (
     *     tags={"Регистрация"},
     *     path="/api/register",
     *     summary="Регистрация",
     *     description="Регистрация",
     *    @OA\RequestBody(
     *         description="Register",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="last name",
     *                     property="last_name",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="name",
     *                     property="name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="second name",
     *                     property="second_name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="email",
     *                     property="email",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="personal phone",
     *                     property="personal_phone",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="password",
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="confirm password",
     *                     property="confirm_password",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="user birthday day",
     *                     property="user_birthday_day",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="user birthday month",
     *                     property="user_birthday_month",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="user birthday year",
     *                     property="user_birthday_year",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     description="subscribes",
     *                     property="uf_subscribes",
     *                     type="boolean",
     *                 ),
     *                 @OA\Property(
     *                     description="personal agree",
     *                     property="uf_personal_agree",
     *                     type="boolean",
     *                 ),
     *                 required={"uf_personal_agree", "confirm_password", "password", "personal_phone", "email", "second_name", "name", "last_name"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="token", type="string", readOnly="true"),
     *      )
     *      ),
     *    @OA\Response(response=404, description="Not Found"),
     *     @OA\Response( response=400, description="Bad request",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      )
     *  )
     */
    public function store(HttpRequest $request)
    {
        $regResult = $this->service->store($request->getValues());

        if ($regResult === false || isset($regResult['ERRORS'])) {
            if ($regResult === false)
                $regResult['ERRORS'][] = '';

            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $regResult['ERRORS']], [['name' => 'Content-Type', 'value' => 'application/json; charset=UTF-8']]);
        }

        $user = new \CUser();
        if ($user->Login($regResult["VALUES"]["LOGIN"], $regResult["VALUES"]["PASSWORD"]) && $user->IsAuthorized()) {

            return ['token' => bitrix_sessid()];
        }

        AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => 'Unknown error'], [['name' => 'Content-Type', 'value' => 'application/json; charset=UTF-8']]);

        return '';
    }
}