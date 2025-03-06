<?php
/*
 * форма восстановления пароля
 *
 *
*/

use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$APPLICATION->IncludeComponent(
    "smedia:system.auth.forgotpasswd",
    "forpasswd",
    Array()
);

?>