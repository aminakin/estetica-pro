<?php

namespace ApiLib\API\User;

use ApiLib\AccessClass\AdvancedResponse;
use Bitrix\Main\HttpRequest;

class ForgotPasswordService
{

    public function store(HttpRequest $request)
    {
        $login = $request->get('login');
        $email = $request->get('email');

        if(!defined("ADMIN_SECTION") || ADMIN_SECTION !== true)
            $USER_LID = SITE_ID;
        else
            $USER_LID = false;

        global $DB, $APPLICATION;

        $arParams = array(
            "LOGIN" => $login,
            "EMAIL" => $email,
            "SITE_ID" => $USER_LID,
            "PHONE_NUMBER" => '',
            "SHORT_CODE" => false,
            );

        $result_message = array("MESSAGE"=>GetMessage('ACCOUNT_INFO_SENT')."<br>", "TYPE"=>"OK");
        $APPLICATION->ResetException();
        $bOk = true;
        foreach(GetModuleEvents("main", "OnBeforeUserSendPassword", true) as $arEvent)
        {
            if(ExecuteModuleEventEx($arEvent, array(&$arParams))===false)
            {
                if($err = $APPLICATION->GetException())
                    $result_message = array("MESSAGE"=>$err->GetString()."<br>", "TYPE"=>"ERROR");

                $bOk = false;
                break;
            }
        }

        if($bOk)
        {
            $found = false;

            if($arParams["LOGIN"] <> '' || $arParams["EMAIL"] <> '')
            {
                $strSql = "";
                if($arParams["LOGIN"] <> '')
                {
                    $strSql =
                        "SELECT ID, LID, ACTIVE, BLOCKED, CONFIRM_CODE, LOGIN, EMAIL, NAME, LAST_NAME, LANGUAGE_ID ".
                        "FROM b_user u ".
                        "WHERE LOGIN='".$DB->ForSQL($arParams["LOGIN"])."' ".
                        "	AND (ACTIVE='Y' OR NOT(CONFIRM_CODE IS NULL OR CONFIRM_CODE='')) ".
                        (
                            // $arParams["EXTERNAL_AUTH_ID"] can be changed in the OnBeforeUserSendPassword event
                        $arParams["EXTERNAL_AUTH_ID"] <> ''?
                            "	AND EXTERNAL_AUTH_ID='".$DB->ForSQL($arParams["EXTERNAL_AUTH_ID"])."' " :
                            "	AND (EXTERNAL_AUTH_ID IS NULL OR EXTERNAL_AUTH_ID='') "
                        );
                }
                if($arParams["EMAIL"] <> '')
                {
                    if($strSql <> '')
                    {
                        $strSql .= "\nUNION\n";
                    }
                    $strSql .=
                        "SELECT ID, LID, ACTIVE, BLOCKED, CONFIRM_CODE, LOGIN, EMAIL, NAME, LAST_NAME, LANGUAGE_ID ".
                        "FROM b_user u ".
                        "WHERE EMAIL='".$DB->ForSQL($arParams["EMAIL"])."' ".
                        "	AND (ACTIVE='Y' OR NOT(CONFIRM_CODE IS NULL OR CONFIRM_CODE='')) ".
                        (
                        $arParams["EXTERNAL_AUTH_ID"] <> ''?
                            "	AND EXTERNAL_AUTH_ID='".$DB->ForSQL($arParams["EXTERNAL_AUTH_ID"])."' " :
                            "	AND (EXTERNAL_AUTH_ID IS NULL OR EXTERNAL_AUTH_ID='') "
                        );
                }
                $res = $DB->Query($strSql);

                while($arUser = $res->Fetch())
                {
                    if($arParams["SITE_ID"]===false)
                    {
                        if(defined("ADMIN_SECTION") && ADMIN_SECTION===true)
                            $arParams["SITE_ID"] = \CSite::GetDefSite($arUser["LID"]);
                        else
                            $arParams["SITE_ID"] = SITE_ID;
                    }

                    if($arUser["ACTIVE"] == "Y")
                    {
                        if($arUser["BLOCKED"] <> "Y")
                        {
                            $found = true;

                            \CUser::SendUserInfo($arUser["ID"], $arParams["SITE_ID"], GetMessage("INFO_REQ"), true, 'USER_PASS_REQUEST');
                        }
                    }

                    if(\COption::GetOptionString("main", "event_log_password_request", "N") === "Y")
                    {
                        \CEventLog::Log("SECURITY", "USER_INFO", "main", $arUser["ID"]);
                    }
                }
            }
            if(!$found)
            {
                AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => GetMessage('DATA_NOT_FOUND1')], [['name' => 'Content-Type', 'value' => 'application/json']]);
            }
        }

        if ($result_message['TYPE'] == 'ERROR') {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result_message], [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return $result_message;
    }

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
            true
        );

        if ($result_message['TYPE'] == 'ERROR') {
            AdvancedResponse::response(400, ['TYPE' => 'ERROR', 'MESSAGE' => $result_message], [['name' => 'Content-Type', 'value' => 'application/json']]);
        }

        return $result_message;
    }
}