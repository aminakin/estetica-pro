<?php

namespace ApiLib\Response\Order;

use ApiLib\Response\JsonResponseAbstract;

class PaySystemResponse extends JsonResponseAbstract
{

    /**
     * @OA\Schema (
     *     schema="PaySystemShema",
     *     type="object",
     *     @OA\Property(property="id", type="integer", readOnly="true"),
     *     @OA\Property(property="pay_system_id", type="integer", readOnly="true"),
     *     @OA\Property(property="name", type="string", readOnly="true"),
     *     @OA\Property(property="psa_name", type="string", readOnly="true"),
     *     @OA\Property(property="code", type="string", readOnly="true"),
     *     @OA\Property(property="sort", type="integer", readOnly="true"),
     *     @OA\Property(property="description", type="string", readOnly="true"),
     *     @OA\Property(property="ps_mode", type="string", readOnly="true"),
     *     @OA\Property(property="have_price", type="string", readOnly="true"),
     *     @OA\Property(property="active", type="string", readOnly="true"),
     *     @OA\Property(property="allow_edit_payment", type="string", readOnly="true"),
     *     @OA\Property(property="is_cash", type="string", readOnly="true"),
     *     @OA\Property(property="auto_change_1c", type="string", readOnly="true"),
     *     @OA\Property(property="can_print_check", type="string", readOnly="true"),
     *     @OA\Property(property="entity_registry_type", type="string", readOnly="true"),
     *     @OA\Property(property="xml_id", type="string", readOnly="true"),
     *     @OA\Property(property="psa_id", type="integer", readOnly="true"),
     *     @OA\Property(property="psa_action_file", type="string", readOnly="true"),
     *     @OA\Property(property="psa_result_file", type="string", readOnly="true"),
     *     @OA\Property(property="psa_new_window", type="string", readOnly="true"),
     *     @OA\Property(property="psa_person_type_id", type="string", readOnly="true"),
     *     @OA\Property(property="psa_params", type="string", readOnly="true"),
     *     @OA\Property(property="psa_tarif", type="string", readOnly="true"),
     *     @OA\Property(property="psa_have_payment", type="string", readOnly="true"),
     *     @OA\Property(property="psa_have_action", type="string", readOnly="true"),
     *     @OA\Property(property="psa_have_result", type="string", readOnly="true"),
     *     @OA\Property(property="psa_have_prepay", type="string", readOnly="true"),
     *     @OA\Property(property="psa_have_result_receive", type="string", readOnly="true"),
     *     @OA\Property(property="psa_encoding", type="string", readOnly="true"),
     *     @OA\Property(property="psa_logotip", type="object", example="{}"),
     *     @OA\Property(property="price", type="integer", readOnly="true"),
     * )
     */
    public static function data($data): array
    {
        return [
            'id' => $data['ID'],
            'pay_system_id' => $data['PAY_SYSTEM_ID'],
            'name' => $data['NAME'],
            'psa_name' => $data['PSA_NAME'],
            'code' => $data['CODE'],
            'sort' => $data['SORT'],
            'description' => $data['DESCRIPTION'],
            'ps_mode' => $data['PS_MODE'],
            'have_price' => $data['HAVE_PRICE'],
            'active' => $data['ACTIVE'],
            'allow_edit_payment' => $data['ALLOW_EDIT_PAYMENT'],
            'is_cash' => $data['IS_CASH'],
            'auto_change_1c' => $data['AUTO_CHANGE_1C'],
            'can_print_check' => $data['CAN_PRINT_CHECK'],
            'entity_registry_type' => $data['ENTITY_REGISTRY_TYPE'],
            'xml_id' => $data['XML_ID'],
            'psa_id' => $data['PSA_ID'],
            'psa_action_file' => $data['PSA_ACTION_FILE'],
            'psa_result_file' => $data['PSA_RESULT_FILE'],
            'psa_new_window' => $data['PSA_NEW_WINDOW'],
            'psa_person_type_id' => $data['PSA_PERSON_TYPE_ID'],
            'psa_params' => $data['PSA_PARAMS'],
            'psa_tarif' => $data['PSA_TARIF'],
            'psa_have_payment' => $data['PSA_HAVE_PAYMENT'],
            'psa_have_action' => $data['PSA_HAVE_ACTION'],
            'psa_have_result' => $data['PSA_HAVE_RESULT'],
            'psa_have_prepay' => $data['PSA_HAVE_PREPAY'],
            'psa_have_result_receive' => $data['PSA_HAVE_RESULT_RECEIVE'],
            'psa_encoding' => $data['PSA_ENCODING'],
            'psa_logotip' => $data['PSA_LOGOTIP'],
            'price' => $data['PRICE'],
        ];
    }
}