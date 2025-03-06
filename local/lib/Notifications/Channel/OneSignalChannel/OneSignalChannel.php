<?php

namespace Local\Notifications\Channel\OneSignalChannel;

use ApiLib\API\Settings\BitrixSettings;
use GuzzleHttp\Client;
use Local\Notifications\IChannel;
use onesignal\client\api\DefaultApi;
use onesignal\client\Configuration;
use onesignal\client\model\Notification;
use onesignal\client\model\StringMap;
use Local\Notifications\IMessage;

class OneSignalChannel implements IChannel
{
    private IMessage $message;

    private DefaultApi $apiInstance;

    private string $appId;

    private string $appKeyToken;

    private string $userKeyToken;

    public function __construct()
    {
        $this->appId = BitrixSettings::getApiVarByName('APP_ID');
        $this->appKeyToken = BitrixSettings::getApiVarByName('APP_KEY_TOKEN');
        $this->userKeyToken = BitrixSettings::getApiVarByName('USER_KEY_TOKEN');

        $config = Configuration::getDefaultConfiguration()
            ->setAppKeyToken($this->appKeyToken)
            ->setUserKeyToken($this->userKeyToken);

        $this->apiInstance = new DefaultApi(
            new Client(),
            $config,
        );
    }

    public function setMessage(IMessage $message)
    {
        $this->message = $message;
    }

    /**
     * @throws \onesignal\client\ApiException
     */
    public function send(): array
    {
        $userList = $this->message->getUserList();
        $userListChunk = array_chunk($userList, 2000);
        $result = [];
        foreach($userListChunk as $itemUserList) {
            $this->message->setUserList($itemUserList);
            $notification = $this->createNotificationUserIds();
            $resultItem = $this->apiInstance->createNotification($notification)->getErrors();
            if ($resultItem !== null)
                $result[] = $resultItem;
        }

        return $result;
    }

    private function createNotificationUserIds(): Notification
    {
        $content = new StringMap();
        $content->setEn($this->message->getBody());
        $content->setRu($this->message->getBody());

        $title = new StringMap();
        $title->setEn($this->message->getTitle());
        $title->setRu($this->message->getTitle());

        $notification = new Notification();
        $notification->setAppId($this->appId);
        $notification->setContents($content);
        $notification->setHeadings($title);
//        $notification->setIncludeExternalUserIds($this->message->getUserList());
        $notification->setIncludePlayerIds($this->message->getUserList());
        $notification->setChannelForExternalUserIds("push");
        $notification->setData($this->message->getUserParams());

        return $notification;
    }

    public function getName(): string
    {
        return 'OneSignalPush';
    }
}
