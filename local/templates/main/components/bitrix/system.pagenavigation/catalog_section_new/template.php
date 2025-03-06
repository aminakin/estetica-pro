<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
/*количество страниц указывается в component.php*/
$this->setFrameMode(true);

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");
?>


<div class="bx-pagination ozi <?=$colorScheme?>">
	<div class="bx-pagination-container">
		<ul class="text 3">
				<?if ($arResult["NavPageNomer"] > 1):?>

					<?if($arResult["bSavePage"]):?>
						<li class="1"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1"><?=GetMessage("nav_begin")?></a></li>
						
						<li class="2"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev")?></a></li>
						
					<?else:?>
						<li class="begining"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_begin")?></a></li>
						
						<?if ($arResult["NavPageNomer"] > 2):?>
							<li class="4" style="display: none"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]-1)?>"><?=GetMessage("nav_prev")?></a></li>
						<?else:?>
							<li class="5" style="display: none"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=GetMessage("nav_prev")?></a></li>
						<?endif?>
						
					<?endif?>

				<?else:?>
					<li class="begining"><?=GetMessage("nav_begin")?></li>
					<li style="display: none"><?=GetMessage("nav_prev")?></li>
				<?endif?>
				<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>
					
					<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
						<li class="bx-active"><span><?=$arResult["nStartPage"]?></span></li>
					<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
						<li class="8"><a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>"><?=$arResult["nStartPage"]?></a></li>
					<?else:?>
						<li class="page"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a></li>
					<?endif?>
					<?$arResult["nStartPage"]++?>
				<?endwhile?>
				<?//var_export($arResult);?>
				<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
					<? if($arResult['NavPageCount'] > 10):?>
				    	<li class="page decktop"><span class="dots" >...</span><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a></li>
					<?endif?>
					<? if($arResult['NavPageCount'] > 5):?>
				    	<li class="page mobile"><span class="dots" >...</span><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>"><?=$arResult["NavPageCount"]?></a></li>
					<?endif?>
				    <li class="forward"><a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=($arResult["NavPageNomer"]+1)?>"><?=GetMessage("nav_next")?></a></li>
				<?else:?>
					<?/*li class="12"><?=GetMessage("nav_next")?></?li><li?><?=GetMessage("nav_end")?></li*/?>
				<?endif?>
		</ul>
	</div>
	<div class="input-page">
		<form action="<?= $APPLICATION->GetCurDir();?>?PAGEN_1=">
			<span><?=GetMessage("nav_title")?></span>
			<input type="number" class="numpage" value="" name="PAGEN_1" min="1" max="<?=$arResult['NavPageCount'];?>">
			<input class="submit" type="submit" value="<?=GetMessage("nav_button")?>">
		</form>
	</div>	
</div>
<div style="clear:both"></div>
<script type="text/javascript">
	$('.numpage').val();
</script>