<?

use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
<div class="about-number-wrap">
	<div class='about-number-title'>
		<? if($arResult['CODE'] !== 'numderMain'):?>	
			<?= GetMessage('CT_BNL_ABOUT_US');?>
		<? else:?>
			<?= $arResult['DESCRIPTION']?>
		<? endif;?>
	</div>
	<ul class="about-number-items">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			?>
			<li class="about-number-item">
				<div class="about-number-item_top">
					<?= $arItem["PREVIEW_TEXT"];?>
				</div>
				<div class="about-number-item_bottom">
					<?= $arItem["DETAIL_TEXT"];?>
				</div>
			</li>
		<?endforeach;?>
	</ul>
</div>
<div class="about-number-more">
	<a href="/about/"><?= Loc::getMessage('CT_BNL_MORE')?></a>
</div>