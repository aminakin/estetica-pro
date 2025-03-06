<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="main-profile-menu">
        <div class="main-profile-menu__part">
            <? foreach ($arResult as $arItem) {
                if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) {
                    continue;
                } ?>
                <? if ($arItem["SELECTED"]) { ?>
                    <a href="<?= $arItem["LINK"] ?>" class="main-profile-menu__link main-profile-menu__link--active"><?= $arItem["TEXT"] ?></a>
                <? } else { ?>
                    <a href="<?= $arItem["LINK"] ?>" class="main-profile-menu__link"><?= $arItem["TEXT"] ?></a>
                <? } ?>
            <? } ?>
        </div>
        <div class="main-profile-menu__part">
            <? if ($USER->IsAuthorized()) { ?>
                <div class="main-profile-menu__item">
                    <a class="main-profile-menu__link logout"
                       href="<?= $APPLICATION->GetCurPageParam('logout=yes', 'logout') ?>">
                        <?= GetMessage('LOGOUT') ?>
                    </a>
                </div>
            <? } ?>
        </div>
    </div>
<? endif ?>