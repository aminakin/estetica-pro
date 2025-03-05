<?php

namespace ApiLib\EntryPoints\Order;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Payment\PaymentService;
use ApiLib\EntryPoints\AController;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Loader;
use Bitrix\Main\Request;

class PaymentController extends AController
{
    private PaymentService $service;

    public function __construct(Request $request = null)
    {
        Loader::includeModule('sale');
        $this->service = new PaymentService();
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /** @OA\Get (
     *     tags={"Платежные систем список"},
     *     path="/api/orders/payments",
     *     summary="Платежные систем список",
     *     description="Платежные систем список",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="payment id",
     *         in="query",
     *         name="payment_id",
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="array",
     *           @OA\Items(ref="#/components/schemas/PaySystemIndexShema"),
     *     ),
     *    ),
     *    @OA\Response(response=401, description="Unauthorized",
     *      @OA\JsonContent(type="object",
     *               @OA\Property(property="MESSAGE", type="array", example="[] | string", readOnly="true"),
     *               @OA\Property(property="TYPE", type="string", readOnly="true"),
     *      )
     *    ),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function index(HttpRequest $request)
    {

        return $this->service->index($request);
    }
}