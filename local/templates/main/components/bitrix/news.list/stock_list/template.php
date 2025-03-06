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

<section class="mainSlider">

<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>

<div class="slider">
<?foreach($arResult["ITEMS"] as $arItem):?>
<?//print_r($arItem['PROPERTIES']['SLIDER']['VALUE'])?>
<?
$image = CFile::GetPath($arItem['PROPERTIES']['SLIDER']['VALUE']);
?>
    <?
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>                
    <a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="slider-item" style="background-image: url('<?=$image?>');" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
        <div class="content">
            <div class="text">
               	<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):                		
                    $timestamp = MakeTimeStamp($arItem["DISPLAY_ACTIVE_FROM"]);
                    $day = date('j', $timestamp);
                    $monthName = FormatDate('F', $timestamp);?>
            	 	<span class="dt">
            	        <span class="day"><?=$day?></span>
            	        <span class="month"><?=$monthName?></span>
            	    </span>
            	<?endif?>   
                <div class="txt">
                    <?=$arItem["NAME"];?>
                </div>
            </div>
        </div>
    </a>
<?endforeach;?>    
</div>
<div class="clear"></div>

<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>

</section>
