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
<div class="why-list_wrap">
	<div class="why-list_title">
		<p>
			<?= GetMessage('CT_BNL_WHY_TITLE');?>
		</p>
	</div>
	<div class="why-list">
		<div class="why-picture" style="background-image: url('<?= $arResult['PICTURE_SECTION_URL']?>')">
		</div>
		<ul class="why-items_wrap">
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<li class="why-item">
					<div class="why-item_text">
						<?= $arItem["PREVIEW_TEXT"];?>
					</div>
				</li>
			<?endforeach;?>
		</ul>
	</div>
</div>