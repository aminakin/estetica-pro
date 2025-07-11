<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true){
	die();
}
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
<div class="experiense-list" id="experiense-main">
	<div class="experiense-list-title major-title-pages">
	    <?= $arResult['NAME'];?>
	</div>
	<div class="experiense-list-slide">
		<?php foreach($arResult["ITEMS"] as $arItem):?>
			<?php
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<div class="experiense-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="experiense-img">
					<img src="<?= $arItem["PREVIEW_PICTURE"]["SRC"];?>" />
				</div>
				<div class="experiense-name">
					<?= $arItem["NAME"]?>
				</div>
			</div>
		<?php endforeach;?>
	</div>
	<div class="experiense-link">
		<a href="https://www.instagram.com/estetica_pro.ru?igsh=MW1yMHVsNWM2dmtsZQ==&utm_source=qr"><?= GetMessage('CT_READ_MORE')?></a>
	</div>
</div>