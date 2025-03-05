<?php

namespace ApiLib\API\User;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

class RegistrationService
{
    private $arResult;

    private $arFields = [
        'LAST_NAME',
        'NAME',
        'SECOND_NAME',
        'EMAIL',
        'PERSONAL_PHONE',
        'PASSWORD',
        'CONFIRM_PASSWORD',
        'USER_BIRTHDAY_DAY',
        'USER_BIRTHDAY_MONTH',
        'USER_BIRTHDAY_YEAR',
        'UF_SUBSCRIBES',
        'UF_PERSONAL_AGREE',
    ];

    private $reuqireFields = [
        'LAST_NAME',
        'NAME',
        'SECOND_NAME',
        'EMAIL',
        'PERSONAL_PHONE',
        'PASSWORD',
        'CONFIRM_PASSWORD',
        'UF_PERSONAL_AGREE',
    ];

    public function store(array $data)
    {
        global $USER;
        global $APPLICATION;
        global $USER_FIELD_MANAGER;
        global $DB;

        Loc::setCurrentLang('ru');
        Loc::loadMessages(__FILE__);

        if (!$USER->IsAuthorized()) {

            if(\COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y')
            {
                //possible encrypted user password
                $sec = new \CRsaSecurity();
                if(($arKeys = $sec->LoadKeys()))
                {
                    $sec->SetKeys($arKeys);
                    $errno = $sec->AcceptFromForm(array('REGISTER'));
                    if($errno == \CRsaSecurity::ERROR_SESS_CHECK)
                        $this->arResult["ERRORS"][] = Loc::getMessage('END_SESSION');
                    elseif($errno < 0)
                        $this->arResult["ERRORS"][] = Loc::getMessage('PASSWORD_DECODE_ERROR');//GetMessage("main_register_decode_err", array("#ERRCODE#"=>$errno));
                }
            }

            foreach ($this->arFields as $key)
            {
                $this->arResult["VALUES"][$key] = $data[mb_strtolower($key)];
                if (in_array($key, $this->reuqireFields) && trim($this->arResult["VALUES"][$key]) == '')
                    $this->arResult["ERRORS"][$key] = Loc::getMessage('REUQIRED_FIELD');
            }

            $USER_FIELD_MANAGER->EditFormAddFields("USER", $this->arResult["VALUES"]);
            if (!$USER_FIELD_MANAGER->CheckFields("USER", 0, $this->arResult["VALUES"]))
            {
                $e = $APPLICATION->GetException();
                $this->arResult["ERRORS"][] = mb_substr($e->GetString(), 0, -4); //cutting "<br>"
                $APPLICATION->ResetException();
            }

            if(count($this->arResult["ERRORS"]) > 0)
            {
                if(\COption::GetOptionString("main", "event_log_register_fail", "N") === "Y")
                {
                    $arError = $this->arResult["ERRORS"];
                    foreach($arError as $key => $error)
                        if(intval($key) == 0 && $key !== 0)
                            $arError[$key] = str_replace("#FIELD_NAME#", '"'.$key.'"', $error);
                    \CEventLog::Log("SECURITY", "USER_REGISTER_FAIL", "main", false, implode("<br>", $arError));
                }
            }
            else // if there's no any errors - create user
            {
                $this->arResult['VALUES']["GROUP_ID"] = array();
                $def_group = \COption::GetOptionString("main", "new_user_registration_def_group", "");
                if ($def_group != "")
                    $this->arResult['VALUES']["GROUP_ID"] = explode(",", $def_group);

                $bConfirmReq = ($this->arResult["USE_EMAIL_CONFIRMATION"] === "Y");
                $active = ($bConfirmReq || $this->arResult["PHONE_REQUIRED"] ? "N" : "Y");

                $this->arResult['VALUES']["CHECKWORD"] = md5(\CMain::GetServerUniqID() . uniqid());
                $this->arResult['VALUES']["~CHECKWORD_TIME"] = $DB->CurrentTimeFunction();
                $this->arResult['VALUES']["ACTIVE"] = $active;
                $this->arResult['VALUES']["CONFIRM_CODE"] = ($bConfirmReq ? randString(8) : "");
                $this->arResult['VALUES']["LID"] = SITE_ID;
                $this->arResult['VALUES']["LANGUAGE_ID"] = LANGUAGE_ID;

                $this->arResult['VALUES']["USER_IP"] = $_SERVER["REMOTE_ADDR"];
                $this->arResult['VALUES']["USER_HOST"] = @gethostbyaddr($_SERVER["REMOTE_ADDR"]);

                if ($this->arResult["VALUES"]["AUTO_TIME_ZONE"] <> "Y" && $this->arResult["VALUES"]["AUTO_TIME_ZONE"] <> "N")
                    $this->arResult["VALUES"]["AUTO_TIME_ZONE"] = "";

                $bOk = true;

                $events = GetModuleEvents("main", "OnBeforeUserRegister", true);
                foreach ($events as $arEvent) {
                    if (ExecuteModuleEventEx($arEvent, array(&$this->arResult['VALUES'])) === false) {
                        if ($err = $APPLICATION->GetException())
                            $this->arResult['ERRORS'][] = $err->GetString();

                        $bOk = false;
                        break;
                    }
                }

                $ID = 0;
                $user = new \CUser();
                if ($bOk) {
                    $ID = $user->Add($this->arResult["VALUES"]);
                }

                if (intval($ID) > 0)
                {
                    $register_done = true;

                    // authorize user
                        if (!$arAuthResult = $USER->Login($this->arResult["VALUES"]["LOGIN"], $this->arResult["VALUES"]["PASSWORD"]))
                            $this->arResult["ERRORS"][] = $arAuthResult;
                    $this->arResult['VALUES']["USER_ID"] = $ID;

                    $arEventFields = $this->arResult['VALUES'];
                    unset($arEventFields["PASSWORD"]);
                    unset($arEventFields["CONFIRM_PASSWORD"]);

                    $event = new \CEvent();
                    $event->SendImmediate("NEW_USER", SITE_ID, $arEventFields);
                    if($bConfirmReq)
                        $event->SendImmediate("NEW_USER_CONFIRM", SITE_ID, $arEventFields);
                }
                else
                {
                    $this->arResult["ERRORS"]['OTHER'] = $user->LAST_ERROR;
                }

                if(count($this->arResult["ERRORS"]) <= 0)
                {
                    if(\COption::GetOptionString("main", "event_log_register", "N") === "Y")
                        \CEventLog::Log("SECURITY", "USER_REGISTER", "main", $ID);
                }
                else
                {
                    if(\COption::GetOptionString("main", "event_log_register_fail", "N") === "Y")
                        \CEventLog::Log("SECURITY", "USER_REGISTER_FAIL", "main", $ID, implode("<br>", $this->arResult["ERRORS"]));
                }

                $events = GetModuleEvents("main", "OnAfterUserRegister", true);
                foreach ($events as $arEvent)
                    ExecuteModuleEventEx($arEvent, array(&$this->arResult['VALUES']));
            }
        } else
            $this->arResult["ERRORS"]['OTHER'] =  Loc::getMessage('ALREADY_AUTHORIZED');

        return $this->arResult;
    }
}