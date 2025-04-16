<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (!empty($arResult)): ?>
    <div class="help-menu-wrapper">
        <? foreach ($arResult as $arItem):
            if ($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1)
                continue; ?>
            <? if ($arItem["SELECTED"]): ?>
                <a href="<?= $arItem["LINK"] ?>" class="active"><?= $arItem["TEXT"] ?><?= classBuildSCG::buildSVG('arrow', $templateFolder."/images" );?></a>
            <? else: ?>
                <a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?><?= classBuildSCG::buildSVG('arrow', $templateFolder."/images" );?></a>
            <? endif ?>
        <? endforeach ?>
    </div>
<? endif ?>