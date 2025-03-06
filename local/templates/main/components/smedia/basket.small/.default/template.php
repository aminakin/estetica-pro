<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php 
// var_export($arResult);
// 
global $smallBasketHeader;
if (empty($smallBasketHeader))
	$smallBasketHeader = 1;
else 
	$smallBasketHeader++;
?>

<a href="#basket" class="baskLink linkOpen menu-links" id="smallBasketHeader<?=$smallBasketHeader?>">
	<span class="small-basket-quantity">
		<?= $arResult['FAVORITE_QUANTITY']?>
	</span>
      <img src="<?= SITE_TEMPLATE_PATH; ?>/images/headerIconMobile/cart.svg" alt="cart"/>
</a>
