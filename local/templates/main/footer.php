<? global $USER; ?>

</div>

</div>
<div class="error-popup">
    <div class="error-popup__block">
        <div class="error-popup__text">

        </div>
        <div class="error-popup__close">
            <span></span><span></span>
        </div>
    </div>
</div>
<footer id="footer">
    <div class="footer-container">
        <div class="footer-container-menu logo-container">
            <a href="/" class="logo">
                <?= classBuildSCG::buildSVG('footer-logo'); ?>
            </a>
            <? $APPLICATION->IncludeFile('/include/footerMail.php'); ?>
            <div class="nbr"><? $APPLICATION->IncludeFile('/include/footerPhone.php'); ?></div>
            <? $APPLICATION->IncludeFile('/include/footerAdress.php'); ?>
            <div class="footer-socials">
                <? $APPLICATION->IncludeComponent(
                    "bitrix:news.list",
                    "soc_net_footer",
                    array(
                        "ACTIVE_DATE_FORMAT" => "d.m.Y",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "CHECK_DATES" => "Y",
                        "DETAIL_URL" => "",
                        "DISPLAY_BOTTOM_PAGER" => "N",
                        "DISPLAY_DATE" => "N",
                        "DISPLAY_NAME" => "Y",
                        "DISPLAY_PICTURE" => "N",
                        "DISPLAY_PREVIEW_TEXT" => "Y",
                        "DISPLAY_TOP_PAGER" => "N",
                        "FIELD_CODE" => array(
                            0 => "",
                            1 => "",
                        ),
                        "FILTER_NAME" => "",
                        "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                        "IBLOCK_ID" => IblockHelper::getIdByCode('soc'),
                        "IBLOCK_TYPE" => "data",
                        "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                        "INCLUDE_SUBSECTIONS" => "N",
                        "MESSAGE_404" => "",
                        "NEWS_COUNT" => "3",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "PAGER_TITLE" => "Новости",
                        "PARENT_SECTION" => "",
                        "PARENT_SECTION_CODE" => "",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "PROPERTY_CODE" => array(
                            0 => "SUBSCRIBERS",
                            1 => "NET",
                            2 => "",
                        ),
                        "SET_BROWSER_TITLE" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_STATUS_404" => "N",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_TITLE" => "N",
                        "SHOW_404" => "N",
                        "SORT_BY1" => "SORT",
                        "SORT_BY2" => "NAME",
                        "SORT_ORDER1" => "ASC",
                        "SORT_ORDER2" => "DESC",
                        "STRICT_SECTION_CHECK" => "N",
                        "COMPONENT_TEMPLATE" => "soc_net_footer"
                    ),
                    false
                ); ?>
            </div>
        </div>
        <div class="footer-container-menu">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "bottom_menu_new",
                array(
                    "COMPONENT_TEMPLATE" => "bottom_menu_new",
                    "ROOT_MENU_TYPE" => "bottom",
                    "MENU_CACHE_TYPE" => "N",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N"
                ),
                false
            );
            ?>
        </div>

        <div class="footer-container-menu">
            <?
            $APPLICATION->IncludeComponent(
                "bitrix:menu",
                "payment",
                array(
                    "COMPONENT_TEMPLATE" => "payment",
                    "ROOT_MENU_TYPE" => "payment",
                    "MENU_CACHE_TYPE" => "N",
                    "MENU_CACHE_TIME" => "3600",
                    "MENU_CACHE_USE_GROUPS" => "Y",
                    "MENU_CACHE_GET_VARS" => array(),
                    "MAX_LEVEL" => "1",
                    "CHILD_MENU_TYPE" => "left",
                    "USE_EXT" => "Y",
                    "DELAY" => "N",
                    "ALLOW_MULTI_SELECT" => "N"
                ),
                false
            );
            ?>
        </div>
        <div class="footer-container-menu">
            <div class="footer-title">Часы работы</div>
            <nav class="footer-menu-list-time-work">
                <div class="footer-menu-item">пн-пт с 9-30 до 19-00</div>
                <div class="footer-menu-item">сб с 9-30 до 18-00</div>
                <div class="footer-menu-item">вс с 9-30 до 17-00</div>
            </nav>
        </div>
    </div>
    <div class="footer-copyright-wrapper">
        <div class="footer-copyright-text">
            <span>© <span class="nbr"><? echo date('Y'); ?></span> <? $APPLICATION->IncludeFile('/include/copyright.php'); ?></span>
        </div>
    </div>
