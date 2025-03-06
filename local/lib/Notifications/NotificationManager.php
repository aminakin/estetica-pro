<?php

namespace Local\Notifications;

use ApiLib\API\Settings\BitrixSettings;
use Bitrix\Main\Entity\DataManager;
use Local\Notifications\DTO\Message;

class NotificationManager
{
    private array $channels;

    public function __construct()
    {
        $channels = BitrixSettings::getApiVarByName('CHANNELS');
        foreach ($channels as $chanel) {
            if (class_exists($chanel)) {
                /** @var IChannel $clsChannel */
                $clsChannel = new $chanel;
                $this->channels[$clsChannel->getName()] = $clsChannel;
            }
        }
    }

    /**
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Exception
     */
    public function dispatch()
    {
        $messageList = PushNotificationTable::getMessages([
            'select' => ['*'],
            'filter' => [
                'SUCCESS_EXEC' => 'N'
            ]
        ]);

        $updateErrors = [];
        $sendErrors = [];

        /** @var IMessage $message */
        foreach ($messageList as $message) {
            $mChannelList = $message->getChannelList();
            /** @var string $chanelName */
            foreach ($mChannelList as $chanelName) {
                if (isset($this->channels[$chanelName])) {
                    /** @var IChannel $channel */
                    $channel = $this->channels[$chanelName];
                    $channel->setMessage($message);
                    $errors = $channel->send();

                    if (count($errors)) {
                        $message->setSuccessExec('E');
                        $sendErrors[] = $errors;
                    } else {
                        $message->setSuccessExec('Y');
                        $message->setDateExec(new \Bitrix\Main\Type\DateTime());
                    }

                    $updateError = PushNotificationTable::update($message->getId(), $message);
                    if (!$updateError->isSuccess())
                        $updateErrors[] = $updateError->getErrorMessages();
                }
            }
        }

        return [$updateErrors, $sendErrors];
    }

    /**
     * @throws \Exception
     */
    public function append($data)
    {
        $message = new Message($data);

        return PushNotificationTable::add($message);
    }
}
