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
$curlastPage = $APPLICATION->GetCurPage(false);
$this->setFrameMode(true);
// номер текущей страницы
$curPage = $arResult["NAV_RESULT"]->NavPageNomer;
// всего страниц - номер последней страницы
$totalPages = $arResult["NAV_RESULT"]->NavPageCount;
// номер постраничной навигации на странице
$navNum = $arResult["NAV_RESULT"]->NavNum;?>
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<?endif;?>
<?if($arParams["AJAX"] != "Y"){?>
    <section class="cources-blocks-list" id="cources-blocks">
<?}?>
    <?
    $i=0;
    foreach($arResult["ITEMS"] as $arItem):?>

        <? if($arItem['IBLOCK_SECTION_ID'] == 1):
            $course_section = 'nail';
        elseif($arItem['IBLOCK_SECTION_ID'] == 2):
            $course_section = 'lash';
        endif;?>
        <?  
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <?$i++;?>

        <div class="cources-list-element <?= $course_section; ?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="cources-list-element-wrap">
                <img class="logo-course" src="/images/logo_course.png">
                <div class="cources-prev-list-bg" >
                    <span class="cources-name hideFull hideMobile"><?=$arItem["NAME"];?></span>
                </div>
                <div class="courses-list-preview">
                    <div class="courses-list-text">
                        <?if($arItem['PROPERTIES']['COUNT_DAY']['VALUE']):?>
                            <div class="count-day"><?=$arItem['PROPERTIES']['COUNT_DAY']['VALUE']?></div>
                        <?endif;?>
                        <div class="courses-list-name">
			                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
			            </div>
                        <?if($arItem['PROPERTIES']['TYPE_COURSE']['VALUE']):?>
                            <div class="type-course"><?=$arItem['PROPERTIES']['TYPE_COURSE']['VALUE']?></div>
                        <?endif;?>
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="but-more">подробнее</a>
                    </div>
                    <div class="courses-list-text-hover">
                        <div class="name-type-count">
                            <?=$arItem["NAME"]?><?if($arItem['PROPERTIES']['TYPE_COURSE']['VALUE']):?>, <span><?=$arItem['PROPERTIES']['TYPE_COURSE']['VALUE']?></span><?endif;?><?if($arItem['PROPERTIES']['COUNT_DAY']['VALUE']):?>, <span><?=$arItem['PROPERTIES']['COUNT_DAY']['VALUE']?><span><?endif;?>
                        </div>
                        <div class="cources-list-price">
                            <?print_r($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?> 
                            <?if($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == 'Бесплатно' || $arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == NULL):?>
                            <?else:?>
                                ₽
                            <?endif;?>
                        </div>
                        <div class="date-begin">
                            <?if($arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE']):?>
                                <?=$arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE']?>
                            <?endif;?>
                        </div>
                        <a class="write-course">записаться</a>
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="but-more">подробнее</a>
                    </div>
                </div>
                <?
                if ($i==count($arResult['ITEMS']) && $_REQUEST['PAGEN_1']!=$totalPages && $totalPages!=1) {?>
                    <div class="load-items-fake last cources-element hideMobile">
                        <div class="title">
                            <?=GetMessage('MORE')?>
                        </div>
                    </div>
                <?}?>
            </div>
        </div>
    <?endforeach;?>
</section>
<? if ($arParams["DISPLAY_BOTTOM_PAGER"]){
$leftItems = $arResult['NAV_RESULT']->NavRecordCount - ($arResult['NAV_RESULT']->NavPageNomer * $arResult['NAV_RESULT']->NavPageSize);
    if ($leftItems > $arResult['NAV_RESULT']->NavPageSize) $leftItems = $arResult['NAV_RESULT']->NavPageSize; ?>
    <div class="pages-container">
        <? if ($leftItems > 0): ?>
            <a href="javascript:void(0)"  class="load-items last cources-element hideFull"><div class="title"><?=GetMessage('MORE');?></div></a>
        <? endif; ?>
        <div class="hideMobile"><? if ($arParams["DISPLAY_BOTTOM_PAGER"]): ?><br/><?= $arResult["NAV_STRING"] ?><? endif; ?></div>
    </div> <?
}?>




