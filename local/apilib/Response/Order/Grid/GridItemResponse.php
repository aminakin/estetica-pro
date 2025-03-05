<?php

namespace ApiLib\Response\Order\Grid;

use ApiLib\Response\JsonResponseAbstract;

class GridItemResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="GridItemShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="data", type="object", ref="#/components/schemas/GridItemDataShema"),
     *     @OA\Property(property="actions", type="array", readOnly="true"),
     *     @OA\Property(property="columns", type="object", ref="#/components/schemas/GridItemColumnShema"),
     *     @OA\Property(property="editable", type="boolean", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['id'],
            'data' => GridItemDataResponse::item($data['data']),
            'actions' => $data['actions'],
            'columns' => GridItemColumnResponse::item($data['columns']),
            'editable' => $data['editable'],
        ];
    }
}