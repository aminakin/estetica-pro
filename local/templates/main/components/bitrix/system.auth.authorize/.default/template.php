<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
ShowMessage($arParams["~AUTH_RESULT"]);
ShowMessage($arResult['ERROR_MESSAGE']);
?>

<? $APPLICATION->IncludeComponent(
                                    "bitrix:system.auth.form",
                                    "auth_header",
                                    array(
                                        "FORGOT_PASSWORD_URL" => "/login/forgot.php",
                                        "PROFILE_URL" => "/personal",
                                        "REGISTER_URL" => "/login/registration.php",
                                        "SHOW_ERRORS" => "Y",
                                        "COMPONENT_TEMPLATE" => "auth_header"
                                    ),
                                    false
                                );
                                ?>
