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
    <section class="cources-blocks-list <? if ($curlastPage == '/courses/raspisanie/'){?>timetable<?}?>" id="cources-blocks">
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
        if($arItem['IBLOCK_SECTION_ID'] == '1216' && $curlastPage != '/courses/raspisanie/'){
                continue;
            }     
        
        
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <?$i++;?>
        <div class="cources-list-element <?= $course_section; ?> <? if ($curlastPage == '/courses/raspisanie/'){?>timetable<?}?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="cources-list-element-wrap <? if ($curlastPage == '/courses/raspisanie/'){?>timetable<?}?>">
            <div class="cources-prev-list-bg" >
                <? if ($curlastPage != '/courses/raspisanie/'){?><span class="cources-name hideFull hideMobile"><?=$arItem["NAME"];?></span><?}?>
                <div class="">
                   <? if ($curlastPage != '/courses/raspisanie/'){?> <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">  <?}?>
                   
                        <?if ($arItem["PREVIEW_PICTURE"]["SRC"])
                        {?>
                            <? if ($curlastPage == '/courses/raspisanie/'){?>
                                <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" />
                            <?}else{?>
                                <img src="<?=$arItem["PICTURE_SMALL"]["src"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" />
                            <?}?>
                            
                        <?}
                        else
                        {?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/nophoto_course.jpg"/>
                        <?}?>
                    <? if ($curlastPage != '/courses/raspisanie/'){?></a><?}?>
                </div>
                <? //var_export($arItem);?>
                <?/*div class="hideMobile">
                    <? if ($curlastPage != '/courses/raspisanie/'){?> <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">  <?}?>
                   
                        <?if ($arItem["DETAIL_PICTURE"]["SRC"])
                        {?>
                            <? if ($curlastPage == '/courses/raspisanie/'){?>
                                <img src="<?=$arItem["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arItem["DETAIL_PICTURE"]["ALT"]?>" />
                            <?}else{?>
                                <img src="<?=$arItem["PICTURE_SMALL"]["src"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" />
                            <?}?>
                            
                        <?}
                        else
                        {?>
                            <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/nophoto.svg"/>
                        <?}?>
                    <? if ($curlastPage != '/courses/raspisanie/'){?></a><?}?>
                </div*/?>
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
            <? if ($curlastPage != '/courses/raspisanie/'){?>
                <div class="courses-list-preview">
                    <div class="courses-list-text">
                        <div class="courses-list-name">
			                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
			            </div>
                        <?if($arItem['DISPLAY_PROPERTIES']['INSTR']['VALUE']):?>
                            <div class="instr"><span><?=$arItem['DISPLAY_PROPERTIES']['INSTR']['NAME']?></span>: <span class="insname"><?=$arItem['DISPLAY_PROPERTIES']['INSTR']['VALUE']?></span></div>
                        <?endif;?>
                        <div class="prvt" style="display: none;">
                        	<div>                        	
                        	<?if($arItem['DISPLAY_PROPERTIES']['PRICE']['~VALUE']):?>
                                <b><?=$arItem['DISPLAY_PROPERTIES']['PRICE']['~VALUE']?></b>
                            <?endif;?>
                            <?if($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']):?>
                                <?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?>
                            <?endif;?>
                            </div>
                        </div>
                    </div>
                    <div class="cources-list-price">
                        <?// var_export($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE']);?>
                        <?print_r($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?> 
                        <?if($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == 'Бесплатно' || $arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == NULL):?>
                            
                        <?else:?>
                            <i class="fa fa-rub" aria-hidden="true"></i>
                        <?endif;?>
                    </div>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="btn btn-link blackBtn hideMobile">Подробнее</a>
                </div>
            <?}?>
            <?/*div class="cources-logo showMobile">
                <div class="courses_list_text_small">
                    <?if($arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE']):?>
						<div>
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
						</div>
					<?endif;?>
					<div>
						<?if($arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']):?>
							<div><?=$arItem['DISPLAY_PROPERTIES']['PERIOD']['VALUE']?></div>
						<?endif;?>
						<?if($arItem['DISPLAY_PROPERTIES']['INSTR']['VALUE']):?>
							<div class="instr"><span><?=$arItem['DISPLAY_PROPERTIES']['INSTR']['NAME']?></span>:<br/><span class="insname"><?=$arItem['DISPLAY_PROPERTIES']['INSTR']['VALUE']?></span></div>
						<?endif;?>
					</div>
                </div>
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="whiteBtn">Подробнее</a>
                <div style="clear: both;"></div>
            </div*/?>

            <?
            if ($i==count($arResult['ITEMS']) && $_REQUEST['PAGEN_1']!=$totalPages && $totalPages!=1)
            {?>
                <div class="load-items-fake last cources-element hideMobile">
                    <div class="title">
                        <?=GetMessage('MORE')?>
                    </div>
                </div>
            <?}?>
            </div>
        </div>
    <?endforeach;?>
    
    <? if ($curlastPage == '/courses/raspisanie/'){?><div class="phone_courses col-xs-12 col-sm-12 clearfix">Запись ведется по телефону:<a class="couses-phone" href="tel:+78332776867"><i class="fa fa-phone" aria-hidden="true"></i> +7 8332 77 68 67</a></div><?}?>
</section>
<?/*if($arParams["AJAX"] != "Y"){?>
        </div>
    <?if($arResult["NAV_STRING"] && $arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <div class="load-items last cources-element hideFull"><? //hideFull?>
            <div class="title">
                <?=GetMessage('MORE')?>
            </div>
        </div>
        <script>
            $(function(){
                var newsSetLoader = new Loader({
                    root: '#cources-blocks',
                    path: '/courses/ajax.php',
                    newsBlock: '#sbb',
                    ajaxLoader: '.load-items',
                    COUNT: '<?=$arParams['NEWS_COUNT']?>',
                    SECTID: '<?=$arResult['SECTION']["PATH"][0]['ID']?>',
                    loadSett:{
                        endPage: <?=$totalPages?>,
                        navNum: <?=$navNum?>
                    }
                });
                newsSetLoader.init();
            });
        </script>
    <?endif;?>
    <div class="clear"></div>
    </section>
<?}*/
?>
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
<?/*div class="hideMobile">
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
    <br /><?=$arResult["NAV_STRING"]?>
<?endif;?>
</div*/?>


