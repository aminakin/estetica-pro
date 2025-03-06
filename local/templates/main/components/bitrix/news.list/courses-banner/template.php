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

<div class="courses-banner-wrapper">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <div class="courses-banner" decktop-img="<?= $arItem["FIELDS"]["DETAIL_PICTURE"]["SRC"]?>" mobile-img="<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"]?>')">
            <div class="courses-banner-text-wrap">
                <div class="courses-banner-title"><?= $arItem["PREVIEW_TEXT"]?></div>
                <div class="courses-banner-text"><?= $arItem["DETAIL_TEXT"]?></div>    
                <a class="courses-link" href="<?= $arItem["PROPERTIES"]["LINK"]["VALUE"] ?>"><?= getMessage('CT_BNL_ELEMENT_LINK');?></a>
            </div>
        </div>
    <? endforeach; ?>
</div>


