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
<div class="personal-list-title">
    <?= $arResult['NAME'];?>
</div>
<div class="personal-list">
	<ul class="personal-items_wrap">
		<?foreach($arResult["SECTION_ITEMS"] as $idSection => $arSection):?>
			<div class="personal-section-title">
				<?= $arSection['NAME']?>
			</div>
			<?$JSParams[] = $idSection;?>
			<div class="personal-items_<?= $idSection?>">
				<?foreach($arSection["ITEMS"] as $arItem):?>
					<li class="personal-item">
						<div class="personal-item_image">
							<img src="<?= $arItem["PREVIEW_PICTURE"]['SRC'];?>" />
						</div>
						<div class="personal-item_name">
							<?= $arItem["NAME"];?>
						</div>
						<? if($arItem["PREVIEW_TEXT"]){?>
							<div class="personal-item_text">
								<?= $arItem["PREVIEW_TEXT"];?>
							</div>
						<?}?>
					</li>
				<?endforeach;?>
			</div>
		<?endforeach;?>
	</ul>
</div>
<script>
  	BX.ready(function () {
		let paramJsObj = new paramJs(<?= json_encode($JSParams) ?>);
	});
</script>