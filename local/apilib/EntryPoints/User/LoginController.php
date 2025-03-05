<?php

namespace ApiLib\EntryPoints\User;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\HttpRequest;
use Bitrix\Rest\OAuth\Client;
use Bitrix\Rest\OAuth\Engine;

class LoginController extends AController
{
    /** @OA\Post (
     *     tags={"Аторизация"},
     *     path="/api/login",
     *     summary="Авторизация",
     *     description="Авторизация",
     *    @OA\RequestBody(
     *         description="Login",
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
     *                     description="password",
     *                     property="password",
     *                     type="string",
     *                 ),
     *                 required={"login", "password"}
     *             ),
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="token", type="string", readOnly="true"),
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
    public function store(HttpRequest $request)
    {
        $user = new \CUser();
        $result = $user->Login($request->get('login'), $request->get('password'));
        if ($result === true && $user->IsAuthorized()) {

            return ['token' => bitrix_sessid()];
        } elseif ($result === false) {
            AdvancedResponse::response(401, ['TYPE' => 'ERROR', 'MESSAGE' => 'Unknown error'], [['name' => 'Content-Type', 'value' => 'application/json']]);
        }
        else {
            AdvancedResponse::response(401, ['TYPE' => 'ERROR', 'MESSAGE' => $result], [['name' => 'Content-Type', 'value' => 'application/json']]);
        }
    }
}