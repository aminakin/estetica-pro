<?php

namespace ApiLib\Agents;

use Local\Notifications\NotificationManager;

class DispatchNotificationAgent
{
    public static function run()
    {
        set_time_limit(4*60);

        $nManager = new NotificationManager();
        $nManager->dispatch();

        return '\ApiLib\Agents\DispatchNotificationAgent::run();';
    }
}
