<?php

namespace ApiLib\EntryPoints\Iblock;

use ApiLib\AccessClass\AuthAccess;
use ApiLib\API\Settings\BitrixSettings;
use ApiLib\EntryPoints\AController;
use ApiLib\Response\Iblock\ExampleResponseResource;
use ApiLib\Response\Iblock\SliderResponseResource;
use Bitrix\Main\HttpRequest;
use ApiLib\API\Iblock\SliderService;
use Bitrix\Main\Request;

/**
 * Входной класс для эндпоинтов ИБ
 */
class IblockEntryController extends AController
{
    public function __construct(Request $request = null)
    {
        parent::__construct($request);
    }

    /**
     * Запрос на получение слайдера
     *
     * @OA\Get (
     *     tags={"Слайдер"},
     *     path="/api/slider",
     *     summary="Получение слайдера",
     *     description="Получение слайдера",
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/SliderShema")
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
    public static function getSliderData(){
        $service = new SliderService((int)BitrixSettings::getApiVarByName('SLIDER_ID'), BitrixSettings::getApiVarByName('SLIDER_LINK_PROPERTIES'));
        $response = $service->getSlider();
        return SliderResponseResource::collection($response);
    }

}