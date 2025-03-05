<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;

class LocationResponse extends JsonResponseAbstract
{

    /**
     * @OA\Schema (
     *     schema="LocationShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="code", type="string", readOnly="true"),
     *     @OA\Property(property="left_margin", type="integer", readOnly="true"),
     *     @OA\Property(property="right_margin", type="integer", readOnly="true"),
     *     @OA\Property(property="depth_level", type="integer", readOnly="true"),
     *     @OA\Property(property="sort", type="integer", readOnly="true"),
     *     @OA\Property(property="parent_id", type="integer", readOnly="true"),
     *     @OA\Property(property="loca_default", type="string", readOnly="true"),
     *     @OA\Property(property="name' =>", type="string", readOnly="true"),
     *     @OA\Property(property="parents' =>", type="string", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'code' => $data['CODE'],
            'left_margin' => $data['LEFT_MARGIN'],
            'right_margin' => $data['RIGHT_MARGIN'],
            'depth_level' => $data['DEPTH_LEVEL'],
            'sort' => $data['SORT'],
            'parent_id' => $data['PARENT_ID'],
            'loca_default' => $data['LOC_DEFAULT'],
            'name' => $data['NAME_RU'],
            'parents' => $data['PARENTS'],
        ];
    }
}