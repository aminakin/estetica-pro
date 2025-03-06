<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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


<?php $res = CIBlockSection::GetList(['ID' => 'ASC'], ['IBLOCK_ID' => $arParams['IBLOCK_ID'], "CNT_ACTIVE" => "Y"], true);
$section = [];
while ($s = $res->GetNext()) {
  if ($s['ELEMENT_CNT'] <= 0) continue;
  $section[] = ['NAME' => $s['NAME'], 'URL' => $s['SECTION_PAGE_URL'], 'PICTURE' => $s['PICTURE']];
} ?>
<div class="courses-list-title" id="courses-list">
  <?= GetMessage('COURSES')?>
</div>
<div class="courses-list">
	<?foreach($section as $arItem):?>
		<div class="course-item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
			<div class="course-img" style="background-image: url('<?= CFile::GetPath($arItem["PICTURE"]);;?>');"></div>
			<div class="course-text">
        <div class="course-name">
			  	<?= $arItem["NAME"]?>
			  </div>
				<a class="course-link" href="<?= $arItem["URL"];?>">
          <?= GetMessage('MORE')?>
        </a>
			</div>
		</div>
	<?endforeach;?>
</div>
