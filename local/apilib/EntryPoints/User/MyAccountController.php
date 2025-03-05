<?php

namespace ApiLib\EntryPoints\User;

use ApiLib\AccessClass\AdvancedResponse;
use ApiLib\EntryPoints\AController;
use ApiLib\API\User\MyAccountService;
use ApiLib\AccessClass\AuthAccess;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class MyAccountController extends AController
{

    private MyAccountService $service;

    public function __construct(Request $request = null)
    {
        parent::__construct($request);

        $this->service = new MyAccountService();
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /**
     *  Мой аккаунт
     *
     * @OA\Get (
     *     tags={"Аккаунт"},
     *     path="/api/my_account",
     *     summary="Аккаунт",
     *     description="Аккаунт",
     *     security={ {"api_key": {}} },
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/AccountShema")
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show()
    {
        return $this->service->show();
    }

    /**
     *  Мой аккаунт
     *
     * @OA\Put (
     *     tags={"Аккаунт"},
     *     path="/api/my_account",
     *     summary="Аккаунт изменить",
     *     description="Аккаунт изменить",
     *     security={ {"api_key": {}} },
     *    @OA\RequestBody(
     *         description="Account",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      description="name",
     *                      property="name",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      description="last_name",
     *                      property="last_name",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      description="second_name",
     *                      property="second_name",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      description="email",
     *                      property="email",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      description="phone",
     *                      property="phone",
     *                      type="string",
     *                  ),
     *                  @OA\Property(
     *                      description="birthday",
     *                      property="birthday",
     *                      type="string",
     *                      example="17.01.1989",
     *                  ),
     *                  required={"filter_status", "filter_canceled"},
     *              ),
     *          ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/AccountShema")
     *      ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found")
     * )
     */
    public function update(HttpRequest $request)
    {
        $result = $this->service->update($request->getValues());
        if (!$result[0])
        {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result[1]], [['name' => 'Content-Type', 'value' => 'application/json; charset=UTF-8']]);
        }

        return $this->show();
    }

    /**
     *  Мой аккаунт
     *
     * @OA\Delete  (
     *     tags={"Аккаунт"},
     *     path="/api/my_account",
     *     summary="Аккаунт удалить",
     *     description="Аккаунт удалить",
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
     * )
     */
    public function destroy()
    {
        global $USER;
        if ($this->service->destroy($USER->GetID()))
            return ['status' => 'success'];

        return ['status' => 'fail'];
    }
}