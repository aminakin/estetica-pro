<?

use Bitrix\Main\Page\Asset;
use Bitrix\Main\Application;

CModule::IncludeModule("iblock");
global $USER, $APPLICATION;
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<!DOCTYPE html>
<html lang="<?= LANGUAGE_ID; ?>">

<head>

    <title><? $APPLICATION->ShowTitle(); ?></title>
    <? $APPLICATION->ShowHead(); ?>
    <meta name="yandex-verification" content="96fc0bb06a46bd08" />
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1.0"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
    <!--  block iphone autozoom because of small font -->

    <link rel="apple-touch-icon" sizes="57x57" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/favicon-16x16.png">
    <link rel="manifest" href="<?= SITE_TEMPLATE_PATH; ?>/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <meta property="og:description" content="<?= $APPLICATION->GetProperty("description"); ?>" />

    <?

    $assetManager = Asset::getInstance();
    $styles = [
        SITE_TEMPLATE_PATH . '/fonts/fonts.css',
        SITE_TEMPLATE_PATH . '/js/formstyler/jquery.formstyler.css',
        SITE_TEMPLATE_PATH . '/font-awesome/css/font-awesome.min.css',
        SITE_TEMPLATE_PATH . '/js/fancy/jquery.fancybox.css',
        SITE_TEMPLATE_PATH . '/js/slick/slick.css',
        SITE_TEMPLATE_PATH . '/styles.css'
    ];
    foreach ($styles as $style) {
        $assetManager->addCss($style);
    }

    $scripts = [
        SITE_TEMPLATE_PATH . '/js/jquery-3.6.0.min.js',
        SITE_TEMPLATE_PATH . '/js/formstyler/jquery.formstyler.js',
        SITE_TEMPLATE_PATH . '/js/jquery.lazy.min.js',
        SITE_TEMPLATE_PATH . "/bitrix/js/main/ajax.js",
        SITE_TEMPLATE_PATH . '/js/tablesaw/tablesaw.jquery.js',
        SITE_TEMPLATE_PATH . '/js/tablesaw/tablesaw-init.js',
        SITE_TEMPLATE_PATH . '/js/fancy/jquery.fancybox.min.js',
        SITE_TEMPLATE_PATH . '/js/fancy/jquery.fancybox-media.js',
        SITE_TEMPLATE_PATH . '/js/jquery.maskedinput.min.js',
        SITE_TEMPLATE_PATH . '/js/readmore-symbols.js',
        SITE_TEMPLATE_PATH . '/js/jquery.cookie.min.js',
        SITE_TEMPLATE_PATH . '/js/slick/slick.min.js',
        SITE_TEMPLATE_PATH . '/js/scroll/jquery.mCustomScrollbar.concat.min.js',
        SITE_TEMPLATE_PATH . '/js/script.js'
    ];

    foreach ($scripts as $script) {
        $assetManager->addJs($script);
    }

    if (!$USER->IsAuthorized()) // Для неавторизованного
    {
        $favorites = unserialize(Application::getInstance()->getContext()->getRequest()->getCookie("favorites"));
    } else {
        $idUser = $USER->GetID();
        $rsUser = CUser::GetByID($idUser);
        $arUser = $rsUser->Fetch();
        $favorites = $arUser['UF_FAVORITES'];
    }
    ?>
</head>

