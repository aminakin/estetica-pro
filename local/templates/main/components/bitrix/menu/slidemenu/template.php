<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if (!empty($arResult)):?>
          
<nav class="bottom footer-menu footer-menu-list-slidemenu">
<?
foreach($arResult as $arItem):
    if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
        continue;?>
	<a href="<?=$arItem["LINK"]?>"  class="footer-menu-item"> <?=$arItem["TEXT"]?> </a>
<?endforeach?>

</nav>

<?endif?>