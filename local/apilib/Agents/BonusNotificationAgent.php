<?php

namespace ApiLib\Agents;

use ApiLib\API\Notifications\BonusNotificationService;

class BonusNotificationAgent
{
    /**
     * @throws \Exception
     */
    public static function run(): string
    {
        $service = new BonusNotificationService();
        $service->start();

        return '\ApiLib\Agents\BonusNotificationAgent::run();';
    }
}
