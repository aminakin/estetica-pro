<?php

namespace ApiLib\EntryPoints\Iblock;

use ApiLib\Response\Iblock\ExampleResponseResource;
use ApiLib\Response\Iblock\SliderResponseResource;
use Bitrix\Main\HttpRequest;
use ApiLib\API\Iblock\SliderService;

/**
 * Входной класс для эндпоинтов ИБ
 */
class IblockEntryController
{
    /**
     * Пример запроса ИБ
     *
     * @OA\Get (
     *     tags={"Раздел для роута"},
     *     path="/api/exampleIB",
     *     summary="Короткое описание  работы роута",
     *     description="Полное описание работы роута",
     *     @OA\Parameter(
     *         description="name",
     *         in="query",
     *         name="name",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *         ),
     *     ),
     *     @OA\Response( response=200, description="OK",
     *      @OA\JsonContent(type="object", ref="#/components/schemas/ExampleShemaName")
     *      ),
     *    @OA\Response(response=401, description="Unauthorized"),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public static function getExampleIBData(HttpRequest  $request){
        //валидатор бы изобрести какой-нибудь для реквеста
        return ExampleResponseResource::item(['name' => $request->get('name') ?? '']);
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
     *    @OA\Response(response=401, description="Unauthorized"),
     *    @OA\Response(response=404, description="Not Found")
     *  )
     */
    public function getSliderData(){
        $service = new SliderService();
        $response = $service->getSlider();

        return SliderResponseResource::collection($response);
    }

}