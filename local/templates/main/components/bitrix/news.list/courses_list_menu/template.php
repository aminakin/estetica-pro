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
<section class="cources-menu">
<?}?>    
        <div class="section-menu">
            <div class="catalog-menu-element-title"><?= GetMessage('NAIL');?></div>   
            <?foreach($arResult["ITEMS"] as $arItem):
                if($arItem['IBLOCK_SECTION_ID'] == 1):?>
                    <div class="cources-menu-element lash" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
                        <?if($arItem['PROPERTIES']['TYPE_COURSE']['VALUE']):?> <span><?=$arItem['PROPERTIES']['TYPE_COURSE']['VALUE']?></span><?endif;?>
                    </div>
                <?endif;?>
            <?endforeach; ?>
        </div>
        <div class="section-menu">
            <div class="catalog-menu-element-title"><?= GetMessage('LASH');?></div>
            <? foreach($arResult["ITEMS"] as $arItem):
                if($arItem['IBLOCK_SECTION_ID'] == 2):?>
                    <div class="cources-menu-element nail" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
                        <?if($arItem['PROPERTIES']['TYPE_COURSE']['VALUE']):?> <span><?=$arItem['PROPERTIES']['TYPE_COURSE']['VALUE']?></span><?endif;?>
                    </div>
                <?endif;?>
            <?endforeach;?>   
        </div>
</section>





