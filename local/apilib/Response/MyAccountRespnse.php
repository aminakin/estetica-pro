<?php

namespace ApiLib\Response;

class MyAccountRespnse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="AccountShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true"),
     *     @OA\Property(property="last_name", type="string", readOnly="true"),
     *     @OA\Property(property="second_name", type="string", readOnly="true"),
     *     @OA\Property(property="email", type="string", readOnly="true"),
     *     @OA\Property(property="phone", type="string", readOnly="true"),
     *     @OA\Property(property="birthday", type="string", readOnly="true"),
     * )
     */

    public static function data($data): array
    {

        return [
            'id' => $data['ID'],
            'name' => $data['NAME'],
            'last_name' => $data['LAST_NAME'],
            'second_name' => $data['SECOND_NAME'],
            'email' => $data['EMAIL'],
            'phone' => $data['PERSONAL_PHONE'],
            'birthday' => $data['PERSONAL_BIRTHDAY'],
        ];
    }
}