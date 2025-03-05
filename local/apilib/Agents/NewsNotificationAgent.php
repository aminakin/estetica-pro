<?php

namespace ApiLib\Agents;

use ApiLib\API\Notifications\NewsNotificationService;

class NewsNotificationAgent
{
    /**
     * @throws \Exception
     */
    public static function run(): string
    {
        $service = new NewsNotificationService();
        $service->start();

        return '\ApiLib\Agents\NewsNotificationAgent::run();';
    }
}
