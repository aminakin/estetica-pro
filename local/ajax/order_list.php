<?php 
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
	global $APPLICATION;

    $APPLICATION->IncludeComponent("bitrix:sale.personal.order", "estetica", Array(
        "ACTIVE_DATE_FORMAT" => "d.m.Y",	// Формат показа даты
        "CACHE_GROUPS" => "Y",	// Учитывать права доступа
        "CACHE_TIME" => "3600",	// Время кеширования (сек.)
        "CACHE_TYPE" => "A",	// Тип кеширования
        "CUSTOM_SELECT_PROPS" => "",	// Дополнительные свойства инфоблока
        "DETAIL_HIDE_USER_INFO" => array(	// Не показывать в информации о пользователе
            0 => "0",
        ),
        "HISTORIC_STATUSES" => array(	// Перенести в историю заказы в статусах
        ),
        "NAV_TEMPLATE" => "",	// Имя шаблона для постраничной навигации
        "ORDERS_PER_PAGE" => "20",	// Количество заказов на одной странице
        "ORDER_DEFAULT_SORT" => "STATUS",	// Сортировка заказов
        "PATH_TO_BASKET" => "/personal/cart",	// Страница с корзиной
        "PATH_TO_CATALOG" => "/catalog/",	// Путь к каталогу
        "PATH_TO_PAYMENT" => "/personal/payment.php",	// Страница подключения платежной системы
        "PROP_1" => "",	// Не показывать свойства для типа плательщика "Физическое лицо" (s1)
        "PROP_2" => "",	// Не показывать свойства для типа плательщика "Юридическое лицо" (s1)
        "RESTRICT_CHANGE_PAYSYSTEM" => array(	// Запретить смену платежной системы у заказов в статусах
            0 => "F",
        ),
        "SAVE_IN_SESSION" => "Y",	// Сохранять установки фильтра в сессии пользователя
        "SEF_FOLDER" => "/personal/orders/",	// Каталог ЧПУ (относительно корня сайта)
        "SEF_MODE" => "Y",	// Включить поддержку ЧПУ
        "SET_TITLE" => "Y",	// Устанавливать заголовок страницы
        "STATUS_COLOR_F" => "gray",	// Цвет статуса "Выполнен"
        "STATUS_COLOR_N" => "green",	// Цвет статуса "Принят, ожидается оплата"
        "STATUS_COLOR_PSEUDO_CANCELLED" => "red",	// Цвет отменённых заказов
        "COMPONENT_TEMPLATE" => ".default",
        "SEF_URL_TEMPLATES" => array(
            "list" => "",
            "detail" => "detail/#ID#",
            "cancel" => "cancel/#ID#",
        ),
        "AJAX" => "Y"
    ),false
);?>