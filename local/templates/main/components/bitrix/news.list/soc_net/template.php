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

<section class="social page">
    <div class="text">
        <span>Cледуйте<br>
        за нами<br>
        в соцсетях</span>
    </div>

	<?foreach($arResult["ITEMS"] as $arItem):?>
        <?
            $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
            $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
	    <a href="<?=$arItem['PROPERTIES']['NET']['VALUE']?>" target="_blank" class="<?=$arItem['CODE']?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
	        <?echo $arItem["PREVIEW_TEXT"];?>
	    </a>
    <?endforeach;?>
    <div class="clear"></div>
</section>

