<?php

namespace ApiLib\EntryPoints\User;

use ApiLib\API\User\ForgotPasswordService;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\Application;
use Bitrix\Main\Context;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class ForgotPasswdController extends AController
{

    private ForgotPasswordService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->service = new ForgotPasswordService();
    }

    /** @OA\Post (
     *     tags={"Восстановление пароля"},
     *     path="/api/forgotpasswd",
     *     summary="Восстановление пароля",
     *     description="Восстановление пароля",
     *    @OA\RequestBody(
     *         description="forgotpasswd",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="login",
     *                     property="login",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="email",
     *                     property="email",
     *                     type="string",
     *                 ),
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      ),
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

        return $this->service->store($request);
    }

    /** @OA\Post (
     *     tags={"Восстановление пароля"},
     *     path="/api/forgotpasswdchange",
     *     summary="Восстановление пароля смена пароля",
     *     description="Восстановление пароля смена пароля",
     *    @OA\RequestBody(
     *         description="forgotpasswdchange",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     description="login",
     *                     property="login",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     description="checkword",
     *                     property="checkword",
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
     *                 required={"login", "checkword", "password", "confirm_password"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *      ),
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

        return $this->service->update($request);
    }
}