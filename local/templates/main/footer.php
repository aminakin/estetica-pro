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
                <?= classBuildSCG::buildSVG('footer-logo');?>
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
            <a href="https://addamant.ru/" class="adm-link">
                created with 
                <svg class="ico-red-heart" width="18" height="18" viewBox="0 0 72 72" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <rect width="72" height="72" fill="url(#pattern0)"></rect>
                    <defs>
                    <pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
                    <use xlink:href="#image0_15_134" transform="scale(0.0138889)"></use>
                    </pattern>
                    <image id="image0_15_134" width="72" height="72" xlink:href="data:image/png;base64,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"></image>
                    </defs>
                </svg>
                at adm                                        
                <svg class="arrow-line-up-right" width="18" height="19" viewBox="0 0 18 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.25 12.9043L12.75 5.4043" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M5.25 5.4043H12.75V12.9043" stroke="#FFFFFF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </a>
        </div>
    </div>
</footer>

<div class="wrapper_modal_cookie">
    <div class="modal_cookie">
        <div class="modal-cookie_close"></div>
        <div class="modal-cookie_wrap">
            <div class="modal-cookie_text">
            <?= GetMessage('COOKIE_DESCRIPTION');?>
            </div>
            <div class="modal-cookie_button">
                <button>Ок</button>  
            </div>
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
            <input type="text" placeholder="Ваше имя"/>
            <textarea placeholder="Комментарий"></textarea>
            <div class="align-center">
                <input type="submit" value="Отправить"/>
            </div>
        </form>
    </div>
</div>


<!-- modal -->
<div id="mask"></div>
<div id="scroller" title="В начало">
    <svg width="12" height="22" viewBox="0 0 12 22" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M2 1.5L11 11L2 20.5" stroke="#221F20" stroke-width="2" stroke-linecap="square" stroke-linejoin="round"/>
    </svg>
</div>
<div class="delivery-popup-shadow"></div>
<div class="site-shadow"></div>
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
                <a onclick="openCloseModal(false)" class="adult-modal-block-links-text adult-modal-block-link-cancel" >
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
                            "change_password")); ?>">
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

<script type="text/javascript">
    (function (m, e, t, r, i, k, a) {
        m[i] = m[i] || function () {
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
    <div><img src="https://mc.yandex.ru/watch/51439306" style="position:absolute; left:-9999px;" alt=""/></div>
</noscript>
<!-- /Yandex.Metrika counter -->
</body>
</html>