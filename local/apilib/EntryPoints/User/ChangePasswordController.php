<?php

namespace ApiLib\EntryPoints\User;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\User\ChangePasswordService;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class ChangePasswordController extends AController
{
    private ChangePasswordService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->service = new ChangePasswordService();
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /** @OA\Post (
     *     tags={"Изменение пароля"},
     *     path="/api/changepassword",
     *     summary="Изменение пароля",
     *     description="Изменение пароля",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="change password",
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
     *                     description="current password",
     *                     property="current_password",
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
     *                 required={"login", "current_password", "password", "confirm_password"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *               @OA\Property(property="FIELD", type="string", readOnly="true"),
     *      )
     *      ),
     *     @OA\Response( response=400, description="Bad request",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *               @OA\Property(property="FIELD", type="string", readOnly="true"),
     *      )
     *      )
     *  )
     */
    public function update(HttpRequest $request)
    {
        return $this->service->update($request);
    }

}