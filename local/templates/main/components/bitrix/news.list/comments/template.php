<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<section class="comment">
    <h2>Комментарии</h2>
    <?if ($arResult['ITEMS'])
    {
        foreach($arResult["ITEMS"] as $arItem)
        {?>
            <div class="comment-item">
                <div class="text"><?=$arItem['PREVIEW_TEXT']?></div>
                <div class="h">
                    <?/*<span class="date">
                        <?=$arItem["DISPLAY_ACTIVE_FROM"]?>
                    </span> -*/?>
                    <span class="name"><?=$arItem['NAME']?></span>
                </div>
            </div>
        <?}
    }
    else
    {?>
        <div class="com">Комментариев нет. Вы можете оставить свой комментарий первым.</div>
    <?}?>
    <div class="btn-block"><a href="#comment" data-id='<?=$arParams['ElementID']?>' class="whiteBtn invert linkOpen">Оставить комментарий</a></div>
</section>