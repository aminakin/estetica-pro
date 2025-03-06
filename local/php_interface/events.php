<?php

use Bitrix\Main\EventManager;
use Local\Discounts\DiscountCore;

$eventManager = EventManager::getInstance();

$eventManager->addEventHandler('sale', 'OnSaleComponentOrderProperties', [DiscountCore::class, 'init'], false, 1000);
