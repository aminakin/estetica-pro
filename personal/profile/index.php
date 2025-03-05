<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Профессиональные материалы для маникюра и наращивания ресниц. Известные и востребованные бренды по официальным ценам производителей. Отправляем в любой город РФ. Быстрая онлайн-оплата заказа на сайте.");
$APPLICATION->SetTitle("Мой аккаунт");

?>

<?
global $USER;
if ($USER->IsAuthorized()) {

    $APPLICATION->IncludeComponent(
        "bitrix:menu",
        "personal_menu",
        array(
            "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
            "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
            "DELAY" => "N",    // Откладывать выполнение шаблона меню
            "MAX_LEVEL" => "1",    // Уровень вложенности меню
            "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
            "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
            "MENU_CACHE_TYPE" => "N",    // Тип кеширования
            "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
            "ROOT_MENU_TYPE" => "profil_menu",    // Тип меню для первого уровня
            "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
            "COMPONENT_TEMPLATE" => ".default"
        ),
        false
    );

?>
    <div class="main_profile_wrapper">
        <div class="left_sidebar">
            <? $APPLICATION->IncludeComponent(
                    "bitrix:menu",
                    "personal_menu_left",
                    array(
                        "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                        "CHILD_MENU_TYPE" => "left",    // Тип меню для остальных уровней
                        "DELAY" => "N",    // Откладывать выполнение шаблона меню
                        "MAX_LEVEL" => "1",    // Уровень вложенности меню
                        "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                        "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
                        "MENU_CACHE_TYPE" => "N",    // Тип кеширования
                        "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
                        "ROOT_MENU_TYPE" => "profil_menu_left",    // Тип меню для первого уровня
                        "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
                        "COMPONENT_TEMPLATE" => ".default"
                    ),
                    false
                );?>
        </div>
        
        <div class="content">
            <? $APPLICATION->IncludeComponent(
                "bitrix:main.profile",
                ".default",
                array(
                    "CHECK_RIGHTS" => "N",
                    "SEND_INFO" => "N",
                    "SET_TITLE" => "Y",
                    "USER_PROPERTY_NAME" => array(
                        0 => "UF_DISCOUNT_CARD_NUM",
                    ),
                    "COMPONENT_TEMPLATE" => ".default",
                    "USER_PROPERTY" => array(
                        0 => "UF_DISCOUNT_CARD_NUM",
                    )
                ),
                false
            ); ?>
        </div>
    </div>
    <? }else{?>
        <?$APPLICATION->SetTitle("Авторизация");?>
        <div class="auth_page" id="auth">
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
            <div class="basket-checkout-container bonus" data-entity="basket-checkout-aligner">
                            <a href="/bonus-system/">
                                <img src="/local/templates/main/images/info.svg">
                                <?= GetMessage('SBB_BASKET_BONUS_INFO'); ?>        
                                <img class="arrow-right" src="/local/templates/main/images/arrow-right.svg">
                            </a>
                        </div>
        </div> 
    <?} ?>
    <div style="clear: both;"></div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>