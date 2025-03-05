<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0) 
    LocalRedirect($backurl);

$APPLICATION->SetTitle("Вход на сайт");
?><div id="auth">
<? if (!$USER->IsAuthorized()): ?>
                            <div class="auth-container">
                                <div class="h2 auth-logo"><?= GetMessage('login'); ?></div>
                                <div class="separate">/</div>
                                <a href="#register" class="linkOpen h2 auth-logo"><?= GetMessage('REGISTRATION'); ?></a>
                            </div>
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
                        <? endif; ?>
                        </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>