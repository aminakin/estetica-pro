<?php

namespace ApiLib\Response\Catalog;

use ApiLib\Response\JsonResponseAbstract;

class ProductResponse extends JsonResponseAbstract
{
    /**
     * Пример ответа в JSON для карточки товара
     *
     * @OA\Schema (
     *     schema="ProductsCardShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="imgSrc", type="string", readOnly="true", example="example.com/img.png"),
     *     @OA\Property(property="price", type="string", readOnly="true", example="790.00"),
     *     @OA\Property(property="max_quantity", type="float", readOnly="true", example="3"),
     *     @OA\Property(property="title", type="string", readOnly="true", example="Ресницы Extreme Look чёрные 18 линий (изгиб LD)"),
     *     @OA\Property(property="type", type="integer", readOnly="true", example="3"),
     *     @OA\Property(property="description", type="string", readOnly="true", example="Ресницы Extreme Look чёрные 18 линий (изгиб LD)"),
     *     @OA\Property(property="isAvailable", type="boolean", readOnly="true", examle="true"),
     *     @OA\Property(property="props", type="array", @OA\Items(type="object", ref="#/components/schemas/PropShema")),
     *     @OA\Property(property="options", type="array", @OA\Items(type="object", ref="#/components/schemas/OfferShema")),
     *
     * )
     */
    public static function data($data): array
    {

        return [
            'id' => $data['ID'],
            'imgSrc' => ($data['DETAIL_PICTURE'] ?
                \CFile::ResizeImageGet($data['DETAIL_PICTURE'], ['width' => 1200, 'height' => 1200], BX_RESIZE_IMAGE_PROPORTIONAL)['src'] :
                \CFile::ResizeImageGet($data['PREVIEW_PICTURE'], ['width' => 1200, 'height' => 1200], BX_RESIZE_IMAGE_PROPORTIONAL)['src']
            ),            'price' => $data['PRICE'],
            'title' => $data['NAME'],
            'type' => $data['TYPE'],
            'description' => ($data['DETAIL_TEXT'] ?? $data['PREVIEW_TEXT']),
            'isAvailable' => ($data['AVAILABLE'] == 'Y'),
            'max_quantity' => $data['QUANTITY'],
            'props' => PropResponse::collection($data['PROPS']),
            'options' => OfferResponse::collection($data["SKU_LIST"]),
        ];
    }
}