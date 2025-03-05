<?php

namespace ApiLib\Response\Order\JsData;

use ApiLib\Response\JsonResponseAbstract;

class JsDataLogictimBonusResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="JsDataLogictimBonusShema",
     *     type="object",
     *     @OA\Property(property="arr_pay_bonus", type="array", @OA\Items(type="object", ref="#/components/schemas/JsDataLogictimBonusArrPayBonusShema")),
     *     @OA\Property(property="min_bonus", type="string", readOnly="true"),
     *     @OA\Property(property="max_bonus", type="string", readOnly="true"),
     *     @OA\Property(property="user_bonus", type="string", readOnly="true"),
     *     @OA\Property(property="logictim_bonus_user_dostup", type="string", readOnly="true"),
     *     @OA\Property(property="input_bonus", type="integer", readOnly="true"),
     *     @OA\Property(property="pay_bonus", type="string", readOnly="true"),
     *     @OA\Property(property="pay_bonus_formated", type="string", readOnly="true"),
     *     @OA\Property(property="pay_bonus_bo_post", type="string", readOnly="true"),
     *     @OA\Property(property="pay_bonus_no_post_formated", type="string", readOnly="true"),
     *     @OA\Property(property="order_sum", type="string", readOnly="true"),
     *     @OA\Property(property="order_sum_formated", type="string", readOnly="true"),
     *     @OA\Property(property="arr_bonus", type="string", readOnly="true"),
     *     @OA\Property(property="add_bonus", type="string", readOnly="true"),
     *     @OA\Property(property="add_bonus_formated", type="string", readOnly="true"),
     *     @OA\Property(property="order_prop_payment_bonus_id", type="string", readOnly="true"),
     *     @OA\Property(property="order_prop_add_bonus_id", type="string", readOnly="true"),
     *     @OA\Property(property="discount_to_products", type="string", readOnly="true"),
     *     @OA\Property(property="order_pay_bonus_auto", type="string", readOnly="true"),
     *     @OA\Property(property="text_bonus_balls", type="string", readOnly="true"),
     *     @OA\Property(property="text_bonus_pay", type="string", readOnly="true"),
     *     @OA\Property(property="text_bonus_for_item", type="string", readOnly="true"),
     *     @OA\Property(property="module_lang", type="object",
     *          @OA\Property(property="have_bonus_text", type="string", readOnly="true"),
     *          @OA\Property(property="have_bonus_text_format", type="string", readOnly="true"),
     *          @OA\Property(property="can_use_bonus_text", type="string", readOnly="true"),
     *          @OA\Property(property="can_use_bonus_text_format", type="string", readOnly="true"),
     *          @OA\Property(property="min_bonus_text", type="string", readOnly="true"),
     *          @OA\Property(property="max_bonus_text", type="string", readOnly="true"),
     *          @OA\Property(property="pay_bonus_text", type="string", readOnly="true"),
     *          @OA\Property(property="text_bonus_for_payment", type="string", readOnly="true"),
     *          @OA\Property(property="text_bonus_use_bonus_button", type="string", readOnly="true"),
     *          @OA\Property(property="text_bonus_error_min_bonus_format", type="string", readOnly="true"),
     *      ),
     * )
     */
    public static function data($data): array
    {
        return [
            'arr_pay_bonus' => JsDataLogictimBonusArrPayBonusResponse::collection($data['ARR_PAY_BONUS']['ITEMS']),
            'min_bonus' => $data['MIN_BONUS'],
            'max_bonus' => $data['MAX_BONUS'],
            'user_bonus' => $data['USER_BONUS'],
            'logictim_bonus_user_dostup' => $data['LOGICTIM_BONUS_USER_DOSTUP'],
            'input_bonus' => $data['INPUT_BONUS'],
            'pay_bonus' => $data['PAY_BONUS'],
            'pay_bonus_formated' => $data['PAY_BONUS_FORMATED'],
            'pay_bonus_bo_post' => $data['PAY_BONUS_NO_POST'],
            'pay_bonus_no_post_formated' => $data['PAY_BONUS_NO_POST_FORMATED'],
            'order_sum' => $data['ORDER_SUM'],
            'order_sum_formated' => $data['ORDER_SUM_FORMATED'],
            'arr_bonus' => $data['ARR_BONUS'],
            'add_bonus' => $data['ADD_BONUS'],
            'add_bonus_formated' => $data['ADD_BONUS_FORMAT'],
            'order_prop_payment_bonus_id' => $data['ORDER_PROP_PAYMENT_BONUS_ID'],
            'order_prop_add_bonus_id' => $data['ORDER_PROP_ADD_BONUS_ID'],
            'discount_to_products' => $data['DISCOUNT_TO_PRODUCTS'],
            'order_pay_bonus_auto' => $data['ORDER_PAY_BONUS_AUTO'],
            'text_bonus_balls' => $data['TEXT_BONUS_BALLS'],
            'text_bonus_pay' => $data['TEXT_BONUS_PAY'],
            'text_bonus_for_item' => $data['TEXT_BONUS_FOR_ITEM'],
            'module_lang' => [
                'have_bonus_text' => $data['HAVE_BONUS_TEXT'],
                'have_bonus_text_format' => $data['HAVE_BONUS_TEXT_FORMAT'],
                'can_use_bonus_text' => $data['CAN_USE_BONUS_TEXT'],
                'can_use_bonus_text_format' => $data['CAN_USE_BONUS_TEXT_FORMAT'],
                'min_bonus_text' => $data['MIN_BONUS_TEXT'],
                'max_bonus_text' => $data['MAX_BONUS_TEXT'],
                'pay_bonus_text' => $data['PAY_BONUS_TEXT'],
                'text_bonus_for_payment' => $data['TEXT_BONUS_FOR_PAYMENT'],
                'text_bonus_use_bonus_button' => $data['TEXT_BONUS_USE_BONUS_BUTTON'],
                'text_bonus_error_min_bonus_format' => $data['TEXT_BONUS_ERROR_MIN_BONUS_FORMAT'],
            ],
        ];
    }
}