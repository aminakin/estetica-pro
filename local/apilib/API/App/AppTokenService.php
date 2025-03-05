<?php

namespace ApiLib\API\App;

use ApiLib\Tables\OneSignalPushTable;
use Bitrix\Main\HttpRequest;

class AppTokenService
{
    /**
     * @throws \Bitrix\Main\LoaderException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Exception
     */
    public function store(HttpRequest $request) {
        $requestValues = $request->getValues();

        $result = Array("status" => "failed");

        if (!\Bitrix\Main\Loader::includeModule("pull"))
        {
            $result["error"] = "Module 'pull' is not installed";
        }
        else
        {
            global $USER;
            $data = null;
            $userId = $USER->GetID();
            if (!$userId)
            {
                $userId = 0;
            }

            if ($requestValues["user_token"])
            {
                $res = array("status" => "failed", "error" => "some unknown error");
                $data = array(
                    "DEVICE_TOKEN" => $requestValues["user_token"],
                    "DEVICE_ID" => $requestValues["device_id"],
                    "DEVICE_TYPE" => $requestValues["device_type"],
                    "APP_ID" => '',
                    "DATE_AUTH" => ConvertTimeStamp(microtime(true), "FULL"),
                    "USER_ID" => $userId
                );

                $pushResult = OneSignalPushTable::query()->addSelect('*')->where("DEVICE_ID", $data["DEVICE_ID"])->exec();
                $arToken = $pushResult->fetch();
                $status = "failed";

                if ($arToken["ID"])
                {
                    $res = OnesignalPushTable::update($arToken["ID"], $data);
                    if (!count($res->getErrors())) {
                        $status = "updated";
                    } else {
                        $data = $res->getErrorMessages();
                    }
                }
                else
                {
                    $res = OnesignalPushTable::add($data);
                    if (!count($res->getErrors()))
                    {
                        $status = "registered";
                    } else {
                        $data = $res->getErrorMessages();
                    }
                }


                $result["status"] = $status;
            }
        }
        if (is_array($data))
            $result = array_merge($result, $data);

        return $result;
    }
}