</footer>

<div class="wrapper_modal_cookie">
    <div class="modal_cookie">
        <div class="modal-cookie_wrap">
            <div class="modal-cookie_text">
                <?= GetMessage('COOKIE_DESCRIPTION'); ?>
            </div>
            <div class="modal-cookie_button"><?= GetMessage('COOKIE_ACCEPT'); ?></div>
        </div>
    </div>
</div>
<div class="toggleOpen about_bonus" id="about_bonus">
    <a href="#" class="close basket-close"><?= classBuildSCG::buildSVG('big-cross'); ?></a>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/about-bonus.php"
        )
    ); ?>
</div>
<div class="toggleOpen no_fav_popup" id="no_fav_popup">
    <a href="#" class="close basket-close"><?= classBuildSCG::buildSVG('big-cross'); ?></a>
    <? $APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/favorite_popup.php"
        )
    ); ?>
</div>
<div class="toggleOpen" id="comment">
    <a href="#" class="close"><?= classBuildSCG::buildSVG('big-cross'); ?></a>
    <div class="h2">Комментарий</div>
    <div class="addcomment">
        <form>
            <input type="text" placeholder="Ваше имя" />
            <textarea placeholder="Комментарий"></textarea>
            <div class="align-center">
                <input type="submit" value="Отправить" />
            </div>
        </form>
    </div>
</div>


<!-- modal -->
<div id="mask"></div>
<div id="scroller" title="В начало">
    <svg width="12" height="22" viewBox="0 0 12 22" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 1.5L11 11L2 20.5" stroke="#221F20" stroke-width="2" stroke-linecap="square" stroke-linejoin="round" />
    </svg>
</div>
<div class="delivery-popup-shadow"></div>
<div class="site-shadow"></div>
<div class="site-shadow-menu"></div>
<script type="text/javascript">
    function openMenu(value) {
        let menu = document.querySelectorAll(`.footer-menu-list-${value.dataset.name}`);

        if (!value.checked) {
            menu[0].style.display = 'flex';
        } else menu[0].style.display = 'none';
    }
</script>

<div class="product-item-adult-modal">
    <div onclick="openCloseModal(false)" class="product-item-adult-modal-background"></div>
    <div class="product-item-adult-modal-block">
        <div onclick="openCloseModal(false)" class="adult-modal-block-exit"></div>
        <div class="adult-modal-text-block">
            <div class="adult-modal-block-title">Вам уже исполнилось 18 лет?</div>
            <div class="adult-modal-block-links">
                <a class="adult-modal-block-links-text adult-modal-block-link-success">Да, мне есть 18</a>
                <a onclick="openCloseModal(false)" class="adult-modal-block-links-text adult-modal-block-link-cancel">
                    Нет, мне нет 18
                </a>
            </div>
        </div>
    </div>