<body class="mainPage">
    <? $APPLICATION->ShowPanel(); ?>
    <div id="layer" <? if ($APPLICATION->GetCurDir() != '/personal/cart/') { ?>style="overflow:hidden" <? } ?>>
        <div class="layer_body<?= CSite::inDir('/personal/cart/') ? ' hide_mobile_header' : '' ?>">
            <header>
                <div class="header-wrap">
                    <div class="header-desktop">
                        <div class="header-top" style="display: none;">
                            <div class="header-row">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:menu",
                                    "top",
                                    array(
                                        "ALLOW_MULTI_SELECT" => "N",
                                        "CHILD_MENU_TYPE" => "",
                                        "COMPONENT_TEMPLATE" => "top",
                                        "DELAY" => "N",
                                        "MAX_LEVEL" => "2",
                                        "MENU_CACHE_GET_VARS" => array(),
                                        "MENU_CACHE_TIME" => "3600",
                                        "MENU_CACHE_TYPE" => "A",
                                        "MENU_CACHE_USE_GROUPS" => "Y",
                                        "ROOT_MENU_TYPE" => "top_menu",
                                        "USE_EXT" => "N"
                                    ),
                                    false
                                ); ?>
                                <div class="header-phone">
                                    <? $APPLICATION->IncludeFile('/include/phone.php'); ?>
                                </div>
                            </div>
                        </div>
                        <div class="header-middle">
                            <div class="header-row">
                                <div class="header-row-content">
                                    <div class="header-desc">
                                        <? $APPLICATION->IncludeFile('/include/head-desc.php'); ?>
                                    </div>
                                </div>
                                <div class="logo">
                                    <a href="/">
                                        <?= classBuildSCG::buildSVG('logo_new_svg'); ?>
                                    </a>
                                </div>
                                <div class="header-row-content">
                                    <div class="usket">
                                        <? if ($USER->IsAuthorized()): ?>
                                            <a href="#auth" class="enterLink showPreAuthForm menu-links">
                                                <?= classBuildSCG::buildSVG('user'); ?>
                                                <div class="header-right-menu-text">
                                                    <?= ($USER->GetFullName() != '') ? $USER->GetFullName() : $USER->GetEmail(); ?>
                                                </div>
                                            </a>
                                        <? else: ?>
                                            <a href="#auth" class="enterLink linkOpen menu-links">
                                                <?= classBuildSCG::buildSVG('user'); ?>
                                                <div class="header-right-menu-text"><?= GetMessage('LOGIN_USER'); ?></div>
                                            </a>
                                        <? endif; ?>
                                        <a href="/personal/favorites.php" class="menu-links enterLink-favorites-header<?= (is_array($favorites) && count($favorites) > 0) ? ' favorites_not-empty' : '' ?>">
                                            <?= classBuildSCG::buildSVG('favorite'); ?>
                                            <div class="header-right-menu-text"><?= GetMessage('FAVORITES'); ?></div>
                                        </a>
                                        <? $APPLICATION->IncludeComponent("smedia:basket.small", ".default", array(), false);
                                        clearCart(); ?>
                                    </div>
                                    <div class="fly-prod-wrap"></div>
                                </div>
                            </div>
                        </div>
                        <div class="header-bottom">
                            <div class="header-row">
                                <div class="header-menu">
                                    <div class="header-menu__item brendmenu">
                                        <div class="catalog-top-menu-catalog">
                                            <a href="/catalog/"><?= GetMessage('BRAND'); ?></a>
                                        </div>
                                        <div class="header-menu__item-modal">
                                            <div class="catalog-menu-block">
                                                <? $APPLICATION->IncludeComponent(
                                                    "bitrix:catalog.section.list",
                                                    "catalog-menu-oneline-new",
                                                    array(
                                                        "IBLOCK_TYPE" => 'catalog',
                                                        "IBLOCK_ID" => '14',
                                                        "CACHE_TYPE" => 'A',
                                                        "CACHE_TIME" => '36000000',
                                                        "CACHE_GROUPS" => 'Y',
                                                        "COUNT_ELEMENTS" => 'Y',
                                                        "TOP_DEPTH" => '3',
                                                        "SECTION_URL" => '/catalog/#SECTION_CODE#/',
                                                        "VIEW_MODE" => 'LIST',
                                                        "SHOW_PARENT_NAME" => 'Y',
                                                        "HIDE_SECTION_NAME" => "N",
                                                        "ADD_SECTIONS_CHAIN" => 'Y',
                                                    ),
                                                    false,
                                                    array("HIDE_ICONS" => "Y")
                                                ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="header-menu__item catalogmenu">
                                        <div class="catalog-top-menu-catalog">
                                            <a href="/new-catalog/"><?= GetMessage('CATALOG'); ?></a>
                                        </div>
                                        <div class="header-menu__item-modal">
                                            <div class="catalog-menu-block">
                                                <? $APPLICATION->IncludeComponent(
                                                    "bitrix:catalog.section.list",
                                                    "catalog-menu-oneline-new",
                                                    array(
                                                        "IBLOCK_TYPE" => 'catalog',
                                                        "IBLOCK_ID" => '26',
                                                        "CACHE_TYPE" => 'A',
                                                        "CACHE_TIME" => '36000000',
                                                        "CACHE_GROUPS" => 'Y',
                                                        "COUNT_ELEMENTS" => 'Y',
                                                        "TOP_DEPTH" => '3',
                                                        "SECTION_URL" => '/new-catalog/#SECTION_CODE_PATH#/',
                                                        "VIEW_MODE" => 'LIST',
                                                        "SHOW_PARENT_NAME" => 'Y',
                                                        "HIDE_SECTION_NAME" => "N",
                                                        "ADD_SECTIONS_CHAIN" => 'Y',
                                                    ),
                                                    false,
                                                    array("HIDE_ICONS" => "Y")
                                                ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <? $APPLICATION->IncludeComponent(
                                        "bitrix:menu",
                                        "multimenu_top",
                                        array(
                                            "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                                            "CHILD_MENU_TYPE" => "top",    // Тип меню для остальных уровней
                                            "COMPONENT_TEMPLATE" => "tree",
                                            "DELAY" => "N",    // Откладывать выполнение шаблона меню
                                            "MAX_LEVEL" => "1",    // Уровень вложенности меню
                                            "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                                            "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
                                            "MENU_CACHE_TYPE" => "N",    // Тип кеширования
                                            "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
                                            "ROOT_MENU_TYPE" => "top",    // Тип меню для первого уровня
                                            "USE_EXT" => "N",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
                                        ),
                                        false
                                    ); ?>
                                    <div class="header-menu__item palitramenu">
                                        <div class="catalog-top-menu-catalog">
                                            <a href="/palitra/"><?= GetMessage('PALITRA'); ?></a>
                                        </div>
                                        <div class="header-menu__item-modal catalog-menu-modal-container-example-palette">
                                            <div class="catalog-menu-block">
                                                <? $APPLICATION->IncludeComponent(
                                                    "bitrix:catalog.section.list",
                                                    "catalog-top",
                                                    array(
                                                        "IBLOCK_TYPE" => 'catalog',
                                                        "IBLOCK_ID" => '25',
                                                        "CACHE_TYPE" => 'A',
                                                        "CACHE_TIME" => '36000000',
                                                        "CACHE_GROUPS" => 'Y',
                                                        "COUNT_ELEMENTS" => 'N',
                                                        "TOP_DEPTH" => '1',
                                                        "SECTION_URL" => '/palitra/#SECTION_CODE#/',
                                                        "VIEW_MODE" => 'TILE',
                                                        "SHOW_PARENT_NAME" => 'Y',
                                                        "HIDE_SECTION_NAME" => "N",
                                                        "ADD_SECTIONS_CHAIN" => 'Y',
                                                    ),
                                                    $component,
                                                    array("HIDE_ICONS" => "Y")
                                                ); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="menu__item">
                                        <li class="catalog-top-menu-catalog">
                                            <? $APPLICATION->IncludeFile('/include/menu-courses.php'); ?>
                                        </li>
                                    </div>
                                </div>
                                <div class="header-search-line">
                                    <? $APPLICATION->IncludeComponent(
                                        "arturgolubev:search.title",
                                        "template",
                                        array(
                                            "ANIMATE_HINTS" => array(),
                                            "ANIMATE_HINTS_SPEED" => "1",
                                            "CATEGORY_0" => array(
                                                0 => "iblock_catalog",
                                            ),
                                            "CATEGORY_0_TITLE" => "",
                                            "CATEGORY_0_iblock_catalog" => array(
                                                0 => "14",
                                            ),
                                            "CHECK_DATES" => "N",
                                            "CONTAINER_ID" => "smart-title-search",
                                            "CONVERT_CURRENCY" => "N",
                                            "FILTER_NAME" => "",
                                            "INPUT_ID" => "smart-title-search-input",
                                            "INPUT_PLACEHOLDER" => "Поиск",
                                            "ORDER" => "rank",
                                            "PAGE" => "#SITE_DIR#search/",
                                            "PREVIEW_HEIGHT_NEW" => "34",
                                            "PREVIEW_WIDTH_NEW" => "34",
                                            "PRICE_CODE" => array(
                                                0 => "Типовое соглашение с клиентами",
                                            ),
                                            "PRICE_VAT_INCLUDE" => "Y",
                                            "SHOW_HISTORY" => "N",
                                            "SHOW_INPUT" => "Y",
                                            "SHOW_LOADING_ANIMATE" => "Y",
                                            "SHOW_PREVIEW" => "N",
                                            "SHOW_PREVIEW_TEXT" => "N",
                                            "SHOW_PROPS" => array(),
                                            "TOP_COUNT" => "3",
                                            "USE_LANGUAGE_GUESS" => "Y",
                                            "COMPONENT_TEMPLATE" => "template"
                                        ),
                                        false
                                    ); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="header-mobile">
                        <div class="header-mobile-top">
                            <div class="logo">
                                <a href="/">
                                    <?= classBuildSCG::buildSVG('logo_svg'); ?>
                                </a>
                            </div>
                            <a href="#menu" class="header-burger">
                                <span class="burger-menu"><?= classBuildSCG::buildSVG('hamburger'); ?></span>
                                <span class="close-menu" style="display: none"><?= classBuildSCG::buildSVG('close-menu'); ?></span>
                            </a>
                        </div>
                        <div class="header-mobile-bottom">
                            <div class="header-mobile-search">
                                <? $APPLICATION->IncludeComponent(
                                    "arturgolubev:search.title",
                                    "template-mobile",
                                    array(
                                        "ANIMATE_HINTS" => array(),
                                        "ANIMATE_HINTS_SPEED" => "1",
                                        "CATEGORY_0" => array(
                                            0 => "iblock_catalog",
                                        ),
                                        "CATEGORY_0_TITLE" => "",
                                        "CATEGORY_0_iblock_catalog" => array(
                                            0 => "14",
                                        ),
                                        "CHECK_DATES" => "N",
                                        "CONTAINER_ID" => "smart-title-search-mobile",
                                        "CONVERT_CURRENCY" => "N",
                                        "FILTER_NAME" => "",
                                        "INPUT_ID" => "smart-title-search-input-mobile",
                                        "INPUT_PLACEHOLDER" => "Поиск",
                                        "ORDER" => "rank",
                                        "PAGE" => "#SITE_DIR#search/",
                                        "PREVIEW_HEIGHT_NEW" => "34",
                                        "PREVIEW_WIDTH_NEW" => "34",
                                        "PRICE_CODE" => array(
                                            0 => "Типовое соглашение с клиентами",
                                        ),
                                        "PRICE_VAT_INCLUDE" => "Y",
                                        "SHOW_HISTORY" => "N",
                                        "SHOW_INPUT" => "Y",
                                        "SHOW_LOADING_ANIMATE" => "Y",
                                        "SHOW_PREVIEW" => "N",
                                        "SHOW_PREVIEW_TEXT" => "N",
                                        "SHOW_PROPS" => array(),
                                        "TOP_COUNT" => "3",
                                        "USE_LANGUAGE_GUESS" => "Y",
                                        "COMPONENT_TEMPLATE" => "template"
                                    ),
                                    false
                                ); ?>
                            </div>
                            <div class="header-mobile-btn">
                                <? if ($USER->IsAuthorized()): ?>
                                    <a href="#auth" class="enterLink showPreAuthForm menu-links">
                                        <?= classBuildSCG::buildSVG('user'); ?>
                                    </a>
                                <? else: ?>
                                    <a href="#auth" class="enterLink linkOpen menu-links">
                                        <?= classBuildSCG::buildSVG('user'); ?>
                                    </a>
                                <? endif; ?>
                                <a href="/personal/favorites.php"
                                    class="enterLink menu-links favorit <?= (is_array($favorites) && count($favorites) > 0) ? 'favorites_not-empty' : '' ?>">
                                    <?= classBuildSCG::buildSVG('favorite'); ?>
                                </a>
                                <? $APPLICATION->IncludeComponent("smedia:basket.small", ".default", array(), false); ?>
                            </div>
                        </div>
                    </div>


                    <? if ($_GET['registration_success'] == true): ?>
                        <script>
                            jQuery(document).ready(function($) {
                                $('#register').addClass('opened');
                                $('.modal-bg').fadeIn();
                            });
                        </script>
                    <? endif; ?>
                    <? if (Page::isCourse()) {
                    ?>
                        <div class="toggleOpen" id="req">
                            <a href="#" class="close"><span></span><span></span></a>
                            <div class="h2">Оставить заявку</div>
                            <div class="course_body">
                                <? $APPLICATION->IncludeComponent(
                                    "bitrix:form",
                                    "estetica",
                                    array(
                                        "AJAX_MODE" => "Y",    // Включить режим AJAX
                                        "AJAX_OPTION_ADDITIONAL" => "",    // Дополнительный идентификатор
                                        "AJAX_OPTION_HISTORY" => "N",    // Включить эмуляцию навигации браузера
                                        "AJAX_OPTION_JUMP" => "N",    // Включить прокрутку к началу компонента
                                        "AJAX_OPTION_STYLE" => "Y",    // Включить подгрузку стилей
                                        "CACHE_TIME" => "3600",    // Время кеширования (сек.)
                                        "CACHE_TYPE" => "A",    // Тип кеширования
                                        "CHAIN_ITEM_LINK" => "",    // Ссылка на дополнительном пункте в навигационной цепочке
                                        "CHAIN_ITEM_TEXT" => "",    // Название дополнительного пункта в навигационной цепочке
                                        "EDIT_ADDITIONAL" => "N",    // Выводить на редактирование дополнительные поля
                                        "EDIT_STATUS" => "N",    // Выводить форму смены статуса
                                        "IGNORE_CUSTOM_TEMPLATE" => "N",    // Игнорировать свой шаблон
                                        "NOT_SHOW_FILTER" => array(    // Коды полей, которые нельзя показывать в фильтре
                                            0 => "",
                                            1 => "",
                                        ),
                                        "NOT_SHOW_TABLE" => array(    // Коды полей, которые нельзя показывать в таблице
                                            0 => "",
                                            1 => "",
                                        ),
                                        "RESULT_ID" => $_REQUEST['RESULT_ID'],    // ID результата
                                        "SEF_MODE" => "N",    // Включить поддержку ЧПУ
                                        "SHOW_ADDITIONAL" => "N",    // Показать дополнительные поля веб-формы
                                        "SHOW_ANSWER_VALUE" => "N",    // Показать значение параметра ANSWER_VALUE
                                        "SHOW_EDIT_PAGE" => "N",    // Показывать страницу редактирования результата
                                        "SHOW_LIST_PAGE" => "N",    // Показывать страницу со списком результатов
                                        "SHOW_STATUS" => "N",    // Показать текущий статус результата
                                        "SHOW_VIEW_PAGE" => "N",    // Показывать страницу просмотра результата
                                        "START_PAGE" => "new",    // Начальная страница
                                        "SUCCESS_URL" => "",    // Страница с сообщением об успешной отправке
                                        "USE_EXTENDED_ERRORS" => "N",    // Использовать расширенный вывод сообщений об ошибках
                                        "VARIABLE_ALIASES" => array(
                                            "action" => "action",
                                        ),
                                        "WEB_FORM_ID" => "2",    // ID веб-формы
                                    ),
                                    false
                                ); ?>
                            </div>
                        </div>
                    <? } ?>
                    <div class="custom_popup_block" id="basket">
                        <a href="#" class="close basket-close"><?= classBuildSCG::buildSVG('big-cross'); ?></a>
                        <div id="small-basket-content">
                            <?
                            $basketSmallComponent = new CBitrixComponent();
                            $basketSmallComponent->InitComponent('bitrix:sale.basket.basket', 'basket.small.list.basket');
                            $basketSmallComponent->initComponentTemplate();

                            Asset::getInstance()->addCss($basketSmallComponent->__template->GetFolder() . '/style.css');
                            Asset::getInstance()->addJs($basketSmallComponent->__template->GetFolder() . '/script.js');
                            ?>
                        </div>
                    </div>
                    <div class="modal-background"></div>
                    <div class="toggleOpen auth-modal" id="auth">
                        <a href="#" class="close">
                            <?= classBuildSCG::buildSVG('big-cross'); ?>
                        </a>
                        <? $APPLICATION->IncludeComponent(
                            "bxmaker:authuserphone.enter",
                            "template",
                            array(),
                            false
                        ); ?>
                        <a href="#register" class="linkOpen auth-logo">регистрация</a>
                    </div>
                    <div class="toggleOpen auth-modal <?= $USER->IsAuthorized() ? 'success' : ''; ?>" id="register">
                        <a href="#" class="close">
                            <?= classBuildSCG::buildSVG('big-cross'); ?>
                        </a>

                        <? $APPLICATION->IncludeComponent(
                            "addamant:main.register",
                            ".default",
                            array(
                                "USER_PROPERTY_NAME" => "",
                                "SEF_MODE" => "N",
                                "SHOW_FIELDS" => array(
                                    0 => "LAST_NAME",
                                    1 => "NAME",
                                    2 => "SECOND_NAME",
                                    3 => "PERSONAL_PHONE",
                                    4 => "PERSONAL_BIRTHDAY",
                                    5 => "UF_SUBSCRIBES"
                                ),
                                "REQUIRED_FIELDS" => array(
                                    0 => "NAME",
                                    1 => "LAST_NAME",
                                    2 => "PERSONAL_PHONE",
                                    3 => "SECOND_NAME",
                                    4 => "EMAIL"
                                ),
                                "AUTH" => "Y",
                                "USE_BACKURL" => "N",
                                "SUCCESS_PAGE" => $APPLICATION->GetCurPage() . '?registration_success=true',
                                "SET_TITLE" => "N",
                                "USER_PROPERTY" => array(
                                    0 => "UF_PERSONAL_AGREE",
                                    1 => "UF_DISCOUNT_CARD_NUM"
                                ),
                                "COMPONENT_TEMPLATE" => ".default",
                                "USER_CONSENT" => "Y",
                                "USER_CONSENT_ID" => "1",
                                "USER_CONSENT_IS_CHECKED" => "Y",
                                "USER_CONSENT_IS_LOADED" => "N",
                                "AJAX_MODE" => "Y"
                            ),
                            false
                        ); ?>

                    </div>
                </div>
                <div class="modal-bg"></div>
            </header>

            <div class="header-mobile-menu" id="menu">
                <div class="header-mobile-menu-wrap">
                    <ul class="menu-block">
                        <li class="header-menu__item">
                            <a class="top-mobile-menu__catalog_opener root-item-catalog"><span></span><span><?= GetMessage('CATALOG'); ?></span></a>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:catalog.section.list",
                                "catalog-menu-top",
                                array(
                                    "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
                                    "ADD_SECTIONS_CHAIN" => "N",
                                    "CACHE_FILTER" => "N",
                                    "CACHE_GROUPS" => "Y",
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_TYPE" => "A",
                                    "COMPONENT_TEMPLATE" => "catalog-menu-top",
                                    "COUNT_ELEMENTS" => "N",
                                    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
                                    "FILTER_NAME" => "sectionsFilter",
                                    "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
                                    "IBLOCK_ID" => "26",
                                    "IBLOCK_TYPE" => "catalog",
                                    "SECTION_CODE" => "",
                                    "SECTION_FIELDS" => array(0 => "", 1 => "",),
                                    "SECTION_ID" => $_REQUEST["SECTION_ID"],
                                    "SECTION_URL" => "",
                                    "SECTION_USER_FIELDS" => array(0 => "", 1 => "",),
                                    "SHOW_PARENT_NAME" => "Y",
                                    "TOP_DEPTH" => "3",
                                    "VIEW_MODE" => "LIST"
                                )
                            ); ?>
                        </li>
                        <li class="header-menu__item">
                            <a class="top-mobile-menu__catalog_opener root-item-brand"><span></span><span><?= GetMessage('BRAND'); ?></span></a>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:catalog.section.list",
                                "catalog-menu-top",
                                array(
                                    "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
                                    "ADD_SECTIONS_CHAIN" => "N",
                                    "CACHE_FILTER" => "N",
                                    "CACHE_GROUPS" => "Y",
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_TYPE" => "A",
                                    "COMPONENT_TEMPLATE" => "catalog-menu-top",
                                    "COUNT_ELEMENTS" => "N",
                                    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
                                    "FILTER_NAME" => "sectionsFilter",
                                    "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
                                    "IBLOCK_ID" => "14",
                                    "IBLOCK_TYPE" => "catalog",
                                    "SECTION_CODE" => "",
                                    "SECTION_FIELDS" => array(0 => "", 1 => "",),
                                    "SECTION_ID" => $_REQUEST["SECTION_ID"],
                                    "SECTION_URL" => "",
                                    "SECTION_USER_FIELDS" => array(0 => "", 1 => "",),
                                    "SHOW_PARENT_NAME" => "Y",
                                    "TOP_DEPTH" => "3",
                                    "VIEW_MODE" => "LIST"
                                )
                            ); ?>
                        </li>
                        <? $APPLICATION->IncludeComponent(
                            "bitrix:menu",
                            "multimenu_top_mobile",
                            array(
                                "ALLOW_MULTI_SELECT" => "N",    // Разрешить несколько активных пунктов одновременно
                                "CHILD_MENU_TYPE" => "top",    // Тип меню для остальных уровней
                                "COMPONENT_TEMPLATE" => "tree",
                                "DELAY" => "N",    // Откладывать выполнение шаблона меню
                                "MAX_LEVEL" => "2",    // Уровень вложенности меню
                                "MENU_CACHE_GET_VARS" => "",    // Значимые переменные запроса
                                "MENU_CACHE_TIME" => "3600",    // Время кеширования (сек.)
                                "MENU_CACHE_TYPE" => "N",    // Тип кеширования
                                "MENU_CACHE_USE_GROUPS" => "Y",    // Учитывать права доступа
                                "ROOT_MENU_TYPE" => "top",    // Тип меню для первого уровня
                                "USE_EXT" => "Y",    // Подключать файлы с именами вида .тип_меню.menu_ext.php
                            ),
                            false
                        ); ?>
                        <li class="header-menu__item">
                            <a class="top-mobile-menu__catalog_opener root-item-palitra"><span></span><span><?= GetMessage('PALITRA'); ?></span></a>
                            <? $APPLICATION->IncludeComponent(
                                "bitrix:catalog.section.list",
                                "catalog-menu-top",
                                array(
                                    "ADDITIONAL_COUNT_ELEMENTS_FILTER" => "additionalCountFilter",
                                    "ADD_SECTIONS_CHAIN" => "N",
                                    "CACHE_FILTER" => "N",
                                    "CACHE_GROUPS" => "Y",
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_TYPE" => "A",
                                    "COMPONENT_TEMPLATE" => "catalog-menu-top",
                                    "COUNT_ELEMENTS" => "N",
                                    "COUNT_ELEMENTS_FILTER" => "CNT_ACTIVE",
                                    "FILTER_NAME" => "sectionsFilter",
                                    "HIDE_SECTIONS_WITH_ZERO_COUNT_ELEMENTS" => "N",
                                    "IBLOCK_ID" => "25",
                                    "IBLOCK_TYPE" => "catalog",
                                    "SECTION_CODE" => "",
                                    "SECTION_FIELDS" => array(0 => "", 1 => "",),
                                    "SECTION_ID" => $_REQUEST["SECTION_ID"],
                                    "SECTION_URL" => "",
                                    "SECTION_USER_FIELDS" => array(0 => "", 1 => "",),
                                    "SHOW_PARENT_NAME" => "Y",
                                    "TOP_DEPTH" => "2",
                                    "VIEW_MODE" => "LIST"
                                )
                            ); ?>
                        </li>
                    </ul>
                </div>
                <div class="menu-block simple">
                    <? $APPLICATION->IncludeComponent(
                        "bitrix:menu",
                        "top",
                        array(
                            "ALLOW_MULTI_SELECT" => "N",
                            "CHILD_MENU_TYPE" => "",
                            "COMPONENT_TEMPLATE" => "top",
                            "DELAY" => "N",
                            "MAX_LEVEL" => "2",
                            "MENU_CACHE_GET_VARS" => array(),
                            "MENU_CACHE_TIME" => "3600",
                            "MENU_CACHE_TYPE" => "N",
                            "MENU_CACHE_USE_GROUPS" => "Y",
                            "ROOT_MENU_TYPE" => "top_menu",
                            "USE_EXT" => "N"
                        )
                    ); ?>
                </div>
                <div class="header-phone">
                    <? $APPLICATION->IncludeFile('/include/phone.php'); ?>
                </div>
            </div>

            <div id="content-wrap" class="<?= (Page::isPersonal()) ? ' personal' : '' ?>">
                <? if (!Page::IsMain() /*&& $APPLICATION->GetCurDir() != '/login/auth/' && strpos($APPLICATION->GetCurDir(), 'personal') == false*/) { ?>

                    <? $APPLICATION->IncludeComponent(
                        "bitrix:breadcrumb",
                        ".default",
                        array(
                            "COMPONENT_TEMPLATE" => ".default",
                            "START_FROM" => "0",
                            "PATH" => "",
                            "SITE_ID" => "s1"
                        ),
                        false
                    ); ?>
                <? } ?>
                <? if ($APPLICATION->GetCurDir() == '/personal/cart/') { ?>
                    <div class="cart-mobile-back">
                        <div class="btn-block">
                            <a onclick="javascript:history.back(); return false;"><?= classBuildSCG::buildSVG('back-menu'); ?></a>
                        </div>
                    </div>
                <? } ?>
                <? if (Page::IsMain() != true && $APPLICATION->GetCurDir() == '/search1/' && $APPLICATION->GetCurDir() != '/personal/cart/') { ?>
                    <div class="title-header">
                        <h1><?= $APPLICATION->ShowTitle(false); ?></h1>
                    </div>
                <? } ?>