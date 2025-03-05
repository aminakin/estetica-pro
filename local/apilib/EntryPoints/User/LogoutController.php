<?php

namespace ApiLib\EntryPoints\User;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\Request;


/**
 * @OA\SecurityScheme(
 *   type="apiKey",
 *   in="header",
 *   securityScheme="api_key",
 *   name="X-Bitrix-Csrf-Token"
 * )
 */
class LogoutController extends AController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    /** @OA\Delete (
     *     tags={"Выход"},
     *     path="/api/logout",
     *     summary="Выход",
     *     description="Выход",
     *     security={ {"api_key": {}} },
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="status", type="string", readOnly="true", example="success"),
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
    public function destroy()
    {
        $user = new \CUser();
        $user->Logout();

        return ['status' => 'success'];
    }
}