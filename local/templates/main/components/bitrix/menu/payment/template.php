<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<?if (!empty($arResult)):?>
    <div class="footer-title"><?= GetMessage('TITLE_PAYMENT')?></div>      
    <nav class="footer-menu-list-payment">
        <?
        foreach($arResult as $arItem):
            if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
                continue;
        ?>
        	<a href="<?=$arItem["LINK"]?>"  class="footer-menu-item"> <?=$arItem["TEXT"]?> </a>

        <?endforeach?>
    </nav>
    <img src="<?= SITE_TEMPLATE_PATH ?>/images/svg/payment.png">
<?endif?>