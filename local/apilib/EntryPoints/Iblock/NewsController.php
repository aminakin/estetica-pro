<?php

namespace ApiLib\EntryPoints\Iblock;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Iblock\NewsService;
use ApiLib\API\Settings\BitrixSettings;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Iblock\NewsResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class NewsController extends AController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    /**
     * Запрос на получение денты новостей
     *
     * @OA\Get (
     *     tags={"Новости"},
     *     path="/api/news/index",
     *     summary="Получение ленты новостей",
     *     description="Получение ленты новостей",
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/NewsShema")
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
    public static function getNewsData(){
        $service = new NewsService();
        $response = $service->getNews();

        return NewsResponse::collection($response);
    }
}