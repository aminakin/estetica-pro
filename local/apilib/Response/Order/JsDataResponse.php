<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;
use ApiLib\Response\Order\Grid\GridResponse;
use ApiLib\Response\Order\JsData\JsDataLogictimBonusResponse;
use ApiLib\Response\Order\JsData\JsDataTotalResponse;
use ApiLib\Response\Order\OrderProp\OrderPropResponse;
use ApiLib\Response\Order\OrderProp\UserProfileResponse;

class JsDataResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="JsDataShema",
     *     type="object",
     *     @OA\Property(property="is_authorized", type="boolean", readOnly="true"),
     *     @OA\Property(property="last_order_data", type="object", example="{}"),
     *     @OA\Property(property="zip_property_changed", type="string", readOnly="true"),
     *     @OA\Property(property="order_description", type="boolean", readOnly="true"),
     *     @OA\Property(property="show_auth", type="boolean", readOnly="true"),
     *     @OA\Property(property="show_empty_basket", type="boolean", readOnly="true"),
     *     @OA\Property(property="auth", type="string", readOnly="true"),
     *     @OA\Property(property="sms_auth", type="string", readOnly="true"),
     *     @OA\Property(property="ok_message", type="string", readOnly="true"),
     *     @OA\Property(property="grid", type="object", ref="#/components/schemas/GridShema"),
     *     @OA\Property(property="person_type", type="array", @OA\Items(type="object", ref="#/components/schemas/PersonTypeShema")),
     *     @OA\Property(property="pay_system", type="array", @OA\Items(type="object", ref="#/components/schemas/PaySystemShema")),
     *     @OA\Property(property="inner_pay_system", type="string"),
     *     @OA\Property(property="delivery", type="array", @OA\Items(type="object", ref="#/components/schemas/DeliveryShema")),
     *     @OA\Property(property="user_profiles", type="array", @OA\Items(type="object", ref="#/components/schemas/UserProfileShema")),
     *     @OA\Property(property="order_prop", type="array", @OA\Items(type="object", ref="#/components/schemas/OrderPropShema")),
     *     @OA\Property(property="store_list", type="array", @OA\Items(type="object", ref="#/components/schemas/StoreListShema")),
     *     @OA\Property(property="buyer_store", type="integer"),
     *     @OA\Property(property="coupon_list", type="array", example="[]"),
     *     @OA\Property(property="pay_current_account", type="string"),
     *     @OA\Property(property="pay_from_account", type="string"),
     *     @OA\Property(property="current_budget_formated", type="string"),
     *     @OA\Property(property="total", type="object", ref="#/components/schemas/JsDataTotalShema"),
     *     @OA\Property(property="error", type="string"),
     *     @OA\Property(property="warning", type="string"),
     *     @OA\Property(property="logictim_bonus", type="object", ref="#/components/schemas/JsDataLogictimBonusShema"),
     * )
     */
    public static function data($data): array
    {
        return [
            'is_authorized' => $data['IS_AUTHORIZED'],
            'last_order_data' => $data['LAST_ORDER_DATA'],
            'zip_property_changed' => $data['ZIP_PROPERTY_CHANGED'],
            'order_description' => $data['ORDER_DESCRIPTION'],
            'show_auth' => $data['SHOW_AUTH'],
            'show_empty_basket' => $data['SHOW_EMPTY_BASKET'],
            'auth' => $data['AUTH'],
            'sms_auth' => $data['SMS_AUTH'],
            'ok_message' => $data['OK_MESSAGE'],
            'grid' => GridResponse::item($data['GRID']),
            'person_type' => PersonTypeResponse::collection($data['PERSON_TYPE']),
            'pay_system' => PaySystemResponse::collection($data['PAY_SYSTEM']),
            'inner_pay_system' => $data['INNER_PAY_SYSTEM'],
            'delivery' => DeliveryResponse::collection($data['DELIVERY']),
            'user_profiles' => UserProfileResponse::collection($data['USER_PROFILES']),
            'order_prop' => OrderPropResponse::item($data['ORDER_PROP']),
            'store_list' => StoreListResponse::collection($data['STORE_LIST']),
            'buyer_store' => $data['BUYER_STORE'],
            'coupon_list' => $data['COUPON_LIST'],
            'pay_current_account' => $data['PAY_CURRENT_ACCOUNT'],
            'pay_from_account' => $data['PAY_FROM_ACCOUNT'],
            'current_budget_formated' => $data['CURRENT_BUDGET_FORMATED'],
            'total' => JsDataTotalResponse::item($data['TOTAL']),
            'error' => $data['ERROR'],
            'warning' => $data['WARNING'],
            'logictim_bonus' => JsDataLogictimBonusResponse::item($data['LOGICTIM_BONUS']),
        ];
    }
}