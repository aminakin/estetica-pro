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
    <section class="sale-blocks" id="sale-blocks">
        <div id="sbb">
<?}?>
    <?
    $i=0;
    foreach($arResult["ITEMS"] as $arItem):?>
        <?
        $this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
        $this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
        ?>
        <?$i++;?>
        <div class="sale-element" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
            <div class="sale-prev-bg">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
                    <?if ($arItem["PREVIEW_PICTURE"]["SRC"])
                    {?>
                        <img src="<?=$arItem["PICTURE_SMALL"]["src"]?>" alt="<?=$arItem["PREVIEW_PICTURE"]["ALT"]?>" />
                    <?}
                    else
                    {?>
                        <img src="<?=SITE_TEMPLATE_PATH?>/images/icons/nophoto_action.png"/>
                    <?}?>
                </a>
                <?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
                    <div class="sale-prev-text">
                        <?=$arItem["PREVIEW_TEXT"];?>
                    </div>
                <?endif;?>

                <div class="sale-logo hideMobile">
					<?if ($arItem['PROPERTIES']['BRAND']['VALUE'])
					{?>
						<div class="sale-logo-img">
							<?
								$APPLICATION->IncludeComponent(
									"smedia:sale.brand",
									".default",
									array(
										"IBLOCK_ID" => 6,
										"BRAND_ID" => $arItem['PROPERTIES']['BRAND']['VALUE']
									),
									false
								);
							?>
						</div>
					<?}?>
                    <?if($arItem['PROPERTIES']['DATE_END_SALE']['VALUE']):?>
                    <?
                        $dateEndSale = strtotime($arItem['PROPERTIES']['DATE_END_SALE']['VALUE']);
                        $dateToday = strtotime(date('d.m.Y'));

                        if($dateEndSale < $dateToday):?>
                            <div class="icon-sale">
                                Срок акции истек
                            </div>
                        <?endif;
                    ?>
                    <?endif;?>
                    <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="whiteBtn">Подробнее</a>
                </div>
            </div>

            <div class="sale-logo showMobile">
				<?if ($arItem['PROPERTIES']['BRAND']['VALUE'])
				{?>
					<div class="sale-logo-img">
						<?
							$APPLICATION->IncludeComponent(
								"smedia:sale.brand",
								".default",
								array(
									"IBLOCK_ID" => 6,
									"BRAND_ID" => $arItem['PROPERTIES']['BRAND']['VALUE']
								),
								false
							);
						?>
					</div>
				<?}?>
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="whiteBtn">Подробнее</a>
                <div style="clear: both;"></div>
            </div>

            <div class="sale-name">
                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
            </div>
            <?
            if ($i==count($arResult['ITEMS']) && $_REQUEST['PAGEN_1']!=$totalPages && $totalPages!=1)
            {?>
                <div class="load-items-fake last sale-element hideMobile">
                    <div class="title">
                        <?=GetMessage('MORE')?>
                    </div>
                </div>
            <?}?>
        </div>
    <?endforeach;?>

<?if($arParams["AJAX"] != "Y"){?>
        </div>
    <?if($arResult["NAV_STRING"] && $arParams["DISPLAY_BOTTOM_PAGER"]):?>
        <div class="load-items last sale-element hideFull">
            <div class="title">
                <?=GetMessage('MORE')?>
            </div>
        </div>
        <script>
            $(function(){
                var newsSetLoader = new Loader({
                    root: '#sale-blocks',
                    path: '/sale/ajax.php',
                    newsBlock: '#sbb',
                    ajaxLoader: '.load-items',
                    COUNT: '<?=$arParams['NEWS_COUNT']?>',
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
<?}?>

