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
if ($arResult['IBLOCK_SECTION_ID'] == 1):
	$course_section = 'nail';
elseif ($arResult['IBLOCK_SECTION_ID'] == 2):
	$course_section = 'lash';
endif;
//$dateRange = Courses::getDateRange($arResult['PROPERTIES']['PERIOD_DATE']['VALUE'], $arResult['PROPERTIES']['PERIOD_DATE']['DESCRIPTION']);

?>
<section class="blocks-detail-course">
	<div class="top-wrap-course">
		<div class="top-wrap-course-left">
			<div class="top-wrap-title">
				<?= $arResult["NAME"] ?>
			</div>
			<?php if (!empty($arResult['PROPERTIES']['TYPE_COURSE']['VALUE'])): ?>
				<div class="type-course">
					<div><?php echo $arResult['PROPERTIES']['TYPE_COURSE']['VALUE'] . " "; ?></div>
					<?

					//var_export($arResult['PROPERTIES']['PERIOD_DATE']['DESCRIPTION']);
					foreach ($arResult['PROPERTIES']['PERIOD_DATE']['DESCRIPTION'] as $key => $item) {
						if (preg_match('/(\d{2}\.\d{2}\.\d{4})/', $item, $matches)) {
							$date[] = ' - ' . $matches[1];
						}
					}

					foreach ($arResult['PROPERTIES']['PERIOD_DATE']['VALUE'] as $key => $value) {
						$currentDate = strtotime($value);
						$now = time();
						//$nextDate = isset($arItem['PROPERTIES']['PERIOD_DATE']['VALUE'][$key + 1]) ? strtotime($arItem['PROPERTIES']['PERIOD_DATE']['VALUE'][$key + 1]) : null;
						if ($currentDate <= $now) {
							continue;
						} else {
							//$count++;
							//if ($count == 1) {
							echo date('d.m.Y', $currentDate) . $date[$key] . "\n <br>";
							//}
						}
					}

					unset($date);
					?>
					<span> <?= $arResult['PROPERTIES']['FORM_STYLE']['VALUE']; ?></span>
				</div>
			<?php endif; ?>
			<a href="https://wa.me/79229776867" target="_blank">
				<?= GetMessage('WRITE'); ?>
			</a>
		</div>
		<div class="top-wrap-course-right">
			<ul class="text-course">
				<li class="prices-date-course">
					<?= classBuildSCG::buildSVG('arrow', $templateFolder . "/img"); ?>
					<?php if ($arResult['PROPERTIES']['PRICE']['VALUE'] == 'Бесплатно' || $arResult['PROPERTIES']['PRICE']['VALUE'] == NULL): ?>
						<span><?= GetMessage('FREE'); ?></span>
						<span><?= GetMessage('TOP_PRICES'); ?></span>
					<?php else: ?>
						<span><?= GetMessage('TOP_PRICES'); ?></span>
						<span><?= CurrencyFormat($arResult['PROPERTIES']['PRICE']['VALUE'], "RUB"); ?></span>
					<?php endif; ?>
				</li>
				<?php if (!empty($arResult['PROPERTIES']['INSTR']['VALUE'])): ?>
					<li class="instructor">
						<?= classBuildSCG::buildSVG('arrow', $templateFolder . "/img"); ?>
						<span><?= GetMessage('INSTRUCTOR'); ?></span>
						<span><?= $arResult['PROPERTIES']['INSTR']['VALUE'] ?></span>
					</li>
				<?php endif; ?>
				<?php if (!empty($arResult['PROPERTIES']['PERIOD']['VALUE'])): ?>
					<li>
						<?= classBuildSCG::buildSVG('arrow', $templateFolder . "/img"); ?>
						<span><?= GetMessage('DATE_COURSE') ?></span>
						<span><?= $arResult['PROPERTIES']['PERIOD']['VALUE'] ?> <?= $arResult['PROPERTIES']['TIME_COURSE']['VALUE'] ?></span>
					</li>
				<?php endif; ?>
				<?php if (!empty($arResult['PROPERTIES']['TYPE_GROUP']['VALUE'])): ?>
					<li>
						<?= classBuildSCG::buildSVG('arrow', $templateFolder . "/img"); ?>
						<span><?= GetMessage('COUNT_PEOPLE') ?></span>
						<span><?= $arResult['PROPERTIES']['TYPE_GROUP']['VALUE'] ?></span>
					</li>
				<?php endif; ?>
				<?php if (!empty($arResult['PROPERTIES']['QUANTITY_MODEL']['VALUE'])): ?>
					<li>
						<?= classBuildSCG::buildSVG('arrow', $templateFolder . "/img"); ?>
						<span><?= GetMessage('QUANTITY') ?></span>
						<span><?= $arResult['PROPERTIES']['QUANTITY_MODEL']['VALUE'] ?></span>
					</li>
				<?php endif; ?>
				<?php if (!empty($arResult['PROPERTIES']['TAKE_WITH']['VALUE'])): ?>
					<li>
						<?= classBuildSCG::buildSVG('arrow', $templateFolder . "/img"); ?>
						<span><?= GetMessage('TAKE_WITH') ?></span>
						<span><?= $arResult['PROPERTIES']['TAKE_WITH']['VALUE'] ?></span>
					</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<?php if (!empty($arResult['PREVIEW_TEXT'])): ?>
		<div class="preview-text">
			<?php echo $arResult["PREVIEW_TEXT"]; ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($arResult['DETAIL_TEXT'])): ?>
		<div class="detail-text">
			<div class="content-program">
				<div class="title-tab major-title-pages">
					<?= GetMessage('PROGRAMM'); ?>
				</div>
				<?php echo $arResult["DETAIL_TEXT"]; ?>
			</div>
			<span class="more-program"><?= GetMessage('MORE'); ?></span>
		</div>
	<?php endif; ?>
	<? $APPLICATION->IncludeComponent(
		"bitrix:news.list",
		"courses-present",
		array(
			"ACTIVE_DATE_FORMAT" => "",
			"ADD_SECTIONS_CHAIN" => "N",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_ADDITIONAL" => "",
			"AJAX_OPTION_HISTORY" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "N",
			"CACHE_FILTER" => "N",
			"CACHE_GROUPS" => "Y",
			"CACHE_TIME" => "36000000",
			"CACHE_TYPE" => "A",
			"CHECK_DATES" => "Y",
			"COMPONENT_TEMPLATE" => "courses-present",
			"DETAIL_URL" => "",
			"DISPLAY_BOTTOM_PAGER" => "N",
			"DISPLAY_DATE" => "N",
			"DISPLAY_NAME" => "Y",
			"DISPLAY_PICTURE" => "Y",
			"DISPLAY_PREVIEW_TEXT" => "Y",
			"DISPLAY_TOP_PAGER" => "N",
			"FIELD_CODE" => array(0 => "", 1 => "",),
			"FILTER_NAME" => "",
			"HIDE_LINK_WHEN_NO_DETAIL" => "N",
			"IBLOCK_ID" => IblockHelper::getIdByCode('bonusAfterLearn'),
			"IBLOCK_TYPE" => "courses",
			"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
			"INCLUDE_SUBSECTIONS" => "N",
			"MESSAGE_404" => "",
			"NEWS_COUNT" => "6",
			"PAGER_BASE_LINK_ENABLE" => "N",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "N",
			"PAGER_SHOW_ALWAYS" => "N",
			"PAGER_TEMPLATE" => ".default",
			"PAGER_TITLE" => "",
			"PARENT_SECTION" => "",
			"PARENT_SECTION_CODE" => "",
			"PREVIEW_TRUNCATE_LEN" => "",
			"PROPERTY_CODE" => array(),
			"SET_BROWSER_TITLE" => "N",
			"SET_LAST_MODIFIED" => "N",
			"SET_META_DESCRIPTION" => "N",
			"SET_META_KEYWORDS" => "N",
			"SET_STATUS_404" => "N",
			"SET_TITLE" => "N",
			"SHOW_404" => "N",
			"SORT_BY1" => "SORT",
			"SORT_BY2" => "ID",
			"SORT_ORDER1" => "ASC",
			"SORT_ORDER2" => "ASC",
			"STRICT_SECTION_CHECK" => "N"
		),
		$component
	); ?>
	<?php if (!empty($arResult['PROPERTIES']['IMAGES']['VALUE'])): ?>
		<div class="works-list">
			<div class="works-list-title major-title-pages">
				<?= GetMessage('FOTO'); ?>
			</div>
			<div class="works-list-slide">
				<?php foreach ($arResult['PROPERTIES']['IMAGES']['VALUE'] as $key => $photo) { ?>
					<?php $file = CFile::ResizeImageGet($photo, array('width' => 445, 'height' => 445), BX_RESIZE_IMAGE_EXACT, true); ?>
					<div class="work-item-img" style="background-image: url('<?= $file['src']; ?>');"> </div>
				<?php } ?>
			</div>
		</div>
	<?php endif; ?>
	<?/*	отзывы
		$reviewsId = $arResult['PROPERTIES']['REVIEWS']['VALUE'];
	?>
    <?$APPLICATION->IncludeComponent(
      "bitrix:news.list",
      "reviews",
	    Array(
	    	"ACTIVE_DATE_FORMAT" => "",
	    	"ADD_SECTIONS_CHAIN" => "N",
	    	"AJAX_MODE" => "N",
	    	"AJAX_OPTION_ADDITIONAL" => "",
	    	"AJAX_OPTION_HISTORY" => "N",
	    	"AJAX_OPTION_JUMP" => "N",
	    	"AJAX_OPTION_STYLE" => "N",
	    	"CACHE_FILTER" => "N",
	    	"CACHE_GROUPS" => "Y",
	    	"CACHE_TIME" => "36000000",
	    	"CACHE_TYPE" => "A",
	    	"CHECK_DATES" => "Y",
	    	"COMPONENT_TEMPLATE" => "reviews",
	    	"DETAIL_URL" => "",
	    	"DISPLAY_BOTTOM_PAGER" => "N",
	    	"DISPLAY_DATE" => "N",
	    	"DISPLAY_NAME" => "Y",
	    	"DISPLAY_PICTURE" => "Y",
	    	"DISPLAY_PREVIEW_TEXT" => "Y",
	    	"DISPLAY_TOP_PAGER" => "N",
	    	"FIELD_CODE" => array(0=>"",1=>"",),
	    	"FILTER_NAME" => $reviewsId,
	    	"HIDE_LINK_WHEN_NO_DETAIL" => "N",
	    	"IBLOCK_ID" => "67",
	    	"IBLOCK_TYPE" => "courses",
	    	"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
	    	"INCLUDE_SUBSECTIONS" => "N",
	    	"MESSAGE_404" => "",
	    	"NEWS_COUNT" => "6",
	    	"PAGER_BASE_LINK_ENABLE" => "N",
	    	"PAGER_DESC_NUMBERING" => "N",
	    	"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
	    	"PAGER_SHOW_ALL" => "N",
	    	"PAGER_SHOW_ALWAYS" => "N",
	    	"PAGER_TEMPLATE" => ".default",
	    	"PAGER_TITLE" => "",
	    	"PARENT_SECTION" => "",
	    	"PARENT_SECTION_CODE" => "",
	    	"PREVIEW_TRUNCATE_LEN" => "",
	    	"PROPERTY_CODE" => array(),
	    	"SET_BROWSER_TITLE" => "N",
	    	"SET_LAST_MODIFIED" => "N",
	    	"SET_META_DESCRIPTION" => "N",
	    	"SET_META_KEYWORDS" => "N",
	    	"SET_STATUS_404" => "N",
	    	"SET_TITLE" => "N",
	    	"SHOW_404" => "N",
	    	"SORT_BY1" => "SORT",
	    	"SORT_BY2" => "ID",
	    	"SORT_ORDER1" => "ASC",
	    	"SORT_ORDER2" => "ASC",
	    	"STRICT_SECTION_CHECK" => "N"
	    ),
        $component
	);*/ ?>
	<?php if ($arResult['PROPERTIES']['PRICE']['VALUE'] != 'Бесплатно'): ?>
		<div class="prices-wrapper-course">
			<div class="prices-course">
				<?php if ($arResult['PROPERTIES']['PRICE']['VALUE'] == 'Бесплатно' || $arResult['PROPERTIES']['PRICE']['VALUE'] == NULL): ?>
					<div class="price"><?= GetMessage('PRICES') ?> <?= GetMessage('FREE_CLASS') ?></div>
				<?php else: ?>
					<div class="price"><?= GetMessage('PRICES') ?> <?= CurrencyFormat($arResult['PROPERTIES']['PRICE']['VALUE'], "RUB"); ?></div>
					<?php if ($arResult['PROPERTIES']['INFO_BRONER']['VALUE']): ?>
						<?= $arResult['PROPERTIES']['INFO_BRONER']['~VALUE']['TEXT'] ?>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="write-course" id="write-course">
				<div class="title"><?= GetMessage('WRITE'); ?></div>
				<div class="text"><?= GetMessage('WRITE_OR_CALL'); ?></div>
				<div class="course-btn-wrap">
					<a href="https://wa.me/79229776867" target="_blank" class="whatapp">
						<?= GetMessage('PHONE_WHATAPP'); ?>
					</a>
					<a href="/courses/raspisanie/" target="_blank" class="table">
						<?= GetMessage('TABLE'); ?>
					</a>
				</div>
			</div>
		</div>
	<?php endif; ?>
</section>