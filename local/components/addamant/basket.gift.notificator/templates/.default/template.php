<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);
CJSCore::Init(array('fx', 'popup', 'ajax'));
?>

<div class="sale-popup">
    <a href="#" class="close-popup" onclick="basketTriggerNotificator.closePopup()"><span></span><span></span></a>
    <div id="sale-popup_content"></div>
</div>
<div class="sale-popup-overlay" onclick="basketTriggerNotificator.closePopup()"></div>