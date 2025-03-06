<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>
	<nav class="top-menu">
		<ul>
			<? $previousLevel = 0;
			foreach($arResult as $arItem):?>
				<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
					<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
				<?endif?>
				
				<?if ($arItem["IS_PARENT"]):?>
						<li class="header-menu__item catalog-top-menu-catalog">
							<a class="catalog-top-menu-catalog" href="<?=$arItem["LINK"]?>"><span></span><span><?=$arItem["TEXT"]?></span></a>
							<div class="header-menu__item-modal">
							<div class="catalog-menu-block">
							<div class="bx_sitemap_oneline">
							<ul class="bx_sitemap_ul_oneline">
				<?else:?> 
						<li class>
							<a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
						</li>
				<?endif?>
				<?$previousLevel = $arItem["DEPTH_LEVEL"];?>
			<?endforeach?>
				
			<?if ($previousLevel > 1):?>
				<?=str_repeat("</ul></div></div></div></li>", ($previousLevel-1) );?>
			<?endif?>
		</ul>
	</nav>
<?endif?>