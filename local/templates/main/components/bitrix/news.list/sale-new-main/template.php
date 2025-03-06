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
<div class="sales-list">
	<ul class="sales-items_wrap">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			$position = $arItem['DISPLAY_PROPERTIES']['TEXT_POSITION']['VALUE_XML_ID'];
			$bg_color = $arItem['DISPLAY_PROPERTIES']['BG_COLOR']['VALUE_XML_ID'];?>
			<li class="sales-item <?= $position; ?> <?= $bg_color; ?>" style="background-image: url('<?= $arItem['PREVIEW_PICTURE']['SRC']?>')">
				<a href="<?= $arItem['DISPLAY_PROPERTIES']['LINK']['VALUE'] ?>">
					<span class="sales-item_name" >
						<?= $arItem["NAME"];?>
					</span>
					<?/* if( $arItem["PREVIEW_TEXT"] !== NULL):?>
						<span class="sales-item_text" >
							<?= $arItem["PREVIEW_TEXT"];?>
						</span>
					<?endif;*/?>
				</a>
			</li>
		<?endforeach;?>
	</ul>
</div>