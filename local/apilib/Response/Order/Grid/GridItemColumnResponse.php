<?php

namespace ApiLib\Response\Order\Grid;

use ApiLib\Response\JsonResponseAbstract;

class GridItemColumnResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="GridItemColumnShema",
     *     type="object",
     *     @OA\Property(property="props", type="string", readOnly="true"),
     *     @OA\Property(property="preview_picture", type="string", readOnly="true"),
     *     @OA\Property(property="detail_picture", type="string", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'props' => $data['PROPS'],
            'preview_picture' => $data['PREVIEW_PICTURE'],
            'detail_picture' => $data['DETAIL_PICTURE'],
        ];
    }
}