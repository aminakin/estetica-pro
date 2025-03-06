<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
IncludeTemplateLangFile(__FILE__);?>

<?if (!empty($arResult)):?>
<div class="footer-title"><?= GetMessage('TITLE_MUNE');?></div>
                     
<nav class="footer-menu-list-store">
    <?
    foreach($arResult as $arItem):
        if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
            continue;
    ?>
    	<a href="<?=$arItem["LINK"]?>"  class="footer-menu-item">
    			<?=$arItem["TEXT"]?>
    	</a>

    <?endforeach?>
</nav>

<?endif?>