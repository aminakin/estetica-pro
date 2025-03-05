<?php

namespace ApiLib\EntryPoints\Iblock;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Iblock\NewsReviewsService;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Iblock\NewsReviewsAddResponse;
use Bitrix\Main\HttpRequest;
use Bitrix\Main\Request;

class NewsReviewsController extends AController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
        $this->addCheckAccess('all', AuthAccess::class)->checkAccess('all');
    }

    /**
     * Запрос на создание комментария к новости
     *
     * @OA\Post (
     *     tags={"Новости"},
     *     path="/api/news/add-review",
     *     summary="Добавление коментария к новости",
     *     description="Добавление коментария к новости",
     *     security={ {"api_key": {}} },
     *     @OA\Parameter(
     *         description="news_id",
     *         in="query",
     *         name="news_id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="comment_title",
     *         in="query",
     *         name="comment_title",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Parameter(
     *         description="comment_text",
     *         in="query",
     *         name="comment_text",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/NewsReviewsAddShema")
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
    public static function addNewsReviewData(HttpRequest $request){
        $service = new NewsReviewsService(['linkPropId' => 325]);
        $response = $service->addNewsReview($request);

        return NewsReviewsAddResponse::item($response);
    }
}