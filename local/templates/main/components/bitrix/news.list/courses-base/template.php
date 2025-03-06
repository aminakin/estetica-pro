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
<? if(!empty($arResult["ITEMS"])):?>
<div class="courses-base-wrap">
	<?foreach($arResult["ITEMS"] as $arItem):?>
		<?
		$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
		$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
		?>
		<div class="courses-base-img" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]['SRC'];?>');">
		</div>
		<div class="courses-base-text">
			<div class='courses-base-title'><?= $arItem["NAME"];?></div>
			<div class='courses-base-preview'><?= $arItem["PREVIEW_TEXT"];?></div>
			<div class='courses-base-detail'><?= $arItem["DETAIL_TEXT"];?></div>
		</div>
	<?endforeach;?>
</div>
<?endif;?>