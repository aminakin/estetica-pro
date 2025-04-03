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
?>

<?if($arResult['PROPERTIES']['DATE_END_SALE']['VALUE']):?>
<?
    $dateEndSale = strtotime($arResult['PROPERTIES']['DATE_END_SALE']['VALUE']);
    $dateToday = strtotime(date('d.m.Y'));

    if($dateEndSale < $dateToday):?>
        <section class="new-blocks-detail end-sale">
            <h3>
                К сожалению<br />
                акция уже закончилась.
            </h3>
            <p>Вы можете посмотреть другие наши акции.</p>

        </section>

    <?else:?>
        <section class="new-blocks-detail sale-det">
            <h1 class="hideFull"><?=$arResult["NAME"]?></h1>
            <div class="new-blocks-detail-left">
                <?if($arParams["DISPLAY_PICTURE"]!="N" && is_array($arResult["DETAIL_PICTURE"])):?>
                    <img src="<?=$arResult["DETAIL_PICTURE"]["SRC"]?>" alt="<?=$arResult["DETAIL_PICTURE"]["ALT"]?>" />
                    <div class="sale-logo">
                        <div class="sale-logo-img">
                            <?
                            $APPLICATION->IncludeComponent(
                                "smedia:sale.brand", 
                                ".default", 
                                array(
                                    "IBLOCK_ID" => 6,
                                    "BRAND_ID" => $arResult['PROPERTIES']['BRAND']['VALUE']
                                ),
                                false
                            );
                            ?>
                        </div>
                        <?/*if($arResult['PROPERTIES']['DATE_END_SALE']['VALUE']):?>
                            <?
                            $dateEndSale = strtotime($arResult['PROPERTIES']['DATE_END_SALE']['VALUE']);
                            $dateToday = strtotime(date('d.m.Y'));

                            if($dateEndSale < $dateToday):?>
                                <div class="icon-sale">
                                    Срок акции истек
                                </div>
                            <?endif;?>
                        <?endif;*/?>
                    </div>
                <?endif?>
            </div>
            <div class="new-blocks-detail-right">
                <h1 class="hideMobile"><?=$arResult["NAME"]?></h1>
                <?if ($dateEndSale)
                {?>
                    <div>
                        Сроки проведения акции:
                        <?=($arResult['ACTIVE_FROM'])?'c '.$arResult['ACTIVE_FROM']:''?>
                        до <?=$arResult['PROPERTIES']['DATE_END_SALE']['VALUE']?>
                    </div>
                <?}?>
                <div class="detail-text">
                    <?if(strlen($arResult["DETAIL_TEXT"])>0):?>
                        <?echo $arResult["DETAIL_TEXT"];?>
                    <?endif;?>
                </div>
                <hr />
                <div class="new-block-net">
                    <?if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
                    {
                        ?>
                        <div class="news-detail-share">
                            <noindex>
                                <?$APPLICATION->IncludeComponent(
               "bitrix:news.list", 
               "soc_net_footer", 
                array(
                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "A",
                    "CHECK_DATES" => "Y",
                    "DETAIL_URL" => "",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "DISPLAY_DATE" => "N",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PICTURE" => "N",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "FIELD_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "FILTER_NAME" => "",
                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                    "IBLOCK_ID" => IblockHelper::getIdByCode('soc'),
                    "IBLOCK_TYPE" => "data",
                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                    "INCLUDE_SUBSECTIONS" => "N",
                    "MESSAGE_404" => "",
                    "NEWS_COUNT" => "3",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Новости",
                    "PARENT_SECTION" => "",
                    "PARENT_SECTION_CODE" => "",
                    "PREVIEW_TRUNCATE_LEN" => "",
                    "PROPERTY_CODE" => array(
                        0 => "SUBSCRIBERS",
                        1 => "NET",
                        2 => "",
                    ),
                    "SET_BROWSER_TITLE" => "N",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "N",
                    "SET_META_KEYWORDS" => "N",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "N",
                    "SHOW_404" => "N",
                    "SORT_BY1" => "SORT",
                    "SORT_BY2" => "NAME",
                    "SORT_ORDER1" => "ASC",
                    "SORT_ORDER2" => "DESC",
                    "STRICT_SECTION_CHECK" => "N",
                    "COMPONENT_TEMPLATE" => "soc_net_footer"
                ),
                false
            );?>
                            </noindex>
                        </div>
                    <?}?>
                </div>
            </div>
            <div class="clear"></div>
        </section>
        <?if ($arResult['PROPERTIES']['ADDITIONAL']['VALUE'])
        {
            GLOBAL $arrFilterCatalog;
            $arrFilterCatalog['ID'] = $arResult['PROPERTIES']['ADDITIONAL']['VALUE'];
            $APPLICATION->IncludeComponent(
                "bitrix:catalog.section",
                "action",
                array(
                    "ACTION_VARIABLE" => "action",
                    "ADD_PICT_PROP" => "-",
                    "ADD_PROPERTIES_TO_BASKET" => "Y",
                    "ADD_SECTIONS_CHAIN" => "N",
                    "ADD_TO_BASKET_ACTION" => "ADD",
                    "AJAX_MODE" => "N",
                    "AJAX_OPTION_ADDITIONAL" => "",
                    "AJAX_OPTION_HISTORY" => "N",
                    "AJAX_OPTION_JUMP" => "N",
                    "AJAX_OPTION_STYLE" => "Y",
                    "BACKGROUND_IMAGE" => "-",
                    "BASKET_URL" => "/personal/basket.php",
                    "BROWSER_TITLE" => "-",
                    "CACHE_FILTER" => "N",
                    "CACHE_GROUPS" => "Y",
                    "CACHE_TIME" => "36000000",
                    "CACHE_TYPE" => "N",
                    "COMPATIBLE_MODE" => "Y",
                    "CONVERT_CURRENCY" => "N",
                    "CUSTOM_FILTER" => "",
                    "DETAIL_URL" => "",
                    "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                    "DISPLAY_BOTTOM_PAGER" => "N",
                    "DISPLAY_COMPARE" => "Y",
                    "DISPLAY_TOP_PAGER" => "N",
                    "ELEMENT_SORT_FIELD" => "RAND",
                    "ELEMENT_SORT_FIELD2" => "name",
                    "ELEMENT_SORT_ORDER" => "asc",
                    "ELEMENT_SORT_ORDER2" => "asc",
                    "ENLARGE_PRODUCT" => "STRICT",
                    "FILTER_NAME" => "arrFilterCatalog",
                    "HIDE_NOT_AVAILABLE" => "N",
                    "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                    "IBLOCK_ID" => "3",
                    "IBLOCK_TYPE" => "catalog",
                    "INCLUDE_SUBSECTIONS" => "Y",
                    "LABEL_PROP" => array(
                    ),
                    "LABEL_PROP_MOBILE" => "",
                    "LABEL_PROP_POSITION" => "top-left",
                    "LAZY_LOAD" => "Y",
                    "LINE_ELEMENT_COUNT" => "3",
                    "LOAD_ON_SCROLL" => "N",
                    "MESSAGE_404" => "",
                    "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                    "MESS_BTN_BUY" => "Купить",
                    "MESS_BTN_DETAIL" => "Подробнее",
                    "MESS_BTN_SUBSCRIBE" => "Подписаться",
                    "MESS_BTN_LAZY_LOAD" => "Показать ещё",
                    "MESS_NOT_AVAILABLE" => "Нет в наличии",
                    "META_DESCRIPTION" => "-",
                    "META_KEYWORDS" => "-",
                    "OFFERS_LIMIT" => "6",
                    "PAGER_BASE_LINK_ENABLE" => "N",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "N",
                    "PAGER_SHOW_ALWAYS" => "N",
                    "PAGER_TEMPLATE" => ".default",
                    "PAGER_TITLE" => "Товары",
                    "PAGE_ELEMENT_COUNT" => "2",
                    "PARTIAL_PRODUCT_PROPERTIES" => "N",
                    "PRICE_CODE" => array(
                        0 => "Розничные",
                    ),
                    "PRICE_VAT_INCLUDE" => "Y",
                    "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons,compare",
                    "PRODUCT_ID_VARIABLE" => "id",
                    "PRODUCT_PROPERTIES" => array(
                        0 => 'ICON'
                    ),
                    "PRODUCT_PROPS_VARIABLE" => "prop",
                    "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                    "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
                    "PRODUCT_SUBSCRIPTION" => "Y",
                    "PROPERTY_CODE" => array(
                        0 => "",
                        1 => "",
                    ),
                    "PROPERTY_CODE_MOBILE" => array(
                    ),
                    "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
                    "RCM_TYPE" => "personal",
                    "SECTION_CODE" => "",
                    "SECTION_ID" => $_REQUEST["SECTION_ID"],
                    "SECTION_ID_VARIABLE" => "SECTION_ID",
                    "SECTION_URL" => "",
                    "SECTION_USER_FIELDS" => array(
                        0 => "",
                        1 => "",
                    ),
                    "SEF_MODE" => "N",
                    "SET_BROWSER_TITLE" => "Y",
                    "SET_LAST_MODIFIED" => "N",
                    "SET_META_DESCRIPTION" => "Y",
                    "SET_META_KEYWORDS" => "Y",
                    "SET_STATUS_404" => "N",
                    "SET_TITLE" => "Y",
                    "SHOW_404" => "N",
                    "SHOW_ALL_WO_SECTION" => "Y",
                    "SHOW_CLOSE_POPUP" => "Y",
                    "SHOW_DISCOUNT_PERCENT" => "N",
                    "SHOW_FROM_SECTION" => "N",
                    "SHOW_MAX_QUANTITY" => "N",
                    "SHOW_OLD_PRICE" => "N",
                    "SHOW_PRICE_COUNT" => "1",
                    "SHOW_SLIDER" => "N",
                    "SLIDER_INTERVAL" => "3000",
                    "SLIDER_PROGRESS" => "N",
                    "TEMPLATE_THEME" => "blue",
                    "USE_ENHANCED_ECOMMERCE" => "N",
                    "USE_MAIN_ELEMENT_SECTION" => "N",
                    "USE_PRICE_COUNT" => "N",
                    "USE_PRODUCT_QUANTITY" => "Y",
                    "COMPONENT_TEMPLATE" => "catalog_list_new",
                    "COMPARE_PATH" => "",
                    "MESS_BTN_COMPARE" => "Сравнить",
                    "COMPARE_NAME" => "CATALOG_COMPARE_LIST",
                    'TITLE_H1' => 'Товары, участвующие в акции'
                ),
                false
            );
        }?>
    <?endif;?>
    
<?endif;?>



