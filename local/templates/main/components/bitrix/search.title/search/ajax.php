<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?
if(!empty($arResult["CATEGORIES"])):?>
	<div class="results">
		<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
			<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
				
				<?$ar_res = CPrice::GetBasePrice($arItem['ITEM_ID']);
				?>
				<?if($category_id === "all"):?>
					<a href="<?echo $arItem["URL"]?>" class="all"><?echo $arItem["NAME"]?></a>
				<?elseif(isset($arItem["ICON"])):?>
					<a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$ar_res["PRICE"];?></a>
				<?else:?>
					<a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"]?></a>
				<?endif;?>
				
			<?endforeach;?>
			
		<?endforeach;?>
	</div>

	
<?endif;?>