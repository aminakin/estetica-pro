<?php
/*
 *
 * 
 *
 */
use Bitrix\Main\Loader;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
Loader::includeModule('main');

/*echo '<pre>';
print_r($_POST);
echo '</pre>';
*/
$APPLICATION->IncludeComponent(
    "smedia:system.auth.forgotpasswd",
    "forpasswd.ajax",
    Array()
);

/*$APPLICATION->IncludeComponent(
    "bitrix:system.auth.form", 
    "auth.form", 
    Array(
        "FORGOT_PASSWORD_URL" => "/login/forgot.php",   // Страница забытого пароля
        "PROFILE_URL" => "/personal",   // Страница профиля
        "REGISTER_URL" => "/login/registration.php",    // Страница регистрации
        "SHOW_ERRORS" => "Y",   // Показывать ошибки
        "COMPONENT_TEMPLATE" => "auth_header"
    ),
    false
);*/
?>
