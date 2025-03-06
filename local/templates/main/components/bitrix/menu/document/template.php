<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;?>

<?if (!empty($arResult)):?>

 <label class="footer-input-label">
    <input type="checkbox" style="display: none;" class="footer-input-checkbox" data-name="document" onclick="openMenu(this)"/>
    <div class="footer-title"><?= 'Документы сайта';?></div>
    <div class="footer-title-arrow"></div>
</label>            
<nav class="bottom footer-menu footer-menu-list footer-menu-list-document">
<?
foreach($arResult as $arItem):
    if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
        continue;
?>
	<a href="<?=$arItem["LINK"]?>"  class="footer-menu-item"> <?=$arItem["TEXT"]?> </a>
    <?/*if($arItem["SELECTED"]):?>
        <a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a>
    <?else:?>
        <a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
    <?endif*/?>

<?endforeach?>

</nav>

<?endif?>