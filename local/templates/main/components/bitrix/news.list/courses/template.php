<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
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
<div class="courses-full-list">
    <? foreach ($arResult["ITEMS"] as $arItem): ?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <div class="course-item">
            <div class="course-name">
                <?= $arItem['NAME'] ?>
            </div>
            <? if ($arItem['PROPERTIES']['TYPE_COURSE']['VALUE']): ?>
                <div class="course-type">
                    <?= $arItem['PROPERTIES']['TYPE_COURSE']['VALUE'] ?>
                </div>
            <? endif; ?>
            <div class="course-date">
                <?
                //$count = 0;
                foreach ($arItem['PROPERTIES']['PERIOD_DATE']['DESCRIPTION'] as $key => $item) {
                    if (preg_match('/(\d{2}\.\d{2}\.\d{4})/', $item, $matches)) {
                        $date[] = ' - ' . $matches[1];
                    }
                }

                foreach ($arItem['PROPERTIES']['PERIOD_DATE']['VALUE'] as $key => $value) {
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
                ?>
            </div>
            <? if ($arItem['PROPERTIES']['PRICE']['VALUE']): ?>
                <div class="course-price">
                    <? if ($arItem['PROPERTIES']['PRICE']['VALUE'] == 'Бесплатно'): ?>
                        <span><?= GetMessage('FREE_COURSE') ?></span>
                    <? else: ?>
                        <span><?= CurrencyFormat($arItem['PROPERTIES']['PRICE']['VALUE'], "RUB"); ?></span>
                    <? endif; ?>
                </div>
            <? endif; ?>
            <a class="course-link" href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                <?= GetMessage('MORE') ?>
            </a>
        </div>
    <? endforeach; ?>
</div>
<div class="courses-full-more">
    <? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif; ?>
    <div class="courses-list-more">
        <a class="link-table" href="/courses/raspisanie/"><?= GetMessage('MORE_TABLET') ?></a>
    </div>
</div>