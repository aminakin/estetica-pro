<?php

namespace ApiLib\API\User;

use ApiLib\AccessClass\AdvancedResponse;
use Bitrix\Main\HttpRequest;

class ChangePasswordService
{

    public function update(HttpRequest $request)
    {
        if(!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
            $USER_LID = SITE_ID;
        else
            $USER_LID = false;

        $result_message = (new \CUser)->ChangePassword(
            $request->get("login"),
            $request->get("checkword"),
            $request->get("password"),
            $request->get("confirm_password"),
            $USER_LID,
            $request->get("captcha_word"),
            $request->get("captcha_sid"),
            true,
            '',
            $request->get("current_password"),
        );

        if ($result_message['TYPE'] == 'ERROR') {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result_message], [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return $result_message;
    }
}