<?php

namespace ApiLib\API\Notifications;

use ApiLib\API\User\PushSettingsService;
use ApiLib\Tables\OneSignalPushTable;
use Bitrix\Main\Config\Option;
use Local\Notifications\NotificationManager;

class BonusNotificationService
{
    private $countDayWarning = 15;

    private $currentTime;

    private $pushSettingsService;

    public function __construct()
    {
        \CModule::IncludeModule("iblock");
        \CModule::IncludeModule("main");
        \CModule::IncludeModule("sale");
        \CModule::IncludeModule("logictim.balls");

        $this->pushSettingsService = new PushSettingsService();
    }

    public function start()
    {
        $userBonuses = $this->getUsersWithBonuses();

        $notificationManager = new NotificationManager();

        foreach($userBonuses as $userBonus) {
            $notificationManager->append([
                'userList' => $userBonus['userList'],
                'body' => 'Через ' . $this->countDayWarning . ' дней у вас закончится ' . $userBonus['bonus'] . ' бонусов!',
                'channelList' => ['OneSignalPush'],
                'dictionary' => [],
                'title' => 'Бонусы',
                'userParams' => ['type' => 'bonus'],
                'dateInsert' => new \Bitrix\Main\Type\DateTime(),
                'successExec' => 'N',
            ]);
        }

        Option::set('notification', 'timeBonusNotificationRun', $this->currentTime);
    }

    private function getUsersWithBonuses()
    {
        $userBonuses = $this->getBonusForWarning();

        return $this->replaceBonusDataToPushData($userBonuses);
    }

    private function getBonusForWarning(): array
    {
        $arMessages = array();
        $timeStart = Option::get('notification', 'timeBonusNotificationRun', time());
        $this->currentTime = time();

        $operationsStatus = \cHelper::LiveStatus();

        $dateStart = date('Y-m-d H:i:s', $timeStart + $this->countDayWarning*86400);
        $dateEnd = date('Y-m-d H:i:s', $this->currentTime + $this->countDayWarning*86400);

        $arFilterWarning = Array(
            'IBLOCK_CODE' => 'logictim_bonus_operations',
            '<=PROPERTY_LIVE_DATE' => $dateEnd,
            '>=PROPERTY_LIVE_DATE' => $dateStart,
            'PROPERTY_LIVE_STATUS' => $operationsStatus["ACTIVE"],
            ">PROPERTY_BALLANCE" => 0,
        );

        $userIdsDisable = $this->pushSettingsService->getAllDisable();
        if (count($userIdsDisable))
            $arFilterWarning['!PROPERTY_USER'] = $userIdsDisable;

        $resWarning = \CIBlockElement::GetList(
            array("ID" => "ASC"),
            $arFilterWarning,
            false,
            false,
            array("ID", "IBLOCK_ID", "NAME", "DATE_CREATE", "PROPERTY_*" )
        );

        $usersIdWarning = array();


        while($obWarning = $resWarning->GetNextElement()) {
            $arOpWarning = $obWarning->GetFields();
            $arOpPropsWarning = $obWarning->GetProperties();
            $arOpWarning = array_merge($arOpWarning, $arOpPropsWarning);
            $usersIdWarning[] = $arOpWarning["USER"]["VALUE"];

            if(isset($arMessages[$arOpWarning["USER"]["VALUE"]]))
            {
                $arMessages[$arOpWarning["USER"]["VALUE"]]["OPERATIONS_SUM"] = $arMessages[$arOpWarning["USER"]["VALUE"]]["OPERATIONS_SUM"] + $arOpWarning["BALLANCE"]["VALUE"];
            }
            else
            {
                $arMessages[$arOpWarning["USER"]["VALUE"]] = array(
                    "USER_ID" => $arOpWarning["USER"]["VALUE"],
                    "OPERATIONS_SUM" => $arOpWarning["BALLANCE"]["VALUE"],
                    "LIVE_DATE" => $arOpWarning["LIVE_DATE"]["VALUE"]
                );
            }
        }

        return $arMessages;
    }

    private function replaceBonusDataToPushData($bonusData)
    {
        $result = [];
        if (count($bonusData)) {
            $userIds = array_keys($bonusData);

            $pushUserRes = OneSignalPushTable::query()->
            setSelect(['user_token' => 'DEVICE_TOKEN', 'USER_ID'])->
            whereIn("USER_ID", $userIds)->
            setDistinct()->
            exec();

            while ($pushUser = $pushUserRes->fetch()) {
                $result[] = [
                    'userList' => [$pushUser['user_token']],
                    'bonus' => $bonusData[$pushUser['USER_ID']]['OPERATIONS_SUM'],
                ];
            }
        }

        return $result;
    }

}
