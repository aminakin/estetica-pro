<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;

class OrderPaymentResponse extends JsonResponseAbstract
{
    /**
     * @OA\Schema (
     *     schema="OrderPaymenShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="pay_system_id", type="integer", readOnly="true"),
     *     @OA\Property(property="person_type_id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true"),
     *     @OA\Property(property="psa_name", type="string", readOnly="true"),
     *     @OA\Property(property="code", type="string", readOnly="true"),
     *     @OA\Property(property="sort", type="integer", readOnly="true"),
     *     @OA\Property(property="action_file", type="string", readOnly="true"),
     *     @OA\Property(property="result_file", type="string", readOnly="true"),
     *     @OA\Property(property="description", type="string", readOnly="true"),
     *     @OA\Property(property="new_window", type="string", readOnly="true"),
     *     @OA\Property(property="params", type="string", readOnly="true"),
     *     @OA\Property(property="tarif", type="string", readOnly="true"),
     *     @OA\Property(property="ps_mode", type="string", readOnly="true"),
     *     @OA\Property(property="have_payment", type="string", readOnly="true"),
     *     @OA\Property(property="have_action", type="string", readOnly="true"),
     *     @OA\Property(property="have_result", type="string", readOnly="true"),
     *     @OA\Property(property="have_prepay", type="string", readOnly="true"),
     *     @OA\Property(property="have_price", type="string", readOnly="true"),
     *     @OA\Property(property="have_result_receive", type="string", readOnly="true"),
     *     @OA\Property(property="encoding", type="string", readOnly="true"),
     *     @OA\Property(property="logotip", type="string", readOnly="true"),
     *     @OA\Property(property="active", type="string", readOnly="true"),
     *     @OA\Property(property="allow_edit_payment", type="string", readOnly="true"),
     *     @OA\Property(property="is_cash", type="string", readOnly="true"),
     *     @OA\Property(property="auto_change_1c", type="string", readOnly="true"),
     *     @OA\Property(property="is_cash", type="string", readOnly="true"),
     *     @OA\Property(property="auto_change_1c", type="string", readOnly="true"),
     *     @OA\Property(property="can_print_check", type="string", readOnly="true"),
     *     @OA\Property(property="entity_registry_type", type="string", readOnly="true"),
     *     @OA\Property(property="xml_id", type="string", readOnly="true"),
     *     @OA\Property(property="logotip_src", type="object",
     *          @OA\Property(property="src", type="string", readOnly="true"),
     *          @OA\Property(property="width", type="integer", readOnly="true"),
     *          @OA\Property(property="height", type="integer", readOnly="true"),
     *          @OA\Property(property="size", type="string", readOnly="true"),
     *      ),
     *     @OA\Property(property="payment_url", type="string", readOnly="true"),
     *     @OA\Property(property="is_paid", type="string", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'pay_system_id' => $data['PAY_SYSTEM_ID'],
            'person_type_id' => $data['PERSON_TYPE_ID'],
            'name' => $data['NAME'],
            'psa_name' => $data['PSA_NAME'],
            'code' => $data['CODE'],
            'sort' => $data['SORT'],
            'action_file' => $data['ACTION_FILE'],
            'result_file' => $data['RESULT_FILE'],
            'description' => $data['DESCRIPTION'],
            'new_window' => $data['NEW_WINDOW'],
            'params' => $data['PARAMS'],
            'tarif' => $data['TARIF'],
            'ps_mode' => $data['PS_MODE'],
            'have_payment' => $data['HAVE_PAYMENT'],
            'have_action' => $data['HAVE_ACTION'],
            'have_result' => $data['HAVE_RESULT'],
            'have_prepay' => $data['HAVE_PREPAY'],
            'have_price' => $data['HAVE_PRICE'],
            'have_result_receive' => $data['HAVE_RESULT_RECEIVE'],
            'encoding' => $data['ENCODING'],
            'logotip' => $data['LOGOTIP'],
            'active' => $data['ACTIVE'],
            'allow_edit_payment' => $data['ALLOW_EDIT_PAYMENT'],
            'is_cash' => $data['IS_CASH'],
            'auto_change_1c' => $data['AUTO_CHANGE_1C'],
            'can_print_check' => $data['CAN_PRINT_CHECK'],
            'entity_registry_type' => $data['ENTITY_REGISTRY_TYPE'],
            'xml_id' => $data['XML_ID'],
            'logotip_src' => [
                'src' => $data['LOGOTIP_SRC']['src'],
                'width' => $data['LOGOTIP_SRC']['width'],
                'height' => $data['LOGOTIP_SRC']['height'],
                'size' => $data['LOGOTIP_SRC']['size'],
            ],
            'payment_url' => $data['PAYMENT_URL'],
            'is_paid' => $data['IS_PAID'],
        ];
    }
}