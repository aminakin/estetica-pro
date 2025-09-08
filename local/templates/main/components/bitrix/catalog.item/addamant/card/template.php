<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $item
 * @var array $actualItem
 * @var array $minOffer
 * @var array $itemIds
 * @var array $price
 * @var array $measureRatio
 * @var bool $haveOffers
 * @var bool $showSubscribe
 * @var array $morePhoto
 * @var bool $showSliderf
 * @var string $imgTitle
 * @var string $productTitle
 * @var string $buttonSizeClass
 * @var CatalogSectionComponent $component
 */
?>
<div class="product-item product-item-container" id="<?= $item["ID"]; ?>">
    <div class="product-item-image-block">
        <? global $USER; ?>
        <? if ($USER->IsAuthorized()): ?>
            <div class="product-item-favorite-image-block<?= ' ' . $arParams['LABEL_TYPE'] ?>" data-item="<?= $item["ID"]; ?>">
                <?= classBuildSCG::buildSVG('favorite', $templateFolder . '/image'); ?>
            </div>
        <? else: ?>
            <a class="product-item-favorite-image-block no_auth_fav linkOpen <?= ' ' . $arParams['LABEL_TYPE'] ?>" href="#no_fav_popup" data-item="<?= $item["ID"]; ?>">
                <?= classBuildSCG::buildSVG('favorite', $templateFolder . '/image'); ?>
            </a>
        <? endif; ?>
        <? if (!empty($item['LABEL_ARRAY_VALUE'])) { ?>
            <div class="product-item-label-text <?= $labelPositionClass ?>" id="<?= $itemIds['STICKER_ID'] ?>">
                <? foreach ($item['LABEL_ARRAY_VALUE'] as $code => $value) {
                ?>
                    <? if ($value != 'Нет'): ?>
                        <span class="<?= $code ?>">
                            <? switch ($code) {
                                case 'AKTSIONNYY':
                                    echo 'Sale';
                                    break;
                                case 'SALE_RESNITSY':
                                    echo 'Sale';
                                    break;
                                case 'NEW':
                                    echo 'new';
                                    break;
                                case 'KHIT_PRODAZH':
                                    echo 'hit';
                                    break;
                                default:
                                    echo $value;
                                    break;
                            } ?>
                        </span>
                    <? endif; ?>
                <? } ?>
            </div>
        <? } ?>
        <? if ($item['PROPERTIES']['ADULT_PRODUCT']['VALUE'] === 'Y' && empty($_COOKIE['adultProduct'])) { ?>
            <div class="product-item-adult-block-wrap">
                <div class="product-item-adult-block">
                    <div class="product-item-adult-block-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="9" viewBox="0 0 15 9" fill="none">
                            <path d="M14 1C13.6275 1.86444 13.1487 2.66609 12.5774 3.3815C12.4097 3.59081 12.2334 3.7913 12.0491 3.98232L13.5564 5.66997M11.3634 4.61383C10.3657 5.43548 9.17811 5.91824 7.94223 6.00453V8M7.05866 6.00453C5.82287 5.91853 4.63525 5.43612 3.63746 4.61482L2.07001 6.37175M2.95181 3.98331C2.76723 3.79228 2.59062 3.59179 2.42255 3.38249C1.85127 2.6671 1.37242 1.86545 1 1.00099"
                                stroke="black" stroke-width="0.5" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="product-item-adult-block-text">Товары для взрослых</div>
                    <div onclick="openCloseModal(true)" class="product-item-adult-block-button">подтвердите ваш
                        возраст
                    </div>
                </div>
            </div>
        <? } else { ?>
            <a class="product-item-image-wrapper" href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $imgTitle ?>" data-entity="image-wrapper">
                <span class="product-item-image-slider-slide-container slide" id="<?= $itemIds['PICT_SLIDER'] ?>"
                    style="<?= ($showSlider ? '' : 'display: none') ?>;">
                    <? if ($showSlider) {
                        foreach ($morePhoto as $key => $photo) { ?>
                            <span class="product-item-image-slide item <?= ($key == 0 ? 'active' : '') ?>"
                                style="background-image: url(<?= SITE_TEMPLATE_PATH ?>/images/icons/loader.svg)"
                                data-src="<?= $photo['SRC'] ?>">
                            </span>
                    <? }
                    } ?>
                </span>
                <? $file = CFile::ResizeImageGet($item['PREVIEW_PICTURE']['ID'], array('width' => 400, 'height' => 400), BX_RESIZE_IMAGE_PROPORTIONAL, true); ?>
                <?
                if ($file == false):
                    $file['src'] = '/local/templates/main/components/bitrix/catalog.section/addamant/images/no_photo.png';
                endif;
                if ($item['MORE_PHOTO'][0]['SRC'] != NULL):
                    $file['src'] = $item['MORE_PHOTO'][0]['SRC'];
                endif; ?>

                <span class="product-item-image-original preload"
                    id="<?= $itemIds['PICT'] ?>"
                    style="background-image: url(<?= SITE_TEMPLATE_PATH ?>/images/icons/loader.svg)"
                    data-src="<?= $file['src']; ?>"
                    style="<?= ($showSlider ? 'display: none' : '') ?>;">
                </span>
                <? if ($item['SECOND_PICT']) {
                    $bgImage = !empty($item['PREVIEW_PICTURE_SECOND']) ? $item['PREVIEW_PICTURE_SECOND']['SRC'] : $item['PREVIEW_PICTURE']['SRC']; ?>
                    <span class="product-item-image-alternative preload" id="<?= $itemIds['SECOND_PICT'] ?>"
                        data-src="<?= $file['src']; ?>" style="background-image: url(<?= SITE_TEMPLATE_PATH ?>/images/icons/loader.svg);<?= ($showSlider ? 'display: none' : '') ?>">
                    </span>
                <? } ?>


                <div class="product-item-image-slider-control-container" id="<?= $itemIds['PICT_SLIDER'] ?>_indicator"
                    style="<?= ($showSlider ? '' : 'display: none') ?>;">
                    <? if ($showSlider) {
                        foreach ($morePhoto as $key => $photo) { ?>
                            <div class="product-item-image-slider-control<?= ($key == 0 ? ' active' : '') ?>"
                                data-go-to="<?= $key ?>"></div>
                    <? }
                    } ?>
                </div>
                <?
                if ($arParams['SLIDER_PROGRESS'] === 'Y') {
                ?>
                    <div class="product-item-image-slider-progress-bar-container">
                        <div class="product-item-image-slider-progress-bar"
                            id="<?= $itemIds['PICT_SLIDER'] ?>_progress_bar"
                            style="width: 0;"></div>
                    </div>
                <? } ?>
            </a>
            <script>
                $('[data-src]').lazy({
                    afterLoad: function(element) {
                        $(element).removeClass('preload');
                    },
                });
            </script>
        <? } ?>
    </div>
    <div class="product-item-payment-block">
    </div>
    <? if (!empty($arParams['PRODUCT_BLOCKS_ORDER'])) {
        foreach ($arParams['PRODUCT_BLOCKS_ORDER'] as $blockName) {
            switch ($blockName) {
                case 'price': ?>
                    <div class="product-item-info-container product-item-price-container price-block" data-entity="price-block">
                        <div class="product-item-title">
                            <a href="<?= $item['DETAIL_PAGE_URL'] ?>" title="<?= $productTitle ?>">
                                <?= $productTitle ?>
                            </a>
                            <? if ($item['PROPERTIES']['BEZNACHISLENIYABONUSA']['VALUE'] == 'Да'): ?>
                                <div class="no-bonus-product">
                                    <a class="linkOpen" href="#about_bonus">
                                        <span><?= GetMessage('CT_BCE_QUESTION'); ?></span>
                                        <?= classBuildSCG::buildSVG('question', $templateFolder . '/image'); ?>
                                    </a>
                                </div>
                            <? endif; ?>
                        </div>

                        <div class="product-item-price-wrap">
                            <? if ($arParams['SHOW_OLD_PRICE'] === 'Y') { ?>
                                <div class="product-item-price-old-wrapper">
                                    <span class="product-item-price-old" id="<?= $itemIds['PRICE_OLD'] ?>"
                                        <?= ($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? 'style="display: none;"' : '') ?>>
                                        <?= $haveOffers ? '' : $price['PRINT_RATIO_BASE_PRICE'] ?>
                                    </span>
                                </div>
                            <? } ?>
                            <span class="product-item-price-current <?= ($price['RATIO_PRICE'] >= $price['RATIO_BASE_PRICE'] ? '' : 'old') ?>" id="<?= $itemIds['PRICE'] ?>">
                                <? if (!empty($price)) {
                                    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'N' && $haveOffers) {
                                        echo Loc::getMessage(
                                            'CT_BCI_TPL_MESS_PRICE_SIMPLE_MODE',
                                            array(
                                                '#PRICE#' => $price['PRINT_RATIO_PRICE'],
                                                '#VALUE#' => $measureRatio,
                                                '#UNIT#' => $minOffer['ITEM_MEASURE']['TITLE']
                                            )
                                        );
                                    } else {
                                        echo $price['PRINT_RATIO_PRICE'];
                                    }
                                } ?>
                            </span>
                        </div>
                    </div>
                    <?
                    break;

                case 'quantityLimit':
                    if ($arParams['SHOW_MAX_QUANTITY'] !== 'N') {
                        if ($haveOffers) {
                            if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y') {
                    ?>
                                <div class="product-item-info-container quantity-limit-block"
                                    id="<?= $itemIds['QUANTITY_LIMIT'] ?>"
                                    style="display: none;" data-entity="quantity-limit-block">
                                    <div class="product-item-info-container-title">
                                        <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                        <span class="product-item-quantity" data-entity="quantity-limit-value"></span>
                                    </div>
                                </div>
                            <?
                            }
                        } else {
                            if (
                                $measureRatio
                                && (float)$actualItem['CATALOG_QUANTITY'] > 0
                                && $actualItem['CATALOG_QUANTITY_TRACE'] === 'Y'
                                && $actualItem['CATALOG_CAN_BUY_ZERO'] === 'N'
                            ) {
                            ?>
                                <div class="product-item-info-container quantity-limit-block"
                                    id="<?= $itemIds['QUANTITY_LIMIT'] ?>">
                                    <div class="product-item-info-container-title">
                                        <?= $arParams['MESS_SHOW_MAX_QUANTITY'] ?>:
                                        <span class="product-item-quantity">
                                            <?
                                            if ($arParams['SHOW_MAX_QUANTITY'] === 'M') {
                                                if ((float)$actualItem['CATALOG_QUANTITY'] / $measureRatio >= $arParams['RELATIVE_QUANTITY_FACTOR']) {
                                                    echo $arParams['MESS_RELATIVE_QUANTITY_MANY'];
                                                } else {
                                                    echo $arParams['MESS_RELATIVE_QUANTITY_FEW'];
                                                }
                                            } else {
                                                echo $actualItem['CATALOG_QUANTITY'] . ' ' . $actualItem['ITEM_MEASURE']['TITLE'];
                                            }
                                            ?>
                                        </span>
                                    </div>
                                </div>
                            <?
                            }
                        }
                    }

                    break;

                case 'quantity':
                    /*if (!$item['NO_DANATE']):
                        if (!$haveOffers) {
                            if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY']) {
                                ?>
                                <div class="product-item-info-container" data-entity="quantity-block">
                                    <!--                product-item-hidden-->
                                    <div class="product-item-amount">
                                        <div class="product-item-amount-field-container jq-number qty">

                                            <a class="product-item-amount-field-btn-minus jq-number__spin minus"
                                               id="<?= $itemIds['QUANTITY_DOWN'] ?>"
                                               href="javascript:void(0)" rel="nofollow">
                                            </a>

                                            <div class="jq-number__field efers">
                                                <input class="product-item-amount-field"
                                                       id="<?= $itemIds['QUANTITY'] ?>"
                                                       type="number"
                                                       name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>"
                                                       value="<?= $measureRatio ?>">
                                            </div>

                                            <a class="product-item-amount-field-btn-plus jq-number__spin plus"
                                               id="<?= $itemIds['QUANTITY_UP'] ?>"
                                               href="javascript:void(0)" rel="nofollow">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <?
                            }
                        } 
                    endif;*/

                    break;

                case 'buttons':

                    if (!$item['NO_DANATE']):
                        if (!$haveOffers): ?>
                            <div class="product-item-info-container buttons" data-entity="buttons-block">
                                <? if ($actualItem['CAN_BUY'] && $arParams['USE_PRODUCT_QUANTITY']) { ?>
                                    <div class="product-item-info-container" data-entity="quantity-block">
                                        <div class="product-item-amount">
                                            <div class="product-item-amount-field-container jq-number qty">
                                                <a class="product-item-amount-field-btn-minus jq-number__spin minus"
                                                    id="<?= $itemIds['QUANTITY_DOWN'] ?>"
                                                    href="javascript:void(0)" rel="nofollow"><?= classBuildSCG::buildSVG('minus'); ?>
                                                </a>
                                                <input class="product-item-amount-field"
                                                    id="<?= $itemIds['QUANTITY'] ?>"
                                                    type="number"
                                                    name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>"
                                                    value="<?= $measureRatio ?>">
                                                <a class="product-item-amount-field-btn-plus jq-number__spin plus"
                                                    id="<?= $itemIds['QUANTITY_UP'] ?>"
                                                    href="javascript:void(0)" rel="nofollow"><?= classBuildSCG::buildSVG('plus'); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <? } ?>
                                <? if ($actualItem['CAN_BUY']) {
                                ?>
                                    <div class="product-item-button-container" id="<?= $itemIds['BASKET_ACTIONS'] ?>">
                                        <a class="product-item-button-link whiteBtn btn-link buyBtn btn <?= $buttonSizeClass ?>"
                                            data-id="<?= $item['ID'] ?>"
                                            id="<?= $itemIds['BUY_LINK'] ?>"
                                            href="javascript:void(0)" rel="nofollow"
                                            <? if ($actualItem['IBLOCK_SECTION_ID'] == 1190): ?>onclick="ym(51439306,'reachGoal','target')" <? endif; ?>>
                                            <?= getMessage('MESS_BUY'); ?>
                                            <?= classBuildSCG::buildSVG('cart', $templateFolder . "/image"); ?>
                                        </a>
                                    </div>
                                <?
                                } else {
                                ?>
                                    <div class="product-item-button-container">
                                        <? if ($showSubscribe) { ?>
                                        <?
                                            $APPLICATION->IncludeComponent(
                                                'bitrix:catalog.product.subscribe',
                                                'itemtemplate',
                                                array(
                                                    'PRODUCT_ID' => $actualItem['ID'],
                                                    'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                                    'BUTTON_CLASS' => 'bx-catalog-subscribe-button',
                                                    'DEFAULT_DISPLAY' => true,
                                                    'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                                ),
                                                $component,
                                                array('HIDE_ICONS' => 'Y')
                                            );
                                        } ?>
                                    </div>
                                <? } ?>
                            </div>

                        <? endif; ?>
                    <? else: ?>
                        <div class="no-danate">
                            <?= GetMessage('CT_BCE_NO_DANATE'); ?>
                        </div>
                        <? endif;
                    break;

                case 'props':
                    if (!$haveOffers) {
                        if (!empty($item['DISPLAY_PROPERTIES'])) {
                        ?>
                            <div class="product-item-info-container product-item-hidden" data-entity="props-block">
                                <dl class="product-item-properties">
                                    <?
                                    foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty) {
                                    ?>
                                        <dt<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                            <?= $displayProperty['NAME'] ?>
                                            </dt>
                                            <dd<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                                <?= (is_array($displayProperty['DISPLAY_VALUE'])
                                                    ? implode(' / ', $displayProperty['DISPLAY_VALUE'])
                                                    : $displayProperty['DISPLAY_VALUE']) ?>
                                                </dd>
                                            <?
                                        }
                                            ?>
                                </dl>
                            </div>
                        <?
                        }

                        if ($arParams['ADD_PROPERTIES_TO_BASKET'] === 'Y' && !empty($item['PRODUCT_PROPERTIES'])) {
                        ?>
                            <div id="<?= $itemIds['BASKET_PROP_DIV'] ?>" style="display: none;">
                                <?
                                if (!empty($item['PRODUCT_PROPERTIES_FILL'])) {
                                    foreach ($item['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo) {
                                ?>
                                        <input type="hidden"
                                            name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propID ?>]"
                                            value="<?= htmlspecialcharsbx($propInfo['ID']) ?>">
                                    <?
                                        unset($item['PRODUCT_PROPERTIES'][$propID]);
                                    }
                                }

                                if (!empty($item['PRODUCT_PROPERTIES'])) {
                                    ?>
                                    <table>
                                        <?
                                        foreach ($item['PRODUCT_PROPERTIES'] as $propID => $propInfo) {
                                        ?>
                                            <tr>
                                                <td><?= $item['PROPERTIES'][$propID]['NAME'] ?></td>
                                                <td>
                                                    <?
                                                    if (
                                                        $item['PROPERTIES'][$propID]['PROPERTY_TYPE'] === 'L'
                                                        && $item['PROPERTIES'][$propID]['LIST_TYPE'] === 'C'
                                                    ) {
                                                        foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                    ?>
                                                            <label>
                                                                <? $checked = $valueID === $propInfo['SELECTED'] ? 'checked' : ''; ?>
                                                                <input type="radio"
                                                                    name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propID ?>]"
                                                                    value="<?= $valueID ?>" <?= $checked ?>>
                                                                <?= $value ?>
                                                            </label>
                                                            <br />
                                                        <?
                                                        }
                                                    } else {
                                                        ?>
                                                        <select name="<?= $arParams['PRODUCT_PROPS_VARIABLE'] ?>[<?= $propID ?>]">
                                                            <?
                                                            foreach ($propInfo['VALUES'] as $valueID => $value) {
                                                                $selected = $valueID === $propInfo['SELECTED'] ? 'selected' : '';
                                                            ?>
                                                                <option value="<?= $valueID ?>" <?= $selected ?>>
                                                                    <?= $value ?>
                                                                </option>
                                                            <?
                                                            }
                                                            ?>
                                                        </select>
                                                    <?
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?
                                        }
                                        ?>
                                    </table>
                                <?
                                }
                                ?>
                            </div>
                        <?
                        }
                    } else {
                        $showProductProps = !empty($item['DISPLAY_PROPERTIES']);
                        $showOfferProps = $arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $item['OFFERS_PROPS_DISPLAY'];

                        if ($showProductProps || $showOfferProps) {
                        ?>
                            <div class="product-item-info-container product-item-hidden" data-entity="props-block">
                                <dl class="product-item-properties">
                                    <?
                                    if ($showProductProps) {
                                        foreach ($item['DISPLAY_PROPERTIES'] as $code => $displayProperty) {
                                    ?>
                                            <dt<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                                <?= $displayProperty['NAME'] ?>
                                                </dt>
                                                <dd<?= (!isset($item['PROPERTY_CODE_MOBILE'][$code]) ? ' class="hidden-xs"' : '') ?>>
                                                    <?= (is_array($displayProperty['DISPLAY_VALUE'])
                                                        ? implode(' / ', $displayProperty['DISPLAY_VALUE'])
                                                        : $displayProperty['DISPLAY_VALUE']) ?>
                                                    </dd>
                                                <?
                                            }
                                        }

                                        if ($showOfferProps) {
                                                ?>
                                                <span id="<?= $itemIds['DISPLAY_PROP_DIV'] ?>" style="display: none;"></span>
                                            <?
                                        }
                                            ?>
                                </dl>
                            </div>
                        <?
                        }
                    }

                    break;

                case 'sku':
                    if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y' && $haveOffers && !empty($item['OFFERS_PROP'])) {
                        ?>
                        <div id="<?= $itemIds['PROP_DIV'] ?>" class="sku-btn-wrap">

                            <?
                            
                            if ($item['OFFERS_PROP']['TSVET'] == true) {
                                foreach ($arParams['SKU_PROPS'] as $skuProperty) {
                                    $propertyId = $skuProperty['ID'];
                                    foreach ($skuProperty['VALUES'] as $key => $value) {
                                        
                                        if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
                                            continue;

                                        $count++;
                                    }
                                } ?>
                                <div class="count-color"><a href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                        <? $titles = array('ещё %d цвет', 'ещё %d цвета', 'ещё %d цветов');

                                        echo declOfNumCountCatalog($count, $titles); ?></a>
                                </div>
                            <? } ?>
                            <div class="sku-button <? //if($item['OFFERS_PROP']['TSVET'] == true){echo 'tsvet';}
                                                    ?>">
                                <span class="desctop"><?= GetMessage('CT_BCE_SKU_BUTTON'); ?></span>
                                <span class="mobile"><?= GetMessage('CT_BCE_SKU_BUTTON_MOB'); ?></span>
                            </div>
                            <div class="sku-block-wrap <? //if($item['OFFERS_PROP']['TSVET'] == true){echo 'tsvet';}
                                                        ?>">
                                <?
                                foreach ($arParams['SKU_PROPS'] as $skuProperty) {

                                    $propertyId = $skuProperty['ID'];
                                    $skuProperty['NAME'] = htmlspecialcharsbx($skuProperty['NAME']);
                                    if (!isset($item['SKU_TREE_VALUES'][$propertyId]))
                                        continue;
                                ?>
                                    <div class="product-item-info-container product-item-hidden" data-entity="sku-block">
                                        <div class="product-item-scu-container <? //if($item['OFFERS_PROP']['TSVET'] == true){echo 'tsvet';}
                                                                                ?>" data-entity="sku-line-block">
                                            <div class="title_props"><?= $skuProperty['NAME'] ?></div>
                                            <div class="product-item-scu-block">
                                                <div class="product-item-scu-list">
                                                    <ul class="product-item-scu-item-list">
                                                        <? //$count = 0;
                                                        foreach ($skuProperty['VALUES'] as $value) {


                                                            if (!isset($item['SKU_TREE_VALUES'][$propertyId][$value['ID']]))
                                                                continue;

                                                            $value['NAME'] = htmlspecialcharsbx($value['NAME']);

                                                            if ($skuProperty['SHOW_MODE'] === 'PICT') {
                                                        ?>
                                                                <li class="product-item-scu-item-color-container"
                                                                    title="<?= $value['NAME'] ?>"
                                                                    data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                    data-onevalue="<?= $value['ID'] ?>">
                                                                    <div class="product-item-scu-item-color-block">
                                                                        <div class="product-item-scu-item-color"
                                                                            title="<?= $value['NAME'] ?>"
                                                                            style="background-image: url(<?= $value['PICT']['SRC'] ?>);">
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            <?
                                                            } else {
                                                                $count++; ?>
                                                                <li class="product-item-scu-item-text-container"
                                                                    title="<?= $value['NAME'] ?>"
                                                                    data-treevalue="<?= $propertyId ?>_<?= $value['ID'] ?>"
                                                                    data-onevalue="<?= $value['ID'] ?>">
                                                                    <div class="product-item-scu-item-text-block">
                                                                        <div class="product-item-scu-item-text"><?= $value['NAME'] ?></div>
                                                                    </div>
                                                                </li>
                                                        <?
                                                            }
                                                        }
                                                        ?>
                                                    </ul>
                                                    <div style="clear: both;"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                <?
                                }
                                if (!$item['NO_DANATE']): ?>
                                    <div class="product-item-info-container buttons" data-entity="buttons-block">
                                        <? if ($haveOffers) {
                                            if ($arParams['USE_PRODUCT_QUANTITY']) {
                                        ?>
                                                <div class="product-item-info-container" data-entity="quantity-block">
                                                    <div class="product-item-amount">
                                                        <div class="product-item-amount-field-container jq-number qty">
                                                            <a class="product-item-amount-field-btn-minus jq-number__spin minus"
                                                                id="<?= $itemIds['QUANTITY_DOWN'] ?>"
                                                                href="javascript:void(0)" rel="nofollow"><?= classBuildSCG::buildSVG('minus'); ?>
                                                            </a>
                                                            <div class="jq-number__field">
                                                                <input class="product-item-amount-field"
                                                                    id="<?= $itemIds['QUANTITY'] ?>"
                                                                    type="number"
                                                                    name="<?= $arParams['PRODUCT_QUANTITY_VARIABLE'] ?>"
                                                                    value="<?= $measureRatio ?>">
                                                            </div>
                                                            <a class="product-item-amount-field-btn-plus jq-number__spin plus"
                                                                id="<?= $itemIds['QUANTITY_UP'] ?>"
                                                                href="javascript:void(0)" rel="nofollow"><?= classBuildSCG::buildSVG('plus'); ?>
                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                        <?
                                            }
                                        } ?>
                                        <? if ($haveOffers) {
                                            if ($arParams['PRODUCT_DISPLAY_MODE'] === 'Y') { ?>
                                                <div class="product-item-button-container">
                                                    <? if ($showSubscribe) {
                                                        $APPLICATION->IncludeComponent(
                                                            'bitrix:catalog.product.subscribe',
                                                            'itemtemplate',
                                                            array(
                                                                'PRODUCT_ID' => $item['ID'],
                                                                'BUTTON_ID' => $itemIds['SUBSCRIBE_LINK'],
                                                                'BUTTON_CLASS' => 'btn btn-default ' . $buttonSizeClass,
                                                                'DEFAULT_DISPLAY' => !$actualItem['CAN_BUY'],
                                                                'MESS_BTN_SUBSCRIBE' => $arParams['~MESS_BTN_SUBSCRIBE'],
                                                            ),
                                                            $component,
                                                            array('HIDE_ICONS' => 'Y')
                                                        );
                                                    } ?>
                                                    <div id="<?= $itemIds['BASKET_ACTIONS'] ?>"
                                                        style="<?= ($actualItem['CAN_BUY'] ? '' : 'display: none') ?>;"
                                                        class="buy-button">
                                                        <a class="product-item-button-link whiteBtn btn-link buyBtn btn btn-sm <?= $buttonSizeClass ?>"
                                                            data-id="<?= $item['ID'] ?>" id="<?= $itemIds['BUY_LINK'] ?>"
                                                            href="javascript:void(0)" rel="nofollow">
                                                            <?= getMessage('MESS_BUY'); ?>
                                                            <?= classBuildSCG::buildSVG('cart', $templateFolder . "/image"); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            <?
                                            } else {
                                            ?>
                                                <div class="product-item-button-container">
                                                    <a class="btn btn-default <?= $buttonSizeClass ?>"
                                                        href="<?= $item['DETAIL_PAGE_URL'] ?>">
                                                        <?= $arParams['MESS_BTN_DETAIL'] ?>
                                                    </a>
                                                </div>
                                        <?
                                            }
                                        } ?>
                                    </div>
                                <? else: ?>
                                    <div class="no-danate">
                                        <?= GetMessage('CT_BCE_NO_DANATE'); ?>
                                    </div>
                                <? endif;
                                ?>
                            </div>
                        </div>
        <?
                        foreach ($arParams['SKU_PROPS'] as $skuProperty) {
                            if (!isset($item['OFFERS_PROP'][$skuProperty['CODE']]))
                                continue;

                            $skuProps[] = array(
                                'ID' => $skuProperty['ID'],
                                'SHOW_MODE' => $skuProperty['SHOW_MODE'],
                                'VALUES' => $skuProperty['VALUES'],
                                'VALUES_COUNT' => $skuProperty['VALUES_COUNT']
                            );
                        }

                        unset($skuProperty, $value);

                        if ($item['OFFERS_PROPS_DISPLAY']) {
                            foreach ($item['JS_OFFERS'] as $keyOffer => $jsOffer) {
                                $strProps = '';

                                if (!empty($jsOffer['DISPLAY_PROPERTIES'])) {
                                    foreach ($jsOffer['DISPLAY_PROPERTIES'] as $displayProperty) {
                                        $strProps .= '<dt>' . $displayProperty['NAME'] . '</dt><dd>'
                                            . (is_array($displayProperty['VALUE'])
                                                ? implode(' / ', $displayProperty['VALUE'])
                                                : $displayProperty['VALUE'])
                                            . '</dd>';
                                    }
                                }

                                $item['JS_OFFERS'][$keyOffer]['DISPLAY_PROPERTIES'] = $strProps;
                            }
                            unset($jsOffer, $strProps);
                        }
                    }

                    break;
            }
        }
    }

    if (
        $arParams['DISPLAY_COMPARE']
        && (!$haveOffers || $arParams['PRODUCT_DISPLAY_MODE'] === 'Y')
    ) {
        ?>
        <div class="product-item-compare-container">
            <div class="product-item-compare">
                <div class="checkbox">
                    <label id="<?= $itemIds['COMPARE_LINK'] ?>">
                        <input type="checkbox" data-entity="compare-checkbox">
                        <span data-entity="compare-title"><?= $arParams['MESS_BTN_COMPARE'] ?></span>
                    </label>
                </div>
            </div>
        </div>
    <?
    }
    ?>
</div>