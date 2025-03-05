<?php

namespace ApiLib\Response\catalog;

use ApiLib\Response\JsonResponseAbstract;

class ProductsCardResponse extends JsonResponseAbstract
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
     *     @OA\Property(property="title", type="string", readOnly="true", example="Ресницы Extreme Look чёрные 18 линий (изгиб LD)"),
     *     @OA\Property(property="description", type="string", readOnly="true", example="Ресницы Extreme Look чёрные 18 линий (изгиб LD)"),
     *     @OA\Property(property="isAvailable", type="boolean", readOnly="true", examle="true"),
     *
     * )
     */
    public static function data($data): array
    {
        $response = [
            'id' => $data['ID'],
            'imgSrc' => ($data['DETAIL_PICTURE'] ? \CFile::GetPath($data['DETAIL_PICTURE']) : \CFile::GetPath($data['PREVIEW_PICTURE'])),
            'price' => $data['PRICE'],
            'title' => $data['NAME'],
            'description' => ($data['DETAIL_TEXT'] ?? $data['PREVIEW_TEXT']),
            'isAvailable' => ($data['AVAILABLE'] == 'Y'),
        ];
        $response['options'] = [];
        foreach ($data["SKU_LIST"] as $sku){
            $response['options'][$sku['ID']] = [
                'id' => $sku['ID'],
                'imgSrc' => ($sku['DETAIL_PICTURE'] ? \CFile::GetPath($sku['DETAIL_PICTURE']) : \CFile::GetPath($sku['PREVIEW_PICTURE'])),
                'price' => $sku['PRICE'],
                'title' => $sku['NAME'],
                'description' => ($sku['DETAIL_TEXT'] ?? $sku['PREVIEW_TEXT']),
                'isAvailable' => ($sku['AVAILABLE'] == 'Y'),
            ];
            foreach ($sku["SKU_OPTIONS"] as $option){
                $response['options'][$sku['ID']]['options_sku'][] = ['name' => $option['DESCRIPTION'], 'value' => $option['VALUE']];
            }
        }
        return $response;
    }
}