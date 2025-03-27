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
<div class="courses-present" id="courses-present-main">
	<div class="courses-present-list-title">
	    <?= $arResult['NAME'];?>
	</div>
	<div class="courses-present-list">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<? $count++;
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<??>
			<div class="present-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="present-img" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"];?>');"></div>
				<div class="present-text">
					<?= $arItem["PREVIEW_TEXT"];?>
				</div>
			</div>
		<?endforeach;?>
	</div>
	<a href="https://wa.me/79229776867" target="_blank" >
		<?= GetMessage('WRITE');?>
	</a>
</div>