

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
?>

    <section class="cources-blocks-list" id="cources-blocks-slider">

    <?
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

        <div class="cources-list-element <?= $course_section; ?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>" onclick='window.location.href="<?=$arItem['DETAIL_PAGE_URL']?>"'>
            <div class="cources-list-element-wrap">
                        <div class="type-course"><?=$arItem['PROPERTIES']['TYPE_COURSE']['VALUE']?><span class="cources-list-price">, <?print_r($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'])?>
                                <?if($arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == 'Бесплатно' || $arItem['DISPLAY_PROPERTIES']['PRICE']['VALUE'] == NULL):?>
                                <?else:?>
                                    ₽
                                <?endif;?>
                            </span>
                        </div>
                        <div class="courses-list-name">
			                <a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a>
			            </div>
                        <div class="date-begin">
                            <?if($arItem['PROPERTIES']['COUNT_DAY']['VALUE']):?><span><?=$arItem['PROPERTIES']['COUNT_DAY']['VALUE']?><span>:<?endif;?>
                            <?if($arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE']):?>
                                <?=$arItem['DISPLAY_PROPERTIES']['BEGIN']['VALUE']?>
                            <?endif;?>  
                        </div>
            </div>
        </div>
    <?endforeach;?>
</section>




