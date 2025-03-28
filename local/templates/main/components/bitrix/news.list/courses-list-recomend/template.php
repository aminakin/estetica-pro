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
$curlastPage = $APPLICATION->GetCurPage(false);
$this->setFrameMode(true); ?>

<div class="cources-blocks-list">
  <div class='cources-blocks-title major-title-pages'>
    <?= GetMessage('TITLE_BLOCK'); ?>
  </div>
  <div class="cources-blocks-slide">
    <? foreach ($arResult["ITEMS"] as $id => $arItem): ?>
      <?php
      $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
      $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

      if ($arItem['PROPERTIES']['RECOMEND']['VALUE'] == 'да' && is_array($arItem['PROPERTIES']['IMAGES']['VALUE'])) {
        $arItem['ONE_IMAGE'] = CFile::GetPath(array_shift($arItem['PROPERTIES']['IMAGES']['VALUE'])); ?>
        <a class="cources-list-element <?= $course_section; ?>" href="<?= $arItem['DETAIL_PAGE_URL'] ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>">
          <div class="cource-element-top">
            <div class="cource-element-image" style="background-image: url('<?= $arItem['ONE_IMAGE'] ?>')">
              <? if ($arItem['PROPERTIES']['TYPE_COURSE']['VALUE']) { ?>
                <div class="cource-element-type" <?= ($arItem['PROPERTIES']['LEABLE_COLOR']['VALUE'])? 'style="background: #0B7467;"':''?>>
                  <?= $arItem['PROPERTIES']['TYPE_COURSE']['VALUE'] ?>
                </div>
              <? } ?>
            </div>
          </div>
          <div class="cource-element-bottom">
            <div class="cource-element-name">
              <span><?= $arItem['NAME'] ?></span>
            </div>
            <div class="cource-element-price-date">
              <? if ($arItem['PROPERTIES']['PERIOD']['VALUE']) { ?>
                <div class="cource-element-date">
                  <?= $arItem['PROPERTIES']['PERIOD']['VALUE'] ?>
                </div>
              <? } ?>
              <? if ($arItem['PROPERTIES']['PRICE']['VALUE']) { ?>
                <div class="cource-element-price">
                  <?= CurrencyFormat($arItem['PROPERTIES']['PRICE']['VALUE'], "RUB"); ?>
                </div>
              <? } ?>
              <div class="cource-element-link">
                <?= GetMessage('WRITE_MORE') ?>
              </div>
            </div>
          </div>
        </a>
      <? } ?>
    <?php endforeach; ?>
  </div>
  <div class="cources-link-more"><a href="/about-courses/"><?= GetMessage('MORE') ?></a></div>
</div>