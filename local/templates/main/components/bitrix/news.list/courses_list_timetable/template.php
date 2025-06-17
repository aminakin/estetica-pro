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
    <section class="cources-blocks-list timetable" id="cources-blocks">
        <div class="courses-block__slider" id="sbb">
<?}?>
    <?
    $i=0;
    foreach($arResult["ITEMS"] as $arItem):?>
        <? if($arItem['IBLOCK_SECTION_ID'] == 1){
            $course_section = 'nail';
        }elseif($arItem['IBLOCK_SECTION_ID'] == 2){
            $course_section = 'lash';
        }?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <?$i++;?>
        <div class="cources-list-elementtimetable" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="cources-list-element-wraptimetable">
            <div class="cources-prev-list-bg" >
            	<?//if ($USER->IsAdmin()){
            		if($arItem['PROPERTIES']['IMAGE_TIMETABLE']['VALUE']){
            			foreach ($arItem['PROPERTIES']['IMAGE_TIMETABLE']['VALUE'] as  $images) {?>
	            			<img src="<?=CFile::GetPath($images);?>" alt="timetable-image" /><br>
	            		<?}
            		}
            	//}?>
                    <?/*if ($arItem["PREVIEW_PICTURE"]["SRC"]){?>
                        <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" />
                    <?}*/?>
            </div>
            <div class="datecaption">
             <? if($arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE']):?>
                    <?if (stripos($arItem['DISPLAY_PROPERTIES']['BEGIN']['~VALUE'], '<span>')!==false)
                    {
                        echo $arItem['DISPLAY_PROPERTIES']['BEGIN']['~VALUE'];
                    }
                    else
                    {
                        if (stripos($arItem['DISPLAY_PROPERTIES']['BEGIN']['~VALUE'], ' ')!==false)
                        {
                            $vrem = explode(' ', $arItem['DISPLAY_PROPERTIES']['BEGIN']['~VALUE']);
                            $vrem[0] = '<span>'.$vrem[0].'</span>';
                               echo implode(' ', $vrem);
                        }
                        else
                        {
                            $vrem = explode('.', $arItem['DISPLAY_PROPERTIES']['BEGIN']['~VALUE']);
                            $vrm = '<span>'.$vrem[0].'</span> ';
                            unset($vrem[0]);
                            echo $vrm.implode('.', $vrem);
                        }
                    }?>
             <?endif;?>
            </div>
            <?
            if ($i==count($arResult['ITEMS']) && $_REQUEST['PAGEN_1']!=$totalPages && $totalPages!=1){?>
                <div class="load-items-fake last cources-element hideMobile">
                    <div class="title">
                        <?=GetMessage('MORE')?>
                    </div>
                </div>
            <?}?>
            </div>
        </div>
    <?endforeach;?>
    
    <div class="phone_courses col-xs-12 col-sm-12 clearfix">Записаться на курс:<a class="couses-phone" href="https://wa.me/79229776867" target="_blank"><i class="fa fa-phone" aria-hidden="true"></i> +7 8332 77 68 67</a></div>
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



