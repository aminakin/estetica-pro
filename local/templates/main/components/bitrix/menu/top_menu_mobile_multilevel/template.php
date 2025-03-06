<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if (!empty($arResult)):?>


    <ul class="top-mobile-menu__catalog-list">

<?

$previousLevel = 0;
foreach($arResult as $arItem):?>
	<?
	if(!$arItem["PARAMS"]['ELEMENT_CNT']){
		$cnt = "";
	} else {
		$cnt = "(".$arItem["PARAMS"]['ELEMENT_CNT'].")";
	}
 	?>

    <?if ($arItem["DEPTH_LEVEL"] == 2):?>
        <li><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?> <?=$cnt?></a></li>
    <?endif?>


	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>



	</ul>
<?endif?>