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
<div class="reviews-list" id="reviews-main">
	<div class="reviews-list-title">
	    <?= $arResult['NAME'];?>
	</div>
	<div class="reviews-list-slide">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<? $count++;
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<??>
			<div class="review-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<div class="review-img" style="background-image: url('<?= $arItem["PREVIEW_PICTURE"]["SRC"];?>');"></div>
				<div class="review-name">
					<?= $arItem["NAME"]?>
				</div>
				<div class="review-text_wrap">
					<span class="review-text">
						<?= $arItem["PREVIEW_TEXT"];?>
					</span>
					<span class="read-more"><?= GetMessage('CT_READ_MORE');?></span>
					<span class="less-more"><?= GetMessage('CT_LESS_MORE');?></span>
					<span class="review-text-full">
						<?= $arItem["PREVIEW_TEXT"];?>
					</span>
				</div>
			</div>
		<?endforeach;?>
	</div>
</div>