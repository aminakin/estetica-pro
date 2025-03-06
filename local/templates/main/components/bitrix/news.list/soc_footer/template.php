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
<div class="social">
    <?foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>                
        <a href="<?=$arItem['PROPERTIES']['NET']['VALUE']?>" target="_blank" class="<?=$arItem['CODE']?> footer-soc-desktop" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="footer-link-soc-icon">
                <? if ($arItem["NAME"] === 'instagram') {?>
                    <div class="footer-link-soc-icon-block footer-link-soc-icon-instagram"></div>
                <?} else if ($arItem["NAME"] === 'vkontakte'){?>
                    <div class="footer-link-soc-icon-block footer-link-soc-icon-vkontakte"></div>
                <?} else {?>
                    <?echo $arItem["PREVIEW_TEXT"];?>
                <?}?>
            </div>
            <div class="footer-link-soc-text"><?= $arItem["NAME"];?></div>
        </a>
    <?endforeach;?>            
    <div class="clear"></div>
</div>


