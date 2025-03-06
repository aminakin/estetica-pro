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
$this->setFrameMode(true);
// номер текущей страницы
$curPage = $arResult["NAV_RESULT"]->NavPageNomer;
// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount;
// номер постраничной навигации на странице
$navNum = $arResult["NAV_RESULT"]->NavNum; ?>
<?php if ($arParams["DISPLAY_TOP_PAGER"]): ?>
  <?= $arResult["NAV_STRING"] ?><br />
<?php endif; ?>
<?php if ($arParams["AJAX"] != "Y"){ ?>
<section class="cources-blocks-list" id="cources-blocks">
  <?php } ?>
  <?php
  $i = 0;
  foreach ($arResult["ITEMS"] as $arItem):?>
    <?php if ($arItem['IBLOCK_SECTION_ID'] == 1):
      $course_section = 'nail';
      elseif ($arItem['IBLOCK_SECTION_ID'] == 2):
        $course_section = 'lash';
    endif; ?>
    <?php
    $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
    $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
    ?>
    <?php $i++; ?>

      <div class="cources-list-element <?= $course_section; ?>" id="<?= $this->GetEditAreaId($arItem['ID']); ?>"
           onclick='window.location.href="<?= $arItem['DETAIL_PAGE_URL'] ?>"'>
          <div class="cources-list-element-wrap">
              <div class="type-course"><?= $arItem['PROPERTIES']['TYPE_COURSE']['VALUE'] ?><span
                          class="cources-list-price">, <?php print_r($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']) ?>
                  <?php if ($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == 'Бесплатно' || $arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == NULL): ?>
                  <?php else: ?>
                      ₽
                  <?php endif; ?>
                            </span>
              </div>
              <div class="courses-list-name">
                  <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>"><?= $arItem["NAME"] ?></a>
              </div>
              <div class="date-begin">
                <?php
                /*$dateRange = Courses::getDateRange($arItem['PROPERTIES']['PERIOD_DATE']['VALUE'], $arItem['PROPERTIES']['PERIOD_DATE']['DESCRIPTION'], $arItem['PROPERTIES']['PREFIX_PERIOD_DATE']['VALUE']);

                if (strlen($dateRange)) { ?>
                  <?php if ($arItem['PROPERTIES']['COUNT_DAY']['VALUE']) { ?>
                        <span><?= $arItem['PROPERTIES']['COUNT_DAY']['VALUE'] ?></span>:
                  <?php } ?>
                  <?= $dateRange ?>
                <? } else { ?>
                  <?php if ($arItem['PROPERTIES']['COUNT_DAY']['VALUE']) { ?>
                        <span><?= $arItem['PROPERTIES']['COUNT_DAY']['VALUE'] ?></span>:
                  <?php } ?>
                  <?php if ($arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE']): ?>
                    <?= $arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE'] ?>
                  <?php endif; ?>
                <?php } */?>

              </div>
            
          </div>
      </div>
  <?php endforeach; ?>
</section>
<?php if ($arParams["DISPLAY_BOTTOM_PAGER"]) {
  $leftItems = $arResult['NAV_RESULT']->NavRecordCount - ($arResult['NAV_RESULT']->NavPageNomer * $arResult['NAV_RESULT']->NavPageSize);
  if ($leftItems > $arResult['NAV_RESULT']->NavPageSize) $leftItems = $arResult['NAV_RESULT']->NavPageSize; ?>
    <div class="pages-container">
      <?php if ($leftItems > 0): ?>
          <a href="javascript:void(0)" class="load-items last cources-element hideFull">
              <div class="title"><?= GetMessage('MORE'); ?></div>
          </a>
      <?php endif; ?>
        <div class="hideMobile"><?php if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
                <br /><?= $arResult["NAV_STRING"] ?><?php endif; ?></div>
    </div> <?php
} ?>




