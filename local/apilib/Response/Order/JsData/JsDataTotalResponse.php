<?php

namespace ApiLib\Response\Order\JsData;

use ApiLib\Response\JsonResponseAbstract;

class JsDataTotalResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="JsDataTotalShema",
     *     type="object",
     *     @OA\Property(property="basket_positions", type="integer", readOnly="true"),
     *     @OA\Property(property="price_without_discount_valur", type="integer", readOnly="true"),
     *     @OA\Property(property="price_without_discount", type="string", readOnly="true"),
     *     @OA\Property(property="basket_price_discount_diff_value", type="integer", readOnly="true"),
     *     @OA\Property(property="basket_price_discount_diff", type="string", readOnly="true"),
     *     @OA\Property(property="payed_from_account_formated", type="string", readOnly="true"),
     *     @OA\Property(property="order_total_price", type="integer", readOnly="true"),
     *     @OA\Property(property="order_total_prive_formated", type="string", readOnly="true"),
     *     @OA\Property(property="order_total_left_to_pay", type="string", readOnly="true"),
     *     @OA\Property(property="order_total_left_to_pay_foramted", type="string", readOnly="true"),
     *     @OA\Property(property="order_weight", type="integer", readOnly="true"),
     *     @OA\Property(property="order_weight_formated", type="string", readOnly="true"),
     *     @OA\Property(property="order_weight_formated", type="string", readOnly="true"),
     *     @OA\Property(property="order_price", type="integer", readOnly="true"),
     *     @OA\Property(property="order_price_formated", type="string", readOnly="true"),
     *     @OA\Property(property="use_vat", type="boolean", readOnly="true"),
     *     @OA\Property(property="vat_rate", type="integer", readOnly="true"),
     *     @OA\Property(property="vat_sum", type="integer", readOnly="true"),
     *     @OA\Property(property="vat_sum_formated", type="string", readOnly="true"),
     *     @OA\Property(property="tax_price", type="integer", readOnly="true"),
     *     @OA\Property(property="tax_list", type="array", example="[]"),
     *     @OA\Property(property="discount_price", type="integer"),
     *     @OA\Property(property="discount_price_formated", type="string"),
     *     @OA\Property(property="delivery_price", type="integer"),
     *     @OA\Property(property="pay_system_price", type="integer"),
     *     @OA\Property(property="pay_system_price_formatted", type="string"),
     *
     * )
     */
    public static function data($data): array
    {
        return [
            'basket_positions' => $data['BASKET_POSITIONS'],
            'price_without_discount_valur' => $data['PRICE_WITHOUT_DISCOUNT_VALUE'],
            'price_without_discount' => $data['PRICE_WITHOUT_DISCOUNT'],
            'basket_price_discount_diff_value' => $data['BASKET_PRICE_DISCOUNT_DIFF_VALUE'],
            'basket_price_discount_diff' => $data['BASKET_PRICE_DISCOUNT_DIFF'],
            'payed_from_account_formated' => $data['PAYED_FROM_ACCOUNT_FORMATED'],
            'order_total_price' => $data['ORDER_TOTAL_PRICE'],
            'order_total_prive_formated' => $data['ORDER_TOTAL_PRICE_FORMATED'],
            'order_total_left_to_pay' => $data['ORDER_TOTAL_LEFT_TO_PAY'],
            'order_total_left_to_pay_foramted' => $data['ORDER_TOTAL_LEFT_TO_PAY_FORMATED'],
            'order_weight' => $data['ORDER_WEIGHT'],
            'order_weight_formated' => $data['ORDER_WEIGHT_FORMATED'],
            'order_price' => $data['ORDER_PRICE'],
            'order_price_formated' => $data['ORDER_PRICE_FORMATED'],
            'use_vat' => $data['USE_VAT'],
            'vat_rate' => $data['VAT_RATE'],
            'vat_sum' => $data['VAT_SUM'],
            'vat_sum_formated' => $data['VAT_SUM_FORMATED'],
            'tax_price' => $data['TAX_PRICE'],
            'tax_list' => $data['TAX_LIST'],
            'discount_price' => $data['DISCOUNT_PRICE'],
            'discount_price_formated' => $data['DISCOUNT_PRICE_FORMATED'],
            'delivery_price' => $data['DELIVERY_PRICE'],
            'delivery_price_formated' => $data['DELIVERY_PRICE_FORMATED'],
            'pay_system_price' => $data['PAY_SYSTEM_PRICE'],
            'pay_system_price_formatted' => $data['PAY_SYSTEM_PRICE_FORMATTED'],
        ];
    }
}