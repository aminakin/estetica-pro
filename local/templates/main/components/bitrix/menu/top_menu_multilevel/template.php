<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>

<nav class="top">
    <ul>

<?
$previousLevel = 0;

foreach($arResult as $arItem):?>
	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></div></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>
	<?if ($arItem["IS_PARENT"]):?>
		<?
		echo '<pre>';
		var_export($arItem["PARAMS"]);
		echo '</pre>';

		$strImg = '';
		if(intval($arItem["PARAMS"]["PICTURE"])>0){
			$img = CFile::ResizeImageGet($arItem["PARAMS"]["PICTURE"], array('width'=>38, 'height'=>38),BX_RESIZE_IMAGE_PROPORTIONAL, false); 
			$strImg = '<img alt="" src="'.$img['src'].'" />';
		}
		?>
		<?if ($arItem["DEPTH_LEVEL"] == 1):?>
			<li class="toggleCont 1">
				<a href="<?=$arItem["LINK"]?>" class="toggleLink <?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item 1<?endif?>">
					<?=$arItem["TEXT"]?>
					<?= $strImg;?>
				</a>
				<div class="toToggle">
					<ul class="inner">


		<?elseif($arItem["DEPTH_LEVEL"] == 2):?>
			<li class="toggleCont 2">
				<a href="<?=$arItem["LINK"]?>" class="toggleLink 1<?if ($arItem["SELECTED"]):?> item-selected<?endif?>">
					<?=$arItem["TEXT"]?>
					<?= $strImg;?>
				</a>
				<div class="toToggle" style="display:none;">
					<ul class="inner">

		<?else:?>
			<li class="toggleCont 3">
				<a href="<?=$arItem["LINK"]?>" class="toggleLink 2<?if ($arItem["SELECTED"]):?> item-selected<?endif?>">
					<?=$arItem["TEXT"]?>
					<?= $strImg;?>
				</a>
				<div class="toToggle">
					<ul class="inner">
		<?endif?>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li>
					<a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item 2<?endif?>">
						<?=$arItem["TEXT"]?>
						<?= $strImg;?>
					</a>
				</li>
			<?elseif($arItem["DEPTH_LEVEL"] == 2):?>
				<li>
					<a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item 3<?endif?>">
						<?=$arItem["TEXT"]?>
						<?= $strImg;?>
					</a>
				</li>
			<?else:?>
				<li class>
					<a href="<?=$arItem["LINK"]?>" <?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>>
						<?=$arItem["TEXT"]?>
						<?= $strImg;?>
					</a>
				</li>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1):?>
				<li>
					<a href="" class="1<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item 4<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>">
						<?=$arItem["TEXT"]?>
						<?= $strImg;?>
					</a>
				</li>
			<?elseif($arItem["DEPTH_LEVEL"] == 2):?>
				<li>
					<a href="" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item 5<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>">
						<?=$arItem["TEXT"]?>
						<?= $strImg;?>
					</a>
				</li>
			<?else:?>
				<li>
					<a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>">
						<?=$arItem["TEXT"]?>
					</a>
				</li>
			<?endif?>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></div></li>", ($previousLevel-2) );?>
<?endif?>

	</ul>
</nav>
<?endif?>