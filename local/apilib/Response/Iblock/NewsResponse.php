<?php

namespace ApiLib\Response\Iblock;

use ApiLib\API\Settings\BitrixSettings;
use ApiLib\Response\JsonResponseAbstract;

class NewsResponse extends JsonResponseAbstract
{
    /**
     * Ответ в JSON для ленты новостей
     *
     * @OA\Schema (
     *     schema="NewsShema",
     *     type="object",
     *      @OA\Property(property="id", type="integer", readOnly="true"),
     *      @OA\Property(property="date", type="date", readOnly="true"),
     *      @OA\Property(property="title", type="string", readOnly="true", example="Тестовая новость"),
     *      @OA\Property(property="imgSrc", type="string", readOnly="true", example="example.com/img.png"),
     *      @OA\Property(property="productId", type="intger", readOnly="true", example="1213"),
     *      @OA\Property(property="sectionId", type="intger", readOnly="true", example="1213"),
     *      @OA\Property(property="brandSectionId", type="intger", readOnly="true", example="1213"),
     *      @OA\Property(property="additionalImages", type="array", readOnly="true", example="example.com/img.png, example.com/img.png, example.com/img.png"),
     *      @OA\Property(property="text", type="string", readOnly="true", example="Текст тестовой новости"),
     *      @OA\Property(property="comments", type="object", ref="#/components/schemas/RewiewItem"),
     *      @OA\Property(property="activeFrom", type="string", readOnly="true", example="2017-09-18 11:45:11"),
     *      @OA\Property(property="activeTo", type="string", readOnly="true", example="2017-09-18 11:45:11"),
     *      @OA\Property(property="sort", type="integer", readOnly="true", example="500"),
     * )
     */
    public static function data($data): array
    {
        $newsImages = [];
        if (isset($data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_MORE_PHOTO_PROP_ID')])){
            foreach ($data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_MORE_PHOTO_PROP_ID')] as $image){
                $newsImages[] =  \CFile::ResizeImageGet($image, ['width' => 1200, 'height' => 1200], BX_RESIZE_IMAGE_PROPORTIONAL)['src'];
            }
        }

        $productid = [];
        if (isset($data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_PRODUCT_PROP_ID')])){
            $productid = $data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_PRODUCT_PROP_ID')];
        }

        $sectionId = [];
        if (isset($data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_SECTION_PROP_ID')])){
            $sectionId = $data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_SECTION_PROP_ID')];
        }

        $brandSectionId = [];
        if (isset($data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_BRAND_SECTION_PROP_ID')])){
            $brandSectionId = $data['PROPERTIES'][BitrixSettings::getApiVarByName('NEWS_BRAND_SECTION_PROP_ID')];
        }

        return [
            'id' => (int)$data['ID'],
            'date' =>  FormatDate("d.m.Y H:i", MakeTimeStamp($data['TIMESTAMP_X'])),
            'title' => $data['NAME'],
            'imgSrc' => $data['PREVIEW_PICTURE'],
            'productId' => $productid,
            'sectionId' => $sectionId,
            'brandSectionId' => $brandSectionId,
            'additionalImages' => $newsImages,
            'text' => $data['PREVIEW_TEXT'],
            'comments' =>  NewsReviewView::collection($data['COMMENTS']),
            'activeFrom' => $data['ACTIVE_FROM'],
            'activeTo' => $data['ACTIVE_TO'],
            'sort' => $data['SORT'],
        ];
    }
}
