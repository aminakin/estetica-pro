<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<div class="slider">
    <div class="carousel-wrapper palitra">
    <div id="myCarousel" class="carousel slide">
        <? foreach ($arResult["ITEMS"] as $arItem): ?>
            <? if (!empty($arItem["PROPERTIES"]["MOBILE_PIC"]["VALUE"])): ?>
                <div data-id="<?= $arItem['ID'] ?>" class="item <? if ($count++ == 0) { ?> active<? } ?>">
                    <a class="mobile-img" href="<?= $arItem["PROPERTIES"]["LINK"]["VALUE"] ?>">
                        <? $imageCur = CFile::GetPath($arItem["PROPERTIES"]["MOBILE_PIC"]["VALUE"]);
                        $resizeImage = CFile::ShowImage($imageCur, "500", "500", "", "", false); ?>
                        <?= $resizeImage; ?>
                    </a>
                </div>
            <? else: ?>
                <div data-id="<?= $arItem['ID'] ?>" class="item <? if ($count++ == 0) { ?> active<? } ?>">
                    <a class="desktop-img preload" href="<?= $arItem["PROPERTIES"]["LINK"]["VALUE"] ?>"
                       target="_blank" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>')"
                        <? /*data-src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"*/ ?>>
                        <?/*<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"] ?>"
                             alt="<?= $arItem["PREVIEW_PICTURE"]["ALT"] ?>"
                             title="<?= $arItem["PREVIEW_PICTURE"]["TITLE"] ?>"/>*/?>
                    </a>
                </div>
            <? endif; ?>
        <? endforeach; ?>
    </div>
    </div>
</div>


