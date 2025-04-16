<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$arChildParams = array(
    "PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
    "PATH_TO_CANCEL" => $arResult["PATH_TO_CANCEL"],
    "PATH_TO_COPY" => $arResult["PATH_TO_LIST"] . '?ID=#ID#',
    "PATH_TO_BASKET" => $arParams["PATH_TO_BASKET"],
    "PATH_TO_PAYMENT" => $arParams["PATH_TO_PAYMENT"],
    "SAVE_IN_SESSION" => $arParams["SAVE_IN_SESSION"],
    "ORDERS_PER_PAGE" => $arParams["ORDERS_PER_PAGE"],
    "SET_TITLE" => $arParams["SET_TITLE"],
    "ID" => $arResult["VARIABLES"]["ID"],
    "NAV_TEMPLATE" => $arParams["NAV_TEMPLATE"],
    "ACTIVE_DATE_FORMAT" => $arParams["ACTIVE_DATE_FORMAT"],
    "HISTORIC_STATUSES" => $arParams["HISTORIC_STATUSES"],
    "ALLOW_INNER" => $arParams["ALLOW_INNER"],
    "ONLY_INNER_FULL" => $arParams["ONLY_INNER_FULL"],
    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
    "CACHE_TIME" => $arParams["CACHE_TIME"],
    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
    "DEFAULT_SORT" => $arParams["ORDER_DEFAULT_SORT"],
    "RESTRICT_CHANGE_PAYSYSTEM" => $arParams["RESTRICT_CHANGE_PAYSYSTEM"],
    "AJAX" => $arParams['AJAX']
);

foreach ($arParams as $key => $val)
    if (strpos($key, "STATUS_COLOR_") !== false && strpos($key, "~") !== 0)
        $arChildParams[$key] = $val;
?>

<div class="main-profile-orders section <? $APPLICATION->ShowViewContent('empty_orders'); ?>">
    <div class="main-profile-orders__sidebar left_sidebar">
        <? include 'parts/filter.php' ?>

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
        ); ?>
    </div>
    <div class="main-profile-orders__content">
        <? $APPLICATION->IncludeComponent(
            "addamant:sale.personal.order.list",
            "",
            $arChildParams,
            $component
        ); ?>
    </div>
</div>