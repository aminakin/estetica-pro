<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @global CMain $APPLICATION */
/** @var CBitrixComponent $component */
/** @var array $arResult */

CModule::IncludeModule("catalog");
CModule::IncludeModule("iblock");

if (!empty($arResult)):?>
    <nav class="top-menu">
        <ul>
            <? $previousLevel = 0;
            foreach ($arResult as $arItem):?>
                <? if ($arItem['DEPTH_LEVEL'] == 1): ?>
                    <? if ($arItem["LINK"] === '/catalog/') { ?>
                        <li>
                            <div href="<?= $arItem["LINK"] ?>"
                                 class="<? if ($arItem["SELECTED"]): ?>root-item-selected<? else: ?>root-item<? endif ?> catalog-top-menu-catalog">
                                <?= $arItem["TEXT"] ?>
                            </div>
                        </li>
                    <? } elseif ($arItem["LINK"] === '/palitra/') { ?>
                        <li>
                            <a href="<?= $arItem["LINK"] ?>"
                               class="<? if ($arItem["SELECTED"]): ?>root-item-selected<? else: ?>root-item<? endif ?> catalog-top-menu-palette">
                                <?= $arItem["TEXT"] ?>
                            </a>
                        </li>
                    <? } else { ?>
                        <li>
                            <a href="<?= $arItem["LINK"] ?>"
                               class="<? if ($arItem["SELECTED"]): ?>root-item-selected<? else: ?>root-item<? endif ?>">
                                <?= $arItem["TEXT"] ?>
                            </a>
                        </li>
                    <? } ?>
                <? endif; ?>
            <? endforeach ?>
        </ul>
    </nav>
<? endif ?>

