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
<div class="works-list">
	<div class="works-list-title major-title-pages">
	    <?= $arResult['NAME'];?>
	</div>
	<div class="works-list-slide">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<? $count++;
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<? foreach ($arItem['PROPERTIES']['GALLERY']['VALUE'] as $key => $image) {?>
				<div class="work-item-img" style="background-image: url('<?= CFile::GetPath($image);?>');">
				</div>	
			<?}?>
		<?endforeach;?>
	</div>
</div>