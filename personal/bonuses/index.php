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
    ); ?>
    <div class="main_profile_wrapper bonus">
        <div class="left_sidebar">
            <?  $rsUser = CUser::GetByID($USER->GetID());
                $arUser = $rsUser->Fetch(); 
            if (!empty($arUser['UF_QUANTITY_DATE'])) { ?>
                <div class="bonus__history bonus-history">
                    <div class="bonus-history__title">
                        <span>Всего бонусов</span>
                        <span><?= $arUser['UF_LOGICTIM_BONUS']?></span>
                    </div>
                    <div class="bonus-history__header">
                        <div class="bonus-history__header-count">Бонусы</div>
                        <div class="bonus-history__header-date">Срок действия до</div>
                    </div>
                    <div class="bonus-history__list">
                        <? $tableBonus = $arUser['UF_QUANTITY_DATE'];
                        $tableBonus = explode(";", $tableBonus); ?>
                        <? foreach ($tableBonus as $bonusItems):
                            $bonusItems = explode("/", $bonusItems); ?>
                            <div class="bonus-history__item">
                                <div class="bonus-history__item-count"><?= $bonusItems[0] ?></div>
                                <div class="bonus-history__item-date"><?= $bonusItems[1]; ?></div>
                            </div>
                        <? endforeach; ?>
                    </div>
                    <div class="bonus-history__desc">
                    Бонусы аннулируются в указанную дату — не упустите шанс их<br> потратить!
                    </div>
                </div>
            <? } ?>
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
            );
            ?>
        </div>
        <? $APPLICATION->IncludeComponent(
            "addamant:main.profile",
            "bonus",
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
<? } else {
    LocalRedirect("/");
} ?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>