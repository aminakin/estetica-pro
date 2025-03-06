<?php
/*
 * ajax форма Забыли пароль на сайте в выпадающем окне
 *
 */
use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Loader::includeModule('main');


$APPLICATION->IncludeComponent(
    "smedia:system.auth.forgotpasswd",
    "forpasswd.ajax",
    Array()
);
?>
