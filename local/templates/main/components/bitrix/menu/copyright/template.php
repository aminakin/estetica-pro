<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);?>

<?if (!empty($arResult)):?>

<?
foreach($arResult as $arItem):
    if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
        continue;
?>
    <span class="footer-copyright-separator hidden-xs">·</span>
	<a href="<?=$arItem["LINK"]?>"  class="footer-copyright-text hidden-xs"> <?=$arItem["TEXT"]?> </a>
    <?/*if($arItem["SELECTED"]):?>
        <a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a>
    <?else:?>
        <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
    <?endif*/?>

<?endforeach?>


<?endif?>