</div>
<div id="auth-form" class="auth-form<?= $USER->IsAuthorized() ? ' auth-form--authorized' : '' ?>">
    <div class="auth-form__layout">
        <div class="auth-form__inner">
            <? if ($USER->IsAuthorized()) { ?>
                <? include_once $_SERVER['DOCUMENT_ROOT'] . '/.post-auth.menu.php';
                if (count($aMenuLinks) > 0) { ?>
                    <div class="auth-form__menu">
                        <a class="auth-form__menu-title" href="/personal/orders/">
                            <?= GetMessage('MY_ORDER') ?>:
                        </a>
                        <? foreach ($aMenuLinks as $link) { ?>
                            <div class="auth-form__menu-item">
                                <a class="auth-form__menu-link" href="<?= $link[1] ?>"><?= $link[0] ?></a>
                            </div>
                        <? } ?>
                    </div>
                <? } ?>

                <div class="auth-form__menu">
                    <div class="auth-form__menu-item">
                        <a class="auth-form__menu-link" href="/personal/bonuses/"><?= GetMessage('MY_BONUS') ?></a>
                    </div>
                    <div class="auth-form__menu-item">
                        <a class="auth-form__menu-link" href="/personal/profile/"><?= GetMessage('MY_SETTINGS') ?></a>
                    </div>
                </div>

                <div class="auth-form__menu">
                    <div class="auth-form__menu-item">
                        <a class="auth-form__menu-link" href="/bonus-system/"><?= GetMessage('BONUS_SYSTEM') ?></a>
                    </div>
                    <div class="auth-form__menu-item">
                        <a class="auth-form__menu-link" href="<?= $APPLICATION->GetCurPageParam("logout=yes", array(
                                                                    "login",
                                                                    "logout",
                                                                    "register",
                                                                    "forgot_password",
                                                                    "change_password"
                                                                )); ?>">
                            <?= GetMessage('logout') ?>
                        </a>
                    </div>
                </div>
            <? } ?>
        </div>
    </div>
</div>
<div class="custom_popup" id="success_ajax">
    <div class="custom_popup-body">
        <div class="custom_popup-close">
            <span></span>
            <span></span>
        </div>
        <div id="success_text"></div>
    </div>
</div>
<div class="custom_popup_bg"></div>
    <? $APPLICATION->IncludeComponent(
	"bitrix:iblock.element.add.form", 
	"subscribe", 
	array(
		"CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
		"CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
		"CUSTOM_TITLE_DETAIL_PICTURE" => "",
		"CUSTOM_TITLE_DETAIL_TEXT" => "",
		"CUSTOM_TITLE_IBLOCK_SECTION" => "",
		"CUSTOM_TITLE_NAME" => "",
		"CUSTOM_TITLE_PREVIEW_PICTURE" => "",
		"CUSTOM_TITLE_PREVIEW_TEXT" => "",
		"CUSTOM_TITLE_TAGS" => "",
		"DEFAULT_INPUT_SIZE" => "30",
		"DETAIL_TEXT_USE_HTML_EDITOR" => "N",
		"ELEMENT_ASSOC" => "CREATED_BY",
		"GROUPS" => array(
		),
		"IBLOCK_ID" => "68",
		"IBLOCK_TYPE" => "data",
		"LEVEL_LAST" => "Y",
		"LIST_URL" => "",
		"MAX_FILE_SIZE" => "0",
		"MAX_LEVELS" => "100000",
		"MAX_USER_ENTRIES" => "100000",
		"PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
		"PROPERTY_CODES" => array(
			0 => "356",
			1 => "NAME",
		),
		"PROPERTY_CODES_REQUIRED" => array(
			0 => "356",
			1 => "NAME",
		),
		"RESIZE_IMAGES" => "N",
		"SEF_MODE" => "N",
		"STATUS" => "ANY",
		"STATUS_NEW" => "N",
		"USER_MESSAGE_ADD" => "",
		"USER_MESSAGE_EDIT" => "",
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => "subscribe"
	),
	false
); ?>

<script type="text/javascript">
    (function(m, e, t, r, i, k, a) {
        m[i] = m[i] || function() {
            (m[i].a = m[i].a || []).push(arguments)
        };
        m[i].l = 1 * new Date();
        k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
    })
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym")

    ym(51439306, "init", {
        id: 51439306,
        clickmap: true,
        trackLinks: true,
        accurateTrackBounce: true,
        webvisor: true
    });
</script>

<noscript>
    <div><img src="https://mc.yandex.ru/watch/51439306" style="position:absolute; left:-9999px;" alt="" /></div>
</noscript>
<!-- /Yandex.Metrika counter -->
</body>

</html>