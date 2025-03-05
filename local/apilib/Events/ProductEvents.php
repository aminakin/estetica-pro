<?php

namespace ApiLib\Events;

use ApiLib\API\Notifications\SubscribeProductNotificationService;
use ApiLib\Tables\OneSignalPushTable;
use Bitrix\Catalog\ProductTable;
use Bitrix\Catalog\SubscribeTable;
use Local\Notifications\NotificationManager;

class ProductEvents
{
    private static $subscribeProductNotification;

    public static function OnBeforeProductUpdateHandler(\Bitrix\Catalog\Model\Event $event ) {
        $id = $event->getParameter("id");

        self::$subscribeProductNotification[$id] = new SubscribeProductNotificationService();
        /** @var SubscribeProductNotificationService $service */
        $service = &self::$subscribeProductNotification[$id];
        $service->setOldProduct($id);
    }

    public static function OnAfterProductUpdateHandler(\Bitrix\Catalog\Model\Event $event ) {
       $id = $event->getParameter("id");

       if (self::$subscribeProductNotification[$id] instanceof SubscribeProductNotificationService) {
           /** @var SubscribeProductNotificationService $service */
           $service = &self::$subscribeProductNotification[$id];
           $service->start($id);

           unset(self::$subscribeProductNotification[$id]);
       }
    }
}
