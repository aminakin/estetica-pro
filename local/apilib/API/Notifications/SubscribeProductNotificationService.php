<?php

namespace ApiLib\API\Notifications;

use ApiLib\API\User\PushSettingsService;
use ApiLib\Tables\OneSignalPushTable;
use Bitrix\Catalog\EO_Product_Result;
use Bitrix\Catalog\ProductTable;
use Bitrix\Catalog\SubscribeTable;
use Local\Notifications\NotificationManager;

class SubscribeProductNotificationService
{
    private $oldProduct = false;

    private $pushSettingsService;

    public function __construct()
    {
        if (!\Bitrix\Main\Loader::includeModule("iblock")) {
            throw new \Exception("Модуль iblock не удалось подключить");
        }
        if (!\Bitrix\Main\Loader::includeModule("catalog")) {
            throw new \Exception("Модуль catalog не удалось подключить");
        }

        $this->pushSettingsService = new PushSettingsService();
    }

    public function setOldProduct($productId) {
        $this->oldProduct = $this->getProductById($productId);
    }

    public function start($productId)
    {
        $product = $this->getProductById($productId);

        if ($product !== false && $product['AVAILABLE'] === 'Y' && ($this->oldProduct === false || gettype($this->oldProduct) === 'array' && $this->oldProduct['AVAILABLE'] !== 'Y')) {
            $subsscribeUserIds = $this->getSubscribeProductUserId($productId);
            if (count($subsscribeUserIds)) {
                $userList = $this->getUserTokensByIds($subsscribeUserIds);

                if (count($userList)) {

                    $notificationManager = new NotificationManager();

                    $notificationManager->append([
                        'userList' => $userList,
                        'body' => 'Поступление товара на который вы подписаны: "' . $product['NAME'] . '"',
                        'channelList' => ['OneSignalPush'],
                        'dictionary' => [],
                        'title' => 'Новое поступление!',
                        'userParams' => ['type' => 'product', 'id' => $productId],
                        'dateInsert' => new \Bitrix\Main\Type\DateTime(),
                        'successExec' => 'N',
                    ]);
                }
            }
        }
    }

    private function getProductById($productId)
    {
        /** @var EO_Product_Result $product */
        return ProductTable::query()
            ->setSelect(['*', 'NAME' => 'IBLOCK_ELEMENT.NAME'])
            ->where('ID', $productId)
            ->where('IBLOCK_ELEMENT.ACTIVE', 'Y')
            ->exec()
            ->fetch();
    }

    private function getSubscribeProductUserId($productId)
    {
        $userIdsDisable = $this->pushSettingsService->getAllDisable();
        $query = SubscribeTable::query();
        $query->setSelect(['userId' => 'USER_ID'])
            ->where('ITEM_ID', $productId)
            ->whereNotNull('USER_ID');
        if (count($userIdsDisable))
            $query->whereNotIn('USER_ID', $userIdsDisable);

        $arCatalogSubscribe = $query->exec()->fetchAll();

        return array_map(function($item) {
            return $item['userId'];
        }, $arCatalogSubscribe);
    }

    private function getUserTokensByIds($userIds)
    {
        $pushUserList = OneSignalPushTable::query()
            ->setDistinct()
            ->setSelect(['user_token' => 'DEVICE_TOKEN', 'USER_ID'])
            ->whereIn('USER_ID', $userIds)
            ->exec()
            ->fetchAll();

        return  array_map(function($item) {
            return $item['user_token'];
        }, $pushUserList);
    }
}
