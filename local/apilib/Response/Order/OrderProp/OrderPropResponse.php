<?php

namespace ApiLib\Response\Order\OrderProp;

use ApiLib\Response\JsonResponseAbstract;

class OrderPropResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="OrderPropShema",
     *     type="object",
     *     @OA\Property(property="user_profiles", type="array", @OA\Items(type="object", ref="#/components/schemas/UserProfileShema")),
     *     @OA\Property(property="user_props_y", type="array", @OA\Items(type="object", ref="#/components/schemas/UserPropShema")),
     *     @OA\Property(property="print", type="array", @OA\Items(type="object", ref="#/components/schemas/PrintShema")),
     *     @OA\Property(property="user_prop_n", type="array", @OA\Items(type="object", ref="#/components/schemas/UserPropShema")),
     *     @OA\Property(property="related", type="array", example="[]")
     * )
     */
    public static function data($data): array
    {

        return [
            'user_profiles' => UserProfileResponse::collection($data['USER_PROFILES']),
            'user_props_y' => UserPropResponse::collection($data['USER_PROPS_Y']),
            'print' => PrintResponse::collection($data['PRINT']),
            'user_prop_n' => UserPropResponse::collection($data['USER_PROPS_N']),
            'related' => $data['RELATED'],
        ];
    }
}