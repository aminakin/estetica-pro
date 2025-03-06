<?php

use Bitrix\Main\Application;

define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('STOP_STATISTICS', true);


require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');
global $APPLICATION;
$APPLICATION->RestartBuffer();


$context = Application::getInstance()->getContext();
$request = $context->getRequest();



if ($request->get('action') !== null) {
    switch ($request->get('action')) {
        case 'NOTIFICATE_CLIENT':

            die(json_encode(['SUCCESS' => true,
                'BASKKET_TRIGGER' => \Local\BasketGiftModule\BasketTrigger::checkBasketItems(),
            ]));
            break;
    }
}