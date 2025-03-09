<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>

<?php 
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
    <?= classBuildSCG::buildSVG('cart', $templateFolder."/images" );?>
	<div><?= GetMessage('TITLE_CART')?></div>
</a>