<?php

namespace ApiLib\API\Notifications;

use ApiLib\API\User\PushSettingsService;
use ApiLib\Tables\OneSignalPushTable;
use Bitrix\Main\Config\Option;
use Local\Notifications\NotificationManager;

class NewsNotificationService
{
    private $iblockId = 4;

    private $currentTime;

    private $pushSettingsService;

    public function __construct()
    {
        if (!\Bitrix\Main\Loader::includeModule("iblock")) {
            throw new \Exception("Модуль iblock не удалось подключить");
        }

        $this->pushSettingsService = new PushSettingsService();
    }

    /**
     * @throws \Exception
     */
    public function start()
    {
        $newsList = $this->getNewNews();
        $userList = $this->getUserNewsNotification();

        if (count($userList)) {
            $notificationManager = new NotificationManager();

            foreach ($newsList as $news) {
                $notificationManager->append([
                    'userList' => $userList,
                    'body' => 'Новая запись в новостях: "' . $news['NAME'] . '"',
                    'channelList' => ['OneSignalPush'],
                    'dictionary' => [],
                    'title' => 'Новости',
                    'userParams' => ['type' => 'news', 'id' => $news['ID']],
                    'dateInsert' => new \Bitrix\Main\Type\DateTime(),
                    'successExec' => 'N',
                ]);
            }
        }

        Option::set('notification', 'timeNewsNotificationRun', $this->currentTime);
    }

    private function getNewNews(): array
    {
        $result = [];
        $timeStart = Option::get('notification', 'timeNewsNotificationRun', time());
        $this->currentTime = time();
        $res = \CIBlockElement::GetList(
            [
                'active_from' => 'desc',
                "DATE_CREATE" => "DESC"
            ],
            [
                'IBLOCK_ID' => $this->iblockId,
                'ACTIVE' => 'Y',
                [
                    'LOGIC' => 'OR',
                    [
                        '>=DATE_ACTIVE_FROM' => date('d.m.Y H:i:s', $timeStart),
                        '<=DATE_ACTIVE_TO' => date('d.m.Y H:i:s', $this->currentTime),
                    ],
                    [
                        '>=DATE_ACTIVE_FROM' => date('d.m.Y H:i:s', $timeStart),
                        '==DATE_ACTIVE_TO' => null,
                    ],
                    [
                        '==DATE_ACTIVE_FROM' => null,
                        '>=DATE_CREATE' => date('d.m.Y H:i:s', $timeStart),
                        '<=DATE_ACTIVE_TO' => date('d.m.Y H:i:s', $this->currentTime),
                    ],
                    [
                        '==DATE_ACTIVE_FROM' => null,
                        '>=DATE_CREATE' => date('d.m.Y H:i:s', $timeStart),
                        '==DATE_ACTIVE_TO' => null,
                    ],
                ],
            ],
        );

        while ($row = $res->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    private function getUserNewsNotification(): array
    {
        $userIdsDisable = $this->pushSettingsService->getAllDisable();
        $query = OneSignalPushTable::query();
        $query->setDistinct();
            $query->setSelect([
                'user_token' => 'DEVICE_TOKEN'
            ]);

        if (count($userIdsDisable))
            $query->whereNotIn('USER_ID', $userIdsDisable);

        $resPushList = $query->exec();

        $result = $resPushList->fetchAll();

        return array_map(function($v) {
            return $v['user_token'];
        },$result);
    }
}
