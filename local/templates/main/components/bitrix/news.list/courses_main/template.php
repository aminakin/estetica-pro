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
$this->setFrameMode(true);
?>

 <? /*
<?$num = 1;?>
<section class="courses">
	<div class="left">
	    <a href="<?=$arResult["ITEMS"][0]['DETAIL_PAGE_URL'];?>" class="title"><span class="num">01</span><?=$arResult["ITEMS"][0]['NAME'];?></a>
	    <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["ITEMS"][0]["PREVIEW_TEXT"]):?>	
	        <div class="txt"><?echo $arResult["ITEMS"][0]["PREVIEW_TEXT"];?></div>
	    <?endif;?>
		<?$zv = '';
		$cond = '';
		if ($arResult["ITEMS"][0]['PROPERTIES']['PRICE_CONDITION']['VALUE'])
		{
			$zv = '*';
			$cond = '<div class="pcond">*'.$arResult["ITEMS"][0]['PROPERTIES']['PRICE_CONDITION']['VALUE'].'</div>';
		}?>
		<?if (is_int($arResult["ITEMS"][0]['DISPLAY_PROPERTIES']['PRICE']['VALUE']))
		{?>
			<div class="price"><?=format_price($arResult["ITEMS"][0]['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?>&nbsp;<span class="rub">Р</span><?=$zv?></div>
		<?}
		else
		{?>
			<div class="price"><?=$arResult["ITEMS"][0]['DISPLAY_PROPERTIES']['PRICE']['VALUE']?><?=$zv?></div>
		<?}?>
		<?=$cond;?>
	    <a href="<?=$arResult["ITEMS"][0]['DETAIL_PAGE_URL'];?>" class="whiteBtn">Подробнее</a>
	    <img src="<?=$arItem["PICTURE_SMALL"]["src"]?>" alt="">
	    <div class="clear"></div>
    </div>
    <div class="right">
    	<div class="lines">
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>

				<?if(!$i==0){?>
		            <div class="line" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="title"><span class="num">0<?=$num;?></span><?=$arItem['NAME'];?></a>
		                <?if($arItem['DISPLAY_PROPERTIES']['SALE']):?>
		                	<span class="proc">%</span>
		                <?endif;?>
		            </div>
				<?}?>
				<?$i = $i+1;?>
				<?$num = $num + 1;?>
			<?endforeach;?>
			<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
		</div>
		<a href="/courses/" class="all"><span>Все курсы</span></a>
    	<div class="clear"></div>
	</div>
	<div class="clear"></div>
</section>

 */?>
 
 <section class="halfBan">
	<?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
	    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	        <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
	    </a>
	<?endforeach;?>
    <div class="clear"></div>
</section>

 